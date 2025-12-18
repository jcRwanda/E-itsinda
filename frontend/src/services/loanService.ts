import { Lucid } from "lucid-cardano";
// import { requestLoan } from "../../offchain/src/transactions/requestLoan";
// import { acceptLoan } from "../../offchain/src/transactions/acceptLoan";
// import { payInterest } from "../../offchain/src/transactions/payInterest";
// import { repayLoan } from "../../offchain/src/transactions/repayLoan";
// import { claimDefault } from "../../offchain/src/transactions/claimDefault";
// import { LoanDatum } from "../../offchain/src/datums/loanDatum";
import type { Loan } from "../types/loan";

export function getLoans(): Loan[] {
  // Mock data for now
  return [
    { id: "1", borrower: "Alice", loanAmountRwf: 50000, interestPerPeriodRwf: 5000, paymentPeriodDays: 7, status: "Requested" },
    { id: "2", borrower: "Bob", lender: "Alice", loanAmountRwf: 30000, interestPerPeriodRwf: 3000, paymentPeriodDays: 7, status: "Active" }
  ];
}

export async function requestLoan(borrower: string, amount: number, interest: number, period: number) {
  // TODO: implement
  console.log("Requesting loan", borrower, amount, interest, period);
}

export async function acceptLoan(id: string, lender: string) {
  // TODO: implement
  console.log("Accepting loan", id, lender);
}

export async function payInterest(id: string) {
  // TODO: implement
  console.log("Paying interest", id);
}

export async function repayLoan(id: string) {
  // TODO: implement
  console.log("Repaying loan", id);
}

export async function claimDefault(id: string) {
  // TODO: implement
  console.log("Claiming default", id);
}

export class LoanService {
  lucid: Lucid;

  constructor(lucid: Lucid) {
    this.lucid = lucid;
  }

  async createLoan(
    borrower: string,
    amount: number,
    interest: number,
    period: number
  ) {
    return await requestLoan(borrower, amount, interest, period);
  }

  async fundLoan(_lender: string, _datum: any, _amount: number) {
    return await acceptLoan("id", "lender");
  }

  async makeInterestPayment(_borrower: string, _datum: any, _interest: number) {
    return await payInterest("id");
  }

  async repayFullLoan(_borrower: string, _datum: any, _repayment: number) {
    return await repayLoan("id");
  }

  async claimDefaultedLoan(_lender: string, _datum: any) {
    return await claimDefault("id");
  }
}
