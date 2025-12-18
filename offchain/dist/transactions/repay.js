import { LoanStatus } from "../datums/loanDatum";
import { CONTRACT_ADDRESS } from "../config/addresses";
export async function payInterest(lucid, borrower, loanDatum, interestAmount) {
    if (loanDatum.status !== LoanStatus.Active) {
        throw new Error("Loan is not active");
    }
    // Update last payment date
    const updatedDatum = {
        ...loanDatum,
        last_payment_date: Date.now(),
    };
    const inlineDatum = lucid.utils.toData(updatedDatum);
    const tx = await lucid.newTx()
        .collectFromContract(CONTRACT_ADDRESS, loanDatum)
        .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2000000 + interestAmount })
        .complete();
    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();
    console.log("Interest paid. TxHash:", txHash);
    return txHash;
}
