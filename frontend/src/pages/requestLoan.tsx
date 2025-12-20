import { useState } from "react";
import { initLucidWithWallet, LoanService } from "../services/loanService";

export default function RequestLoan() {
  const [amount, setAmount] = useState(0);
  const [interest, setInterest] = useState(5);
  const [period, setPeriod] = useState(30); // days
  const [loading, setLoading] = useState(false);

  const handleRequest = async () => {
    setLoading(true);
    try {
      const lucid = await initLucidWithWallet();
      const service = new LoanService(lucid);
      const txHash = await service.requestLoan(amount, interest, period);
      alert(`Loan requested! TxHash: ${txHash}`);
    } catch (error) {
      alert(`Error: ${error}`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="p-6 max-w-md mx-auto">
      <h2 className="text-xl font-bold mb-4">Request a Loan</h2>
      <input
        type="number"
        placeholder="Loan Amount (ADA)"
        value={amount}
        onChange={(e) => setAmount(Number(e.target.value))}
        className="border p-2 mb-2 w-full"
      />
      <input
        type="number"
        placeholder="Interest per period (ADA)"
        value={interest}
        onChange={(e) => setInterest(Number(e.target.value))}
        className="border p-2 mb-2 w-full"
      />
      <input
        type="number"
        placeholder="Payment period (days)"
        value={period}
        onChange={(e) => setPeriod(Number(e.target.value))}
        className="border p-2 mb-4 w-full"
      />
      <button
        onClick={handleRequest}
        disabled={loading}
        className="bg-blue-500 text-white px-4 py-2 rounded"
      >
        {loading ? "Requesting..." : "Request Loan"}
      </button>
    </div>
  );
}
