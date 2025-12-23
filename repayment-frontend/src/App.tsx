import { Suspense, lazy } from "react";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import FullPageLoader from "./components/FullPageLoader";

const RepayPage = lazy(() => import("./pages/RepayPage/RePay"));
const GroupLedger = lazy(() => import("./pages/GroupLedger/GroupLedger"));
const NotFound = lazy(() => import("./pages/NotFound/NotFound"));

function App() {
  return (
    <BrowserRouter>
      <Suspense fallback={<FullPageLoader />}>
        <Routes>
          {/* Main route */}
          <Route path="/repay" element={<RepayPage />} />
          
          {/* Group ledger route */}
          <Route path="/group/:groupId/ledger" element={<GroupLedger />} />
          
          {/* Redirect root path to /repay */}
          <Route path="/" element={<Navigate to="/repay" replace />} />
          
          {/* Catch-all 404 route - will auto-redirect to /repay */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </Suspense>
    </BrowserRouter>
  );
}

export default App;