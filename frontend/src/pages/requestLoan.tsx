import { useState } from "react";

interface Props {
  onCreate: (borrower: string, amount: number, interest: number, period: number) => void;
}

export default function RequestLoan({ onCreate }: Props) {
  const [borrower, setBorrower] = useState("");
  const [amount, setAmount] = useState(0);
  const [interest, setInterest] = useState(5);
  const [period, setPeriod] = useState(30); // days

  const handleRequest = () => {
    onCreate(borrower, amount, interest, period);
  };

  return (
    <div className="p-6 max-w-md mx-auto">
      <h2 className="text-xl font-bold mb-4">Request a Loan</h2>
      <input
        type="text"
        placeholder="Borrower Name"
        value={borrower}
        onChange={(e) => setBorrower(e.target.value)}
        className="border p-2 mb-2 w-full"
      />
      <input
        type="number"
        placeholder="Loan Amount (RWF)"
        value={amount}
        onChange={(e) => setAmount(Number(e.target.value))}
        className="border p-2 mb-2 w-full"
      />
      <input
        type="number"
        placeholder="Interest per period (RWF)"
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
        className="bg-blue-500 text-white px-4 py-2 rounded"
      >
        Request Loan
      </button>
    </div>
  );
}
