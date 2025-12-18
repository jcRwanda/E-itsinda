export type LoanStatus = "Requested" | "Active" | "Repaid" | "Defaulted";

export interface Loan {
  id: string;
  borrower: string;
  lender?: string;

  // Money in Rwandan Franc (RWF)
  loanAmountRwf: number;
  interestPerPeriodRwf: number;

  // Time (in days)
  paymentPeriodDays: number;
  lastPaymentDate?: number;

  status: LoanStatus;
}
