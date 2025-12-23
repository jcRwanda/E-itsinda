import { Loader2 } from "lucide-react";

export default function FullPageLoader() {
  return (
    <div className="h-screen flex flex-col items-center justify-center text-center gap-6">
      <Loader2 className="h-20 w-20 text-indigo-600 animate-spin" />
      <p className="text-xl font-medium text-gray-700">
        Waiting for e-Tsinda repayment to load
      </p>
    </div>
  );
}
