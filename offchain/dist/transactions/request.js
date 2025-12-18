import { LoanStatus } from "../datums/loanDatum";
import { CONTRACT_ADDRESS } from "../config/addresses";
export async function requestLoan(lucid, borrower, loanAmount, // in RWF
interestPerPeriod, paymentPeriod) {
    // Build LoanDatum
    const datum = {
        borrower,
        lender: null,
        loan_amount: loanAmount,
        interest_per_period: interestPerPeriod,
        payment_period: paymentPeriod,
        last_payment_date: null,
        status: LoanStatus.Requested,
    };
    // Convert datum to on-chain format
    const inlineDatum = lucid.utils.toData(datum);
    // Build transaction
    const tx = await lucid.newTx()
        .payToContract(CONTRACT_ADDRESS, { inline: inlineDatum }, { lovelace: 2000000 }) // minimal UTxO
        .complete();
    // Sign & submit
    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();
    console.log("Loan request submitted. TxHash:", txHash);
    return txHash;
}
