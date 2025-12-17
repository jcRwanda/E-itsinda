use aiken/builtin
use itsinda/loan/types.{LoanDatum, LoanRedeemer}

validator loan_validator(
  datum: LoanDatum,
  redeemer: LoanRedeemer,
  ctx: ScriptContext
) {
    if redeemer == Repay {
        expect tx_signed_by(ctx, datum.borrower)
        expect upper_bound(tx_valid_range(ctx)) <= datum.deadline
    } else {
        expect tx_signed_by(ctx, datum.lender)
        expect lower_bound(tx_valid_range(ctx)) > datum.deadline
    }
}
