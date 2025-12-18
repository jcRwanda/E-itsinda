import { LoanStatus } from "../datums/loanDatum";
import { CONTRACT_ADDRESS } from "../config/addresses";
export async function claimDefault(lucid, lender, loanDatum) {
    if (loanDatum.status !== LoanStatus.Active) {
        throw new Error("Loan is not active");
    }
    // Update status to Defaulted
    const updatedDatum = {
        ...loanDatum,
        status: LoanStatus.Defaulted,
    };
    const inlineDatum = lucid.utils.toData(updatedDatum);
    const tx = await lucid.newTx()
        .collectFromContract(CONTRACT_ADDRESS, loanDatum)
        .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2000000 })
        .complete();
    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();
    console.log("Default claimed. TxHash:", txHash);
    return txHash;
}
