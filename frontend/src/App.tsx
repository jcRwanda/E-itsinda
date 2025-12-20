import React, { useState } from "react";
import Header from "./components/Header";
import Footer from "./components/Footer.tsx";
import Home from "./pages/home";
import RequestLoan from "./pages/requestLoan";

const App: React.FC = () => {
  const [page, setPage] = useState<"home" | "request">("home");

  return (
    <>
      <Header />
      <nav style={{ textAlign: "center", margin: "1rem 0" }}>
        <button onClick={() => setPage("home")} style={{ marginRight: "1rem" }}>Home</button>
        <button onClick={() => setPage("request")}>Request Loan</button>
      </nav>

      {page === "home" && <Home />}
      {page === "request" && <RequestLoan />}
      <Footer />
    </>
  );
};

export default App;
