use aiken/builtin
use aiken/crypto.{VerificationKeyHash}
use cardano/assets.{Lovelace}
use itsinda/loan_fixed/types.{LoanDatum, LoanRedeemer}

validator loan_validator {
  spend(datum_opt: Option<LoanDatum>, redeemer: LoanRedeemer, utxo: OutputReference, ctx: ScriptContext) {
    expect Some(datum) = datum_opt
    when redeemer is {
      AcceptLoan -> True  // For now, always allow accept
      PayInterest -> tx_signed_by(ctx, datum.borrower) && (ctx.transaction.validity.start.validity_start >= datum.last_payment_date + datum.payment_period)
      RepayLoan -> tx_signed_by(ctx, datum.borrower)
      ClaimDefault -> tx_signed_by(ctx, datum.lender) && (ctx.transaction.validity.start.validity_start > datum.last_payment_date + datum.payment_period)
      CancelRequest -> tx_signed_by(ctx, datum.borrower) && datum.status == Requested
    }
  }
}
