import { Lucid, Blockfrost, Data, Constr } from "lucid-cardano";
import blueprint from "../../../onchain/plutus.json";

export async function initLucidWithWallet(): Promise<Lucid> {
  const lucid = await Lucid.new(
    new Blockfrost("https://cardano-preprod.blockfrost.io/api/v0", import.meta.env.VITE_BLOCKFROST_API_KEY),
    "Preprod"
  );

  // Enable wallet (e.g., Nami)
  const api = await (window as any).cardano.nami.enable();
  lucid.selectWallet(api);

  return lucid;
}

export class LoanService {
  lucid: Lucid;
  validator: any;
  validatorAddress: string;

  constructor(lucid: Lucid) {
    this.lucid = lucid;
    // Use validator hash for contract address
    this.validatorAddress = this.lucid.utils.validatorToAddress({
      type: "PlutusV2",
      script: blueprint.validators[0].compiledCode
    });
  }

  async requestLoan(amount: number, interest: number, period: number): Promise<string> {
    const borrowerAddr = await this.lucid.wallet.address();
    const borrowerPkh = this.lucid.utils.getAddressDetails(borrowerAddr).paymentCredential?.hash;

    if (!borrowerPkh) throw new Error("Could not get borrower payment credential");

    // Create datum according to plutus.json schema
    const datum = Data.to(new Constr(0, [
      borrowerPkh,  // borrower: VerificationKeyHash
      new Constr(1, []),  // lender: None (Option constructor index 1)
      BigInt(amount * 1000000),  // loan_amount: Lovelace
      BigInt(interest * 1000000), // interest_per_period: Lovelace
      BigInt(period * 24 * 60 * 60 * 1000), // payment_period: Int (ms)
      BigInt(Date.now()), // last_payment_date: Int
      new Constr(0, []) // status: Requested (LoanStatus constructor index 0)
    ]));

    const tx = await this.lucid
      .newTx()
      .payToContract(this.validatorAddress, datum, { lovelace: 2000000n })
      .complete();

    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();

    console.log("Loan requested:", txHash);
    return txHash;
  }

  async acceptLoan(utxoRef: string): Promise<string> {
    const lenderAddr = await this.lucid.wallet.address();
    const lenderPkh = this.lucid.utils.getAddressDetails(lenderAddr).paymentCredential?.hash;

    if (!lenderPkh) throw new Error("Could not get lender payment credential");

    const utxos = await this.lucid.utxosAt(this.validatorAddress);
    const utxo = utxos.find(u => u.txHash === utxoRef.split("#")[0] && u.outputIndex === parseInt(utxoRef.split("#")[1]));
    if (!utxo) throw new Error("UTxO not found");

    // Parse existing datum - utxo.datum is PlutusData
    const existingDatum = Data.from(utxo.datum!);
    const datumFields = (existingDatum as any).fields;
    const borrower = datumFields[0];
    const loanAmount = datumFields[2];
    const interestPerPeriod = datumFields[3];
    const paymentPeriod = datumFields[4];
    const lastPaymentDate = datumFields[5];

    // Create new datum with lender and Active status
    const newDatum = Data.to(new Constr(0, [
      borrower,     // borrower
      new Constr(0, [lenderPkh]),    // lender: Some(lenderPkh) - Option constructor index 0
      loanAmount,   // loan_amount
      interestPerPeriod, // interest_per_period
      paymentPeriod, // payment_period
      lastPaymentDate, // last_payment_date
      new Constr(1, []) // status: Active (LoanStatus constructor index 1)
    ]));

    const redeemer = Data.to(new Constr(0, [])); // AcceptLoan redeemer (0)

    const tx = await this.lucid
      .newTx()
      .collectFrom([utxo], redeemer)
      .payToContract(this.validatorAddress, newDatum, { ...utxo.assets, lovelace: utxo.assets.lovelace + loanAmount })
      .addSigner(lenderAddr)
      .complete();

    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();

    console.log("Loan accepted:", txHash);
    return txHash;
  }

