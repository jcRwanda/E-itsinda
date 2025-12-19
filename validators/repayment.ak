// use cardano/assets.{lovelace_of, PolicyId, AssetName}
// use cardano/transaction.{Transaction, OutputReference}
// use cardano/address.{Address}
// use aiken/collection/list
// use loan_utils

// /// =======================================================
// /// Loan Datum
// /// =======================================================
// pub type LoanDatum {
//   loan_id: ByteArray,

//   principal: Int,
//   interest_rate_n: Int,
//   interest_rate_d: Int,

//   start_time: Int,
//   due_time: Int,

//   borrower: Address,
//   lender: Address,

//   late_rate_n: Int,
//   late_rate_d: Int,

//   protocol_fee_n: Int,
//   protocol_fee_d: Int,

//   loan_nft_policy: PolicyId,
//   loan_nft_name: AssetName,
// }

// /// =======================================================
// /// Redeemer includes borrower & transaction time
// /// =======================================================
// pub type RepaymentRedeemer {
//   borrower: Address,
//   current_time: Int
// }

// /// =======================================================
// /// Repayment Validator
// /// =======================================================
// validator repayment {

//   spend(
//     datum_opt: Option<LoanDatum>,
//     redeemer: RepaymentRedeemer,
//     _utxo: OutputReference,
//     self: Transaction
//   ) {

//     // 1️⃣ Ensure datum exists
//     expect Some(datum) = datum_opt
//     trace @"Loan ID": datum.loan_id

//     // 2️⃣ NFT gating
//     expect loan_utils.has_loan_nft(self, datum.loan_nft_policy, datum.loan_nft_name)

//     // 3️⃣ Borrower authorization
//     expect redeemer.borrower == datum.borrower

//     // 4️⃣ Calculate late days
//     let late_days = loan_utils.days_late(redeemer.current_time, datum.due_time)
//     trace @"Late days": late_days

//     // 5️⃣ Base interest
//     let base_amount =
//       loan_utils.base_interest(
//         datum.principal,
//         datum.interest_rate_n,
//         datum.interest_rate_d
//       )

//     // 6️⃣ Apply late interest
//     let amount_with_late =
//       if late_days > 0 {
//         loan_utils.late_interest(
//           base_amount,
//           late_days,
//           datum.late_rate_n,
//           datum.late_rate_d
//         )
//       } else {
//         base_amount
//       }

//     // 7️⃣ Protocol fee
//     let fee =
//       loan_utils.protocol_fee(
//         amount_with_late,
//         datum.protocol_fee_n,
//         datum.protocol_fee_d
//       )

//     let total_due = amount_with_late + fee
//     trace @"Total due": total_due

//     // 8️⃣ Output verification
//     let lender_paid =
//       self.outputs |> list.any(fn(o) {
//         o.address == datum.lender &&
//         lovelace_of(o.value) >= amount_with_late
//       })

//     let protocol_paid =
//       self.outputs |> list.any(fn(o) {
//         o.address == loan_utils.protocol_address() &&
//         lovelace_of(o.value) >= fee
//       })

//     expect lender_paid
//     expect protocol_paid

//     // 9️⃣ Success
//     trace @"Loan repaid successfully": datum.loan_id
//     True
//   }
// }


use cardano/assets.{lovelace_of, PolicyId, AssetName}
use cardano/transaction.{Transaction, OutputReference}
use cardano/address.{Address}
use aiken/collection/list
use loan_utils

/// =======================================================
/// Loan Datum
/// Represents a single loan state locked at the script
/// =======================================================
pub type LoanDatum {
  loan_id: ByteArray,

  principal: Int,
  interest_rate_n: Int,
  interest_rate_d: Int,

  start_time: Int,
  due_time: Int,

  borrower: Address,
  lender: Address,

  late_rate_n: Int,
  late_rate_d: Int,

  protocol_fee_n: Int,
  protocol_fee_d: Int,

  loan_nft_policy: PolicyId,
  loan_nft_name: AssetName,
}

/// =======================================================
/// Redeemer
/// Passes the borrower identity for authorization
/// =======================================================
pub type RepaymentRedeemer {
  borrower: Address
}

/// =======================================================
/// Repayment Spending Validator
/// =======================================================
validator repayment {

  spend(
    datum_opt: Option<LoanDatum>,
    redeemer: RepaymentRedeemer,
    _utxo: OutputReference,
    self: Transaction
  ) {

    // 1️⃣ Ensure datum exists
    expect Some(datum) = datum_opt
    trace @"Loan ID": datum.loan_id

    // 2️⃣ NFT gating
    expect loan_utils.has_loan_nft(self, datum.loan_nft_policy, datum.loan_nft_name)

    // 3️⃣ Borrower authorization via redeemer
    expect redeemer.borrower == datum.borrower

    // 4️⃣ Calculate late days
    let tx_time = self.validity_range.lower_bound
    let late_days = loan_utils.days_late(tx_time, datum.due_time)
    trace @"Late days": late_days

    // 5️⃣ Base interest calculation
    let base_amount = loan_utils.base_interest(datum.principal, datum.interest_rate_n, datum.interest_rate_d)

    // 6️⃣ Late interest applied if overdue
    let amount_with_late =
      if late_days > 0 {
        loan_utils.late_interest(base_amount, late_days, datum.late_rate_n, datum.late_rate_d)
      } else {
        base_amount
      }

    // 7️⃣ Protocol fee calculation
    let fee = loan_utils.protocol_fee(amount_with_late, datum.protocol_fee_n, datum.protocol_fee_d)

    let total_due = amount_with_late + fee
    trace @"Total due": total_due

    // 8️⃣ Verify outputs to lender and protocol fee address
    let lender_paid = loan_utils.paid_to_address(self, datum.lender, amount_with_late)
    let protocol_paid = loan_utils.paid_to_address(self, loan_utils.protocol_address(), fee)

    expect lender_paid
    expect protocol_paid

    // 9️⃣ Success
    trace @"Loan repaid successfully": datum.loan_id
    True
  }
}
