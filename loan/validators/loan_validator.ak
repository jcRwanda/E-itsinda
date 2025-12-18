use aiken/builtin
use itsinda/loan/types.{LoanDatum, LoanRedeemer}

validator loan_validator(datum: LoanDatum, redeemer: LoanRedeemer, ctx: ScriptContext) {
  when redeemer is {
    Repay { .. } -> tx_signed_by(ctx, datum.borrower) && upper_bound(tx_valid_range(ctx)) <= datum.due_date
    _ -> tx_signed_by(ctx, datum.lender) && lower_bound(tx_valid_range(ctx)) > datum.due_date
  }
}
