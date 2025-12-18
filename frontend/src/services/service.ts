import type { Loan } from "../types/loan";

let loans: Loan[] = [];

export function requestLoan(loan: Loan) {
  loans.push({ ...loan, status: "Requested" });
}

export function getLoans() {
  return loans;
}

export function acceptLoan(id: string, lender: string) {
  loans = loans.map(l =>
    l.id === id
      ? { ...l, lender, status: "Active", lastPaymentDate: Date.now() }
      : l
  );
}

export function payInterest(id: string) {
  loans = loans.map(l =>
    l.id === id
      ? { ...l, lastPaymentDate: Date.now() }
      : l
  );
}

export function repayLoan(id: string) {
  loans = loans.map(l =>
    l.id === id
      ? { ...l, status: "Repaid" }
      : l
  );
}

export function claimDefault(id: string) {
  loans = loans.map(l =>
    l.id === id
      ? { ...l, status: "Defaulted" }
      : l
  );
}
