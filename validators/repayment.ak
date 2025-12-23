use cardano/assets.{PolicyId, AssetName}
use cardano/transaction.{Transaction, OutputReference}
use cardano/address.{Address}
use aiken/interval.{IntervalBoundType}
use loan_utils

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

pub type RepaymentRedeemer {
  borrower: Address
}

validator repayment {
  spend(
    datum_opt: Option<LoanDatum>,
    redeemer: RepaymentRedeemer,
    _utxo: OutputReference,
    ctx: Transaction
  ) {
    expect Some(datum) = datum_opt
    trace @"Processing loan": datum.loan_id
    expect redeemer.borrower == datum.borrower
    trace @"Borrower authorized": True
    expect loan_utils.has_loan_nft(ctx, datum.loan_nft_policy, datum.loan_nft_name)
    trace @"NFT verified": True
    let tx_time = 
      when ctx.validity_range.lower_bound.bound_type is {
        IntervalBoundType.Finite(time) -> time
        _ -> 0
      }
    let late_days = loan_utils.days_late(tx_time, datum.due_time)
    trace @"Late days": late_days
    let base_amount = loan_utils.base_interest(
      datum.principal,
      datum.interest_rate_n,
      datum.interest_rate_d
    )
    let amount_with_late =
      if late_days > 0 {
        loan_utils.late_interest(base_amount, late_days, datum.late_rate_n, datum.late_rate_d)
      } else {
        base_amount
      }
    let fee = loan_utils.protocol_fee(
      amount_with_late,
      datum.protocol_fee_n,
      datum.protocol_fee_d
    )
    let total_due = amount_with_late + fee
    trace @"Total due": total_due
    let lender_paid = loan_utils.paid_to_address(
      ctx,
      datum.lender,
      amount_with_late
    )
    expect lender_paid
    trace @"Lender payment verified": True
    let protocol_paid = loan_utils.paid_to_address(
      ctx,
      loan_utils.protocol_address(),
      fee
    )
    expect protocol_paid
    trace @"Protocol fee payment verified": True
    trace @"Loan repaid successfully": datum.loan_id
    True
  }
}