  async payInterest(utxoRef: string): Promise<string> {
    const borrowerAddr = await this.lucid.wallet.address();

    const utxos = await this.lucid.utxosAt(this.validatorAddress);
    const utxo = utxos.find(u => u.txHash === utxoRef.split("#")[0] && u.outputIndex === parseInt(utxoRef.split("#")[1]));
    if (!utxo) throw new Error("UTxO not found");

    // Parse existing datum - utxo.datum is PlutusData
    const existingDatum = Data.from(utxo.datum!);
    const datumFields = (existingDatum as any).fields;
    const borrower = datumFields[0];
    const lenderOption = datumFields[1];
    const lender = (lenderOption as any).fields[0]; // Extract from Some constructor
    const loanAmount = datumFields[2];
    const interestPerPeriod = datumFields[3];
    const paymentPeriod = datumFields[4];

    // Create new datum with updated last_payment_date
    const newDatum = Data.to(new Constr(0, [
      borrower,
      lender,
      loanAmount,
      interestPerPeriod,
      paymentPeriod,
      BigInt(Date.now()), // updated last_payment_date
      new Constr(1, []) // status: Active
    ]));

    const redeemer = Data.to(new Constr(1, [])); // PayInterest redeemer (1)

    const tx = await this.lucid
      .newTx()
      .collectFrom([utxo], redeemer)
      .payToContract(this.validatorAddress, newDatum, utxo.assets)
      .payToAddress(lender, { lovelace: interestPerPeriod })
      .addSigner(borrowerAddr)
      .complete();

    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();

    console.log("Interest paid:", txHash);
    return txHash;
  }

  async repayLoan(utxoRef: string): Promise<string> {
    const borrowerAddr = await this.lucid.wallet.address();

    const utxos = await this.lucid.utxosAt(this.validatorAddress);
    const utxo = utxos.find(u => u.txHash === utxoRef.split("#")[0] && u.outputIndex === parseInt(utxoRef.split("#")[1]));
    if (!utxo) throw new Error("UTxO not found");

    // Parse existing datum
    const existingDatum = Data.from(utxo.datum!);
    const datumFields = (existingDatum as any).fields;
    const lenderOption = datumFields[1];
    const lender = (lenderOption as any).fields[0]; // Extract from Some constructor
    const loanAmount = datumFields[2];
    const interestPerPeriod = datumFields[3];

    const redeemer = Data.to(new Constr(2, [])); // RepayLoan redeemer (2)

    const tx = await this.lucid
      .newTx()
      .collectFrom([utxo], redeemer)
      .payToAddress(lender, { lovelace: loanAmount + interestPerPeriod })
      .addSigner(borrowerAddr)
      .complete();

    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();

    console.log("Loan repaid:", txHash);
    return txHash;
  }

  async claimDefault(utxoRef: string): Promise<string> {
    const lenderAddr = await this.lucid.wallet.address();

    const utxos = await this.lucid.utxosAt(this.validatorAddress);
    const utxo = utxos.find(u => u.txHash === utxoRef.split("#")[0] && u.outputIndex === parseInt(utxoRef.split("#")[1]));
    if (!utxo) throw new Error("UTxO not found");

    // Parse existing datum
    const existingDatum = Data.from(utxo.datum!);
    const datumFields = (existingDatum as any).fields;
    const lenderOption = datumFields[1];
    const lender = (lenderOption as any).fields[0]; // Extract from Some constructor

    const redeemer = Data.to(new Constr(3, [])); // ClaimDefault redeemer (3)

    const tx = await this.lucid
      .newTx()
      .collectFrom([utxo], redeemer)
      .payToAddress(lender, utxo.assets)
      .addSigner(lenderAddr)
      .complete();

    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();

    console.log("Default claimed:", txHash);
    return txHash;
  }
}
