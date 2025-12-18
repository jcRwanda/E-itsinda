import { useState } from "react";
import { getLoans, acceptLoan, payInterest, repayLoan, claimDefault } from "../services/loanService";
import LoanCard from "../components/LoanCard";
import type { Loan } from "../types/loan";

export default function Loans() {
  const [loans, setLoans] = useState(getLoans());

  const refresh = () => setLoans([...getLoans()]);

  return (
    <div>
      <h2>All Loans</h2>
      {loans.map((loan: Loan) => (
        <LoanCard
          key={loan.id}
          loan={loan}
          onAccept={(id: string) => { acceptLoan(id, "LenderWallet"); refresh(); }}
          onPayInterest={(id: string) => { payInterest(id); refresh(); }}
          onRepay={(id: string) => { repayLoan(id); refresh(); }}
          onClaimDefault={(id: string) => { claimDefault(id); refresh(); }}
        />
      ))}
    </div>
  );
}
