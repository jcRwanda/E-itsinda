use aiken/collection/list
use aiken/interval.{Finite, Interval, IntervalBound}
use cardano/assets.{ada_asset_name, ada_policy_id, quantity_of}
use cardano/transaction.{
  InlineDatum, Output, OutputReference, Transaction, find_input,
}
use itsinda/loan/types.{
  AcceptLoan, Active, CancelRequest, ClaimDefault, LoanDatum, LoanRedeemer,
  PayInterest, RepayLoan, Requested,
}

// Loan with recurring interest: Request -> Accept -> Pay Interest (recurring) -> Repay/Default
validator loan_validator {
  spend(
    datum_opt: Option<LoanDatum>,
    redeemer: LoanRedeemer,
    utxo_ref: OutputReference,
    self: Transaction,
  ) {
    expect Some(datum) = datum_opt
    expect Some(input) = find_input(self.inputs, utxo_ref)

    when redeemer is {
      // Lender accepts and funds loan (Requested -> Active)
      AcceptLoan -> {
        expect datum.status == Requested
        expect [continuing_output] =
          list.filter(
            self.outputs,
            fn(output: Output) { output.address == input.output.address },
          )
        expect continuing_datum: LoanDatum = when continuing_output.datum is {
          InlineDatum(d) -> d
          _ -> fail @"Expected inline datum"
        }
        expect Some(new_lender) = continuing_datum.lender

        let signed_by_lender = list.has(self.extra_signatories, new_lender)
        let status_is_active = continuing_datum.status == Active
        let terms_unchanged =
          and {
            continuing_datum.borrower == datum.borrower,
            continuing_datum.loan_amount == datum.loan_amount,
            continuing_datum.interest_per_period == datum.interest_per_period,
            continuing_datum.payment_period == datum.payment_period,
          }
        // Set initial last_payment_date when accepting loan
        let Interval { lower_bound: IntervalBound { bound_type, .. }, .. } =
          self.validity_range
        let payment_date_set =
          when bound_type is {
            Finite(t) -> continuing_datum.last_payment_date == t
            _ -> False
          }
        let loan_funded =
          quantity_of(
            continuing_output.value,
            ada_policy_id,
            ada_asset_name,
          ) >= quantity_of(input.output.value, ada_policy_id, ada_asset_name) + datum.loan_amount

        and {
          signed_by_lender,
          status_is_active,
          terms_unchanged,
          payment_date_set,
          loan_funded,
        }
      }

      // Borrower pays interest for current period
      PayInterest -> {
        expect datum.status == Active
        expect Some(_lender) = datum.lender

        expect [continuing_output] =
          list.filter(
            self.outputs,
            fn(output: Output) { output.address == input.output.address },
          )
        expect continuing_datum: LoanDatum = when continuing_output.datum is {
          InlineDatum(d) -> d
          _ -> fail @"Expected inline datum"
        }

        let signed_by_borrower =
          list.has(self.extra_signatories, datum.borrower)
        // Check payment is not overdue (within payment period)
        let Interval { upper_bound: IntervalBound { bound_type, .. }, .. } =
          self.validity_range
        let payment_on_time =
          when bound_type is {
            Finite(t) -> t <= datum.last_payment_date + datum.payment_period
            _ -> False
          }
        // Update last_payment_date in continuing datum
        let Interval {
          lower_bound: IntervalBound { bound_type: lower_type, .. },
          ..
        } = self.validity_range
        let payment_date_updated =
          when lower_type is {
            Finite(t) -> continuing_datum.last_payment_date >= t
            _ -> False
          }
        // All other terms stay the same
        let terms_unchanged =
          and {
            continuing_datum.borrower == datum.borrower,
            continuing_datum.lender == datum.lender,
            continuing_datum.loan_amount == datum.loan_amount,
            continuing_datum.interest_per_period == datum.interest_per_period,
            continuing_datum.payment_period == datum.payment_period,
            continuing_datum.status == Active,
          }

        and {
          signed_by_borrower,
          payment_on_time,
          payment_date_updated,
          terms_unchanged,
        }
      }

      // Borrower repays full loan amount
      RepayLoan -> {
        expect datum.status == Active
        expect Some(_lender) = datum.lender

        list.has(self.extra_signatories, datum.borrower)
      }

      // Lender claims if interest payment is overdue
      ClaimDefault -> {
        expect datum.status == Active
        expect Some(lender) = datum.lender

        let signed_by_lender = list.has(self.extra_signatories, lender)
        // Check that payment period has passed without payment
        let Interval { lower_bound: IntervalBound { bound_type, .. }, .. } =
          self.validity_range
        let payment_overdue =
          when bound_type is {
            Finite(t) -> t > datum.last_payment_date + datum.payment_period
            _ -> False
          }

        signed_by_lender && payment_overdue
      }

      // Borrower cancels their request
      CancelRequest -> {
        expect datum.status == Requested
        list.has(self.extra_signatories, datum.borrower)
      }
    }
  }
}


