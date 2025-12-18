import { Lucid, Blockfrost, Data } from "lucid-cardano";
import { LoanDatum, LoanStatus } from "../datums/loanDatum";
import { LoanRedeemer } from "../datums/loanRedeemer";
import { CONTRACT_ADDRESS } from "../config/addresses";

export async function requestLoan(
  lucid: Lucid,
  borrower: string,
  loanAmount: number, // in RWF
  interestPerPeriod: number,
  paymentPeriod: number
) {
  // Build LoanDatum
  const datum: LoanDatum = {
    borrower,
    lender: null,
    loan_amount: loanAmount,
    interest_per_period: interestPerPeriod,
    payment_period: paymentPeriod,
    last_payment_date: null,
    status: LoanStatus.Requested,
  };

  // Convert datum to on-chain format
  const inlineDatum: Data = lucid.utils.toData(datum);

  // Build transaction
  const tx = await lucid.newTx()
    .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2_000_000 }) // minimal UTxO
    .complete();

  // Sign & submit
  const signedTx = await tx.sign().complete();
  const txHash = await signedTx.submit();

  console.log("Loan request submitted. TxHash:", txHash);
  return txHash;
}
