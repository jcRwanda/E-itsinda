import type { Loan } from "../types/loan";

interface Props {
  loan: Loan;
  onAccept?: (id: string) => void;
  onPayInterest?: (id: string) => void;
  onRepay?: (id: string) => void;
  onClaimDefault?: (id: string) => void;
}

export default function LoanCard({
  loan,
  onAccept,
  onPayInterest,
  onRepay,
  onClaimDefault,
}: Props) {
  return (
    <div style={{ border: "1px solid gray", padding: "1rem", margin: "1rem" }}>
      <p><b>Borrower:</b> {loan.borrower}</p>
      <p><b>Lender:</b> {loan.lender || "Not assigned"}</p>
      <p><b>Amount:</b> {loan.loanAmountRwf} RWF</p>
      <p><b>Interest / Period:</b> {loan.interestPerPeriodRwf} RWF</p>
      <p><b>Payment Period:</b> {loan.paymentPeriodDays} days</p>
      <p><b>Status:</b> {loan.status}</p>

      {loan.status === "Requested" && onAccept && (
        <button onClick={() => onAccept(loan.id)}>Accept Loan</button>
      )}
      {loan.status === "Active" && onPayInterest && (
        <button onClick={() => onPayInterest(loan.id)}>Pay Interest</button>
      )}
      {loan.status === "Active" && onRepay && (
        <button onClick={() => onRepay(loan.id)}>Repay Loan</button>
      )}
      {loan.status === "Active" && onClaimDefault && (
        <button onClick={() => onClaimDefault(loan.id)}>Claim Default</button>
      )}
    </div>
  );
}
