import { useState } from "react";
import { initLucidWithWallet } from "../services/loanService";

export default function WalletConnect() {
  const [connected, setConnected] = useState(false);
  const [address, setAddress] = useState("");

  const connectWallet = async () => {
    try {
      const lucid = await initLucidWithWallet();
      const addr = await lucid.wallet.address();
      setAddress(addr);
      setConnected(true);
    } catch (error) {
      alert(`Failed to connect wallet: ${error}`);
    }
  };

  return (
    <div className="p-4">
      {!connected ? (
        <button onClick={connectWallet} className="bg-green-500 text-white px-4 py-2 rounded">
          Connect Nami Wallet
        </button>
      ) : (
        <div>
          <p>Connected: {address.slice(0, 20)}...</p>
        </div>
      )}
    </div>
  );
}