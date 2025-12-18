import { Lucid } from "lucid-cardano";
import { LoanDatum, LoanStatus } from "../datums/loanDatum";
import { CONTRACT_ADDRESS } from "../config/addresses";

export async function claimDefault(
  lucid: Lucid,
  lender: string,
  loanDatum: LoanDatum
) {
  if (loanDatum.status !== LoanStatus.Active) {
    throw new Error("Loan is not active");
  }

  // Update status to Defaulted
  const updatedDatum: LoanDatum = {
    ...loanDatum,
    status: LoanStatus.Defaulted,
  };

  const inlineDatum = lucid.utils.toData(updatedDatum);

  const tx = await lucid.newTx()
    .collectFromContract(CONTRACT_ADDRESS, loanDatum)
    .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2_000_000 })
    .complete();

  const signedTx = await tx.sign().complete();
  const txHash = await signedTx.submit();

  console.log("Default claimed. TxHash:", txHash);
  return txHash;
}
