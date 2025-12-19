use json "./mock_loan_datum.json"
use "../validators/repayment.ak"
use cardano/transaction.{Transaction, OutputReference}

pub fn simulate_loan_repayment() {

  let loans = json.as_list()

  list.map(fn(loan) {

    trace @"================================"
    trace @"Simulating loan": loan.loan_id

    let tx = Transaction.Mock(
      signatories=[loan.borrower],
      outputs=[
        Transaction.MockOutput(
          address=loan.lender,
          value=loan.principal * 110 / 100
        ),
        Transaction.MockOutput(
          address=loan_utils.protocol_address(),
          value=loan.principal * 2 / 100
        )
      ],
      validity_range=Transaction.MockValidityRange(
        start=loan.due_time + 172800
      )
    )

    let result =
      repayment.spend(
        Some(loan),
        Data.Nil,
        OutputReference.Nil,
        tx
      )

    trace @"Repayment result": result
  }, loans)
}

simulate_loan_repayment()
