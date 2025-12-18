import React, { useState } from "react";
import Header from "./components/Header";
import Footer from "./components/Footer.tsx";
import Home from "./pages/home";
import RequestLoan from "./pages/requestLoan";
import type { Loan } from "./types/loan";

const App: React.FC = () => {
  const [loans, setLoans] = useState<Loan[]>([]);
  const [page, setPage] = useState<"home" | "request">("home");

  const handleCreateLoan = (borrower: string, amount: number, interest: number, period: number) => {
    const newLoan: Loan = {
      id: (loans.length + 1).toString(),
      borrower,
      loanAmountRwf: amount,
      interestPerPeriodRwf: interest,
      paymentPeriodDays: period,
      status: "Requested"
    };
    setLoans([...loans, newLoan]);
    setPage("home");
  };

  return (
    <>
      <Header />
      <nav style={{ textAlign: "center", margin: "1rem 0" }}>
        <button onClick={() => setPage("home")} style={{ marginRight: "1rem" }}>Home</button>
        <button onClick={() => setPage("request")}>Request Loan</button>
      </nav>

      {page === "home" && <Home />}
      {page === "request" && <RequestLoan onCreate={handleCreateLoan} />}
      <Footer />
    </>
  );
};

export default App;
