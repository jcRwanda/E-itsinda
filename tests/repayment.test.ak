module repayment.test

use validators/repayment
use lib/loan_utils
use cardano/transaction.{Transaction, MockInput, MockOutput, MockValidityRange, OutputReference}
use cardano/address.{Address, Credential}
use cardano/assets
use aiken/collection/list

fn create_test_address(prefix: ByteArray) -> Address {
  let key_hash = ByteArray.slice(prefix, 0, 28)
  let payment = Credential.VerificationKey(key_hash)
  Address {
    payment_credential: payment,
    stake_credential: None,
  }
}

test test_on_time_repayment() {
  let borrower = create_test_address(ByteArray.from_hex("00000000000000000000000000000000000000000000000000000000"))
  let lender = create_test_address(ByteArray.from_hex("01000000000000000000000000000000000000000000000000000000"))
  
  let loan = LoanDatum {
    loan_id: ByteArray.from_hex("4c4e2d31"),
    principal: 10_000_000,
    interest_rate_n: 10,
    interest_rate_d: 100,
    start_time: 1_700_000_000,
    due_time: 1_700_086_400,
    borrower: borrower,
    lender: lender,
    late_rate_n: 1,
    late_rate_d: 100,
    protocol_fee_n: 2,
    protocol_fee_d: 100,
    loan_nft_policy: ByteArray.from_hex("706f6c696379"),
    loan_nft_name: ByteArray.from_hex("4e465431")
  }

  let base = loan_utils.base_interest(loan.principal, loan.interest_rate_n, loan.interest_rate_d)
  let fee = loan_utils.protocol_fee(base, loan.protocol_fee_n, loan.protocol_fee_d)

  let nft_asset = assets.singleton(loan.loan_nft_policy, loan.loan_nft_name, 1)

  let tx = Transaction.Mock(
    inputs: [
      MockInput(
        output_reference: OutputReference.Nil,
        output: MockOutput(
          address: borrower,
          value: nft_asset
        )
      )
    ],
    outputs: [
      MockOutput(
        address: lender,
        value: assets.lovelace(base)
      ),
      MockOutput(
        address: loan_utils.protocol_address(),
        value: assets.lovelace(fee)
      )
    ],
    validity_range: MockValidityRange(
      start: loan.due_time
    ),
    signatories: []
  )

  let redeemer = RepaymentRedeemer { borrower: borrower }
  let result = repayment.spend(Some(loan), redeemer, OutputReference.Nil, tx)
  
  result == True
}

test test_basic_calculations() {
  let days1 = loan_utils.days_late(100, 150)
  let days2 = loan_utils.days_late(200, 150)
  let days3 = loan_utils.days_late(150 + 86400, 150)
  
  let base = loan_utils.base_interest(100, 10, 100)
  let late = loan_utils.late_interest(100, 2, 1, 100)
  let fee = loan_utils.protocol_fee(100, 2, 100)
  
  days1 == 0 && days2 == 0 && days3 == 1 && base == 110 && late == 102 && fee == 2
}