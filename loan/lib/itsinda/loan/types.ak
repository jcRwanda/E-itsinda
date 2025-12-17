use aiken/crypto.{VerificationKeyHash}

pub type LoanDatum {
  borrower: VerificationKeyHash,
  principal: Int,
  interest: Int,
  total_repay: Int,
  paid: Int,
  due_date: Int,
  approved: Bool
}

pub type LoanRedeemer {
  Approve
  Repay { amount: Int }
}

