import { Lucid, Data } from "lucid-cardano";
import { LoanDatum, LoanStatus } from "../datums/loanDatum";
import { LoanRedeemer } from "../datums/loanRedeemer";
import { CONTRACT_ADDRESS } from "../config/addresses";

export async function payInterest(
  lucid: Lucid,
  borrower: string,
  loanDatum: LoanDatum,
  interestAmount: number
) {
  if (loanDatum.status !== LoanStatus.Active) {
    throw new Error("Loan is not active");
  }

  // Update last payment date
  const updatedDatum: LoanDatum = {
    ...loanDatum,
    last_payment_date: Date.now(),
  };

  const inlineDatum: Data = lucid.utils.toData(updatedDatum);

  const tx = await lucid.newTx()
    .collectFromContract(CONTRACT_ADDRESS, loanDatum)
    .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2_000_000 + interestAmount })
    .complete();

  const signedTx = await tx.sign().complete();
  const txHash = await signedTx.submit();

  console.log("Interest paid. TxHash:", txHash);
  return txHash;
}
