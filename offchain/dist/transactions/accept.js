import { LoanStatus } from "../datums/loanDatum";
import { CONTRACT_ADDRESS } from "../config/addresses";
export async function acceptLoan(lucid, lender, loanDatum, fundingAmount // in RWF
) {
    // Update datum to Active
    const updatedDatum = {
        ...loanDatum,
        lender,
        last_payment_date: Date.now(),
        status: LoanStatus.Active,
    };
    const inlineDatum = lucid.utils.toData(updatedDatum);
    const tx = await lucid.newTx()
        .collectFromContract(CONTRACT_ADDRESS, loanDatum) // previous UTxO
        .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2000000 + fundingAmount })
        .complete();
    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();
    console.log("Loan accepted. TxHash:", txHash);
    return txHash;
}
