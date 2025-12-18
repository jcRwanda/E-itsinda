use aiken/crypto.{VerificationKeyHash}
use cardano/assets.{Lovelace}

pub type LoanStatus {
  Requested  // Waiting for lender to accept
  Active  // Loan funded and active
}

pub type LoanDatum {
  borrower: VerificationKeyHash,
  lender: Option<VerificationKeyHash>,  // None when Requested
  loan_amount: Lovelace,
  interest_per_period: Lovelace,  // Interest amount to pay each period
  payment_period: Int,  // Period in milliseconds (e.g., 4 weeks)
  last_payment_date: Int,  // Unix timestamp (ms) of last interest payment
  status: LoanStatus,
}

pub type LoanRedeemer {
  AcceptLoan  // Lender funds the loan (Requested -> Active)
  PayInterest  // Borrower pays interest for current period
  RepayLoan  // Borrower repays full loan amount
  ClaimDefault  // Lender claims if interest payment overdue
  CancelRequest  // Borrower cancels request
}
