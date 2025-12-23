use validators/repayment.ak
use loan_utils

pub fn run_simple_test() {
  let borrower = Address.Test(
    Credential.PubKeyKeyHash(ByteArray.from_hex("00")),
    0
  )
  
  let lender = Address.Test(
    Credential.PubKeyKeyHash(ByteArray.from_hex("01")),
    0
  )
  
  let loan = LoanDatum {
    loan_id: ByteArray.from_hex("74657374"),
    principal: 100_000_000,
    interest_rate_n: 10,
    interest_rate_d: 100,
    start_time: 0,
    due_time: 100,
    borrower: borrower,
    lender: lender,
    late_rate_n: 1,
    late_rate_d: 100,
    protocol_fee_n: 2,
    protocol_fee_d: 100,
    loan_nft_policy: ByteArray.from_hex("02"),
    loan_nft_name: ByteArray.from_hex("03")
  }
  
  // Calculate amounts
  let base = loan_utils.base_interest(loan.principal, loan.interest_rate_n, loan.interest_rate_d)
  let fee = loan_utils.protocol_fee(base, loan.protocol_fee_n, loan.protocol_fee_d)
  
  trace @"Principal": loan.principal
  trace @"Base amount (with interest)": base
  trace @"Protocol fee": fee
  trace @"Total due": base + fee
  
  // Create a simple transaction
  let tx = transaction.Mock(
    inputs: [
      transaction.MockInput(
        output_reference: transaction.OutputReference.Nil,
        output: transaction.MockOutput(
          address: borrower,
          value: loan_utils.nft_asset(loan.loan_nft_policy, loan.loan_nft_name)
        )
      )
    ],
    outputs: [
      transaction.MockOutput(
        address: lender,
        value: assets.lovelace(base)
      ),
      transaction.MockOutput(
        address: loan_utils.protocol_address(),
        value: assets.lovelace(fee)
      )
    ],
    validity_range: transaction.MockValidityRange(start: 50),
    signatories: []
  )
  
  let redeemer = RepaymentRedeemer { borrower: borrower }
  
  let result = repayment.spend(Some(loan), redeemer, transaction.OutputReference.Nil, tx)
  
  trace @"Test result": result
  result
}

// Run the test
run_simple_test()