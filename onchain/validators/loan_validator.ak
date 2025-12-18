use aiken/collection/list
use aiken/interval.{Finite, Interval, IntervalBound}

use contract/types/loan_datum.{LoanDatum, LoanStatus}
use contract/types/loan_redeemer.{LoanRedeemer, AcceptLoan, PayInterest, RepayLoan, ClaimDefault, CancelRequest, Requested, Active}
use contract/utils/time.{now_slot}
use contract/utils/checks.{check_signature, check_continuing_output}

// Main Loan Validator
validator loan_validator {
  spend(
    datum_opt: Option<LoanDatum>,
    redeemer: LoanRedeemer,
    utxo_ref: OutputReference,
    self: Transaction
  ) {

    // Ensure datum exists
    expect Some(datum) = datum_opt
    expect Some(input) = find_input(self.inputs, utxo_ref)

    when redeemer is {

      // ------------------------------
      // Lender accepts and funds loan
      // ------------------------------
      AcceptLoan -> {
        expect datum.status == Requested

        let continuing_output =
          check_continuing_output(self.outputs, input.output.address)

        let continuing_datum: LoanDatum =
          when continuing_output.datum is {
            InlineDatum(d) -> d
            _ -> fail @"Expected inline datum"
          }

        expect Some(lender) = continuing_datum.lender

        let signed_by_lender = check_signature(self.extra_signatories, lender)
        let status_is_active = continuing_datum.status == Active

        let terms_unchanged =
          and {
            continuing_datum.borrower == datum.borrower,
            continuing_datum.loan_amount == datum.loan_amount,
            continuing_datum.interest_per_period == datum.interest_per_period,
            continuing_datum.payment_period == datum.payment_period
          }

        signed_by_lender && status_is_active && terms_unchanged
      }

      // ------------------------------
      // Borrower pays interest
      // ------------------------------
      PayInterest -> {
        expect datum.status == Active
        expect Some(_lender) = datum.lender

        let continuing_output =
          check_continuing_output(self.outputs, input.output.address)

        let continuing_datum: LoanDatum =
          when continuing_output.datum is {
            InlineDatum(d) -> d
            _ -> fail @"Expected inline datum"
          }

        let signed_by_borrower = check_signature(self.extra_signatories, datum.borrower)

        // Payment must be within the payment period
        let Interval { upper_bound: IntervalBound { bound_type, .. }, .. } = self.validity_range
        let payment_on_time =
          when bound_type is {
            Finite(t) -> t <= datum.last_payment_date.unwrap_or(0) + datum.payment_period
            _ -> False
          }

        signed_by_borrower && payment_on_time
      }

      // ------------------------------
      // Borrower repays full loan
      // ------------------------------
      RepayLoan -> {
        expect datum.status == Active
        check_signature(self.extra_signatories, datum.borrower)
      }

      // ------------------------------
      // Lender claims default
      // ------------------------------
      ClaimDefault -> {
        expect datum.status == Active
        expect Some(lender) = datum.lender

        let signed_by_lender = check_signature(self.extra_signatories, lender)

        let Interval { lower_bound: IntervalBound { bound_type, .. }, .. } = self.validity_range
        let payment_overdue =
          when bound_type is {
            Finite(t) -> t > datum.last_payment_date.unwrap_or(0) + datum.payment_period
            _ -> False
          }

        signed_by_lender && payment_overdue
      }

      // ------------------------------
      // Borrower cancels loan request
      // ------------------------------
      CancelRequest -> {
        expect datum.status == Requested
        check_signature(self.extra_signatories, datum.borrower)
      }
    }
  }
}
