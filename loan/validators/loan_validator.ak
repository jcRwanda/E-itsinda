use aiken/builtin
use itsinda/loan/types.{LoanDatum, LoanRedeemer}

validator loan_validator(
  datum: LoanDatum,
  redeemer: LoanRedeemer,
  ctx: ScriptContext
) {
    -- Borrower repayment
    if redeemer is Repay then
        tx_signed_by(ctx, datum.borrower),
        upper_bound(tx_valid_range(ctx)) <= datum.deadline

    -- Lender liquidation
    else
        tx_signed_by(ctx, datum.lender),
        lower_bound(tx_valid_range(ctx)) > datum.deadline
}
