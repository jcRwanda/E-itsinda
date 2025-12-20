import { useState } from "react";
import { initLucidWithWallet, LoanService } from "../services/loanService";
import LoanCard from "../components/LoanCard";
import type { Loan } from "../types/loan";

export default function Loans() {
  const [loans, setLoans] = useState<Loan[]>([
    { id: "1", borrower: "Alice", loanAmountRwf: 50000, interestPerPeriodRwf: 5000, paymentPeriodDays: 7, status: "Requested" },
    { id: "2", borrower: "Bob", lender: "Alice", loanAmountRwf: 30000, interestPerPeriodRwf: 3000, paymentPeriodDays: 7, status: "Active" }
  ]);

  const refresh = () => setLoans([...loans]);

  const handleAccept = async (id: string) => {
    try {
      const lucid = await initLucidWithWallet();
      const service = new LoanService(lucid);
      const txHash = await service.acceptLoan(id);
      alert(`Loan accepted! TxHash: ${txHash}`);
      refresh();
    } catch (error) {
      alert(`Error: ${error}`);
    }
  };

  const handlePayInterest = async (id: string) => {
    try {
      const lucid = await initLucidWithWallet();
      const service = new LoanService(lucid);
      const txHash = await service.payInterest(id);
      alert(`Interest paid! TxHash: ${txHash}`);
      refresh();
    } catch (error) {
      alert(`Error: ${error}`);
    }
  };

  const handleRepay = async (id: string) => {
    try {
      const lucid = await initLucidWithWallet();
      const service = new LoanService(lucid);
      const txHash = await service.repayLoan(id);
      alert(`Loan repaid! TxHash: ${txHash}`);
      refresh();
    } catch (error) {
      alert(`Error: ${error}`);
    }
  };

  const handleClaimDefault = async (id: string) => {
    try {
      const lucid = await initLucidWithWallet();
      const service = new LoanService(lucid);
      const txHash = await service.claimDefault(id);
      alert(`Default claimed! TxHash: ${txHash}`);
      refresh();
    } catch (error) {
      alert(`Error: ${error}`);
    }
  };

  return (
    <div>
      <h2>All Loans</h2>
      {loans.map((loan: Loan) => (
        <LoanCard
          key={loan.id}
          loan={loan}
          onAccept={(id: string) => handleAccept(id)}
          onPayInterest={(id: string) => handlePayInterest(id)}
          onRepay={(id: string) => handleRepay(id)}
          onClaimDefault={(id: string) => handleClaimDefault(id)}
        />
      ))}
    </div>
  );
}
