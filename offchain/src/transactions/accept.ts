import { Lucid, Data } from "lucid-cardano";
import { LoanDatum, LoanStatus } from "../datums/loanDatum";
import { LoanRedeemer } from "../datums/loanRedeemer";
import { CONTRACT_ADDRESS } from "../config/addresses";

export async function acceptLoan(
  lucid: Lucid,
  lender: string,
  loanDatum: LoanDatum,
  fundingAmount: number // in RWF
) {
  // Update datum to Active
  const updatedDatum: LoanDatum = {
    ...loanDatum,
    lender,
    last_payment_date: Date.now(),
    status: LoanStatus.Active,
  };

  const inlineDatum: Data = lucid.utils.toData(updatedDatum);

  const tx = await lucid.newTx()
    .collectFromContract(CONTRACT_ADDRESS, loanDatum) // previous UTxO
    .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2_000_000 + fundingAmount })
    .complete();

  const signedTx = await tx.sign().complete();
  const txHash = await signedTx.submit();

  console.log("Loan accepted. TxHash:", txHash);
  return txHash;
}
