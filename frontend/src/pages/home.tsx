import React, { useState } from "react";
import LoanCard from "../components/card";
import type { Loan } from "../types/loan";

const Home: React.FC = () => {
  const [loans, setLoans] = useState<Loan[]>([
    { id: "1", borrower: "Alice", loanAmountRwf: 50000, interestPerPeriodRwf: 5000, paymentPeriodDays: 7, status: "Requested" },
    { id: "2", borrower: "Bob", lender: "Alice", loanAmountRwf: 30000, interestPerPeriodRwf: 3000, paymentPeriodDays: 7, status: "Active" }
  ]);

  const handleAccept = (id: string) => {
    setLoans(loans.map(l => l.id === id ? { ...l, lender: "You", status: "Active" } : l));
  };

  const handlePayInterest = (id: string) => {
    alert(`Interest paid for loan ${id}`);
  };

  const handleRepay = (id: string) => {
    setLoans(loans.map(l => l.id === id ? { ...l, status: "Repaid" } : l));
  };

  const handleClaimDefault = (id: string) => {
    setLoans(loans.map(l => l.id === id ? { ...l, status: "Defaulted" } : l));
  };

  return (
    <main>
      {loans.map(loan => (
        <LoanCard
          key={loan.id}
          loan={loan}
          onAccept={handleAccept}
          onPayInterest={handlePayInterest}
          onRepay={handleRepay}
          onClaimDefault={handleClaimDefault}
        />
      ))}
    </main>
  );
};

export default Home;
