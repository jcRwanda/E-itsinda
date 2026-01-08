// blockchainService.js
require("dotenv").config();
const axios = require("axios");
const CSL = require("@emurgo/cardano-serialization-lib-nodejs");
const fs = require("fs");
const path = require("path");
const crypto = require("crypto");

const BLOCKFROST_API = "https://cardano-preview.blockfrost.io/api/v0";
const BF_KEY = process.env.BLOCKFROST_API_KEY;
const ADMIN_ADDRESS = process.env.ADMIN_ADDRESS;
const ADMIN_SKEY_PATH = process.env.ADMIN_PAYMENT_SKEY;
const NETWORK = process.env.NETWORK || "preview";

if (!BF_KEY) {
  throw new Error("Set BLOCKFROST_API_KEY in .env");
}
if (!ADMIN_ADDRESS) {
  throw new Error("Set ADMIN_ADDRESS in .env");
}
if (!ADMIN_SKEY_PATH) {
  throw new Error("Set ADMIN_PAYMENT_SKEY path in .env");
}

// Helper: load signing key from cardano-cli JSON format
function loadSigningKey(filePath) {
  const txt = fs.readFileSync(path.resolve(filePath), "utf8").trim();
  
  try {
    const keyJson = JSON.parse(txt);
    if (keyJson && keyJson.cborHex) {
      // The cborHex format: 5820 (CBOR tag for 32-byte string) + 32 bytes of key
      const cborBytes = Buffer.from(keyJson.cborHex, "hex");
      // Skip first 2 bytes (CBOR tag 5820)
      const keyBytes = cborBytes.slice(2);
      return CSL.PrivateKey.from_normal_bytes(keyBytes);
    }
  } catch (e) {
    console.error("Failed to parse signing key:", e);
  }

  // Try bech32 format
  if (txt.startsWith("ed25519e_sk") || txt.startsWith("ed25519_sk")) {
    return CSL.PrivateKey.from_bech32(txt);
  }

  throw new Error("Unsupported signing key format. Use cardano-cli generated key file.");
}

// Fetch protocol parameters
async function getProtocolParams() {
  const resp = await axios.get(`${BLOCKFROST_API}/epochs/latest/parameters`, {
    headers: { project_id: BF_KEY }
  });
  return resp.data;
}

// Fetch UTXOs for admin address
async function getUtxos(address) {
  const resp = await axios.get(`${BLOCKFROST_API}/addresses/${address}/utxos`, {
    headers: { project_id: BF_KEY }
  });
  return resp.data;
}

// Submit tx (signed tx bytes hex)
async function submitTx(signedTxCborHex) {
  const txBytes = Buffer.from(signedTxCborHex, "hex");
  const resp = await axios.post(`${BLOCKFROST_API}/tx/submit`, txBytes, {
    headers: {
      project_id: BF_KEY,
      "Content-Type": "application/cbor"
    }
  });
  return resp.data;
}

// Utility: convert Blockfrost UTXO structure to CSL inputs and total lovelace
function utxoToInputs(utxo) {
  const txHash = utxo.tx_hash;
  const txIndex = utxo.output_index;
  const amount = utxo.amount;
  const lovelaceObj = amount.find(a => a.unit === "lovelace");
  const lovelace = lovelaceObj ? BigInt(lovelaceObj.quantity) : 0n;
  return { txHash, txIndex, lovelace };
}

// Sort keys recursively for canonical JSON
function sortObjectKeys(obj) {
  if (Array.isArray(obj)) return obj.map(sortObjectKeys);
  if (obj && typeof obj === "object") {
    return Object.keys(obj).sort().reduce((acc, k) => {
      acc[k] = sortObjectKeys(obj[k]);
      return acc;
    }, {});
  }
  return obj;
}

// Build metadata from payload
function createMetadata(payload) {
  const canonical = sortObjectKeys(payload);
  const metadata = CSL.GeneralTransactionMetadata.new();
  const label = CSL.BigNum.from_str("674");
  
  // Create a metadata map for the payload
  const metadataMap = CSL.MetadataMap.new();
  
  // Add each field as a separate entry to avoid 64-char limit
  for (const [key, value] of Object.entries(canonical)) {
    const keyDatum = CSL.TransactionMetadatum.new_text(key);
    const valueDatum = CSL.TransactionMetadatum.new_text(String(value));
    metadataMap.insert(keyDatum, valueDatum);
  }
  
  const mapDatum = CSL.TransactionMetadatum.new_map(metadataMap);
  metadata.insert(label, mapDatum);
  return metadata;
}

// Build and sign transaction with metadata
async function buildSignSubmitTx(payload) {
  // Compute receipt hash
  const receiptJson = JSON.stringify(sortObjectKeys(payload));
  const receiptHash = crypto.createHash("sha256").update(receiptJson).digest("hex");

  // Fetch admin UTXOs
  const utxos = await getUtxos(ADMIN_ADDRESS);
  if (!utxos || utxos.length === 0) {
    throw new Error("Admin address has no UTXOs. Fund it from faucet.");
  }

  // Get protocol parameters
  const params = await getProtocolParams();
  const linearFeeA = BigInt(params.min_fee_a);
  const linearFeeB = BigInt(params.min_fee_b);
  const minUtxo = BigInt(params.min_utxo || params.utxo_cost_per_word || 34482);
  const poolDeposit = BigInt(params.pool_deposit || 0);
  const keyDeposit = BigInt(params.key_deposit || 0);

  // Configure TransactionBuilder
  const txBuilderCfg = CSL.TransactionBuilderConfigBuilder.new()
    .fee_algo(
      CSL.LinearFee.new(
        CSL.BigNum.from_str(linearFeeA.toString()),
        CSL.BigNum.from_str(linearFeeB.toString())
      )
    )
    .coins_per_utxo_byte(CSL.BigNum.from_str("4310"))
    .pool_deposit(CSL.BigNum.from_str(poolDeposit.toString()))
    .key_deposit(CSL.BigNum.from_str(keyDeposit.toString()))
    .max_value_size(5000)
    .max_tx_size(16384)
    .build();

  const txBuilder = CSL.TransactionBuilder.new(txBuilderCfg);

  // Get payment key hash from admin address
  const adminAddr = CSL.Address.from_bech32(ADMIN_ADDRESS);
  const enterpriseAddr = CSL.EnterpriseAddress.from_address(adminAddr);
  if (!enterpriseAddr) {
    throw new Error("Admin address must be an enterprise address");
  }
  const paymentKeyHash = enterpriseAddr.payment_cred().to_keyhash();

  // Add inputs from UTXOs
  let totalInput = 0n;
  for (const u of utxos) {
    const { txHash, txIndex, lovelace } = utxoToInputs(u);
    const input = CSL.TransactionInput.new(
      CSL.TransactionHash.from_bytes(Buffer.from(txHash, "hex")),
      txIndex
    );
    const value = CSL.Value.new(CSL.BigNum.from_str(lovelace.toString()));
    
    txBuilder.add_key_input(
      paymentKeyHash,
      input,
      value
    );
    totalInput += lovelace;
    // Stop if we have enough (2+ ADA)
    if (totalInput > 2000000n) break;
  }

  if (totalInput === 0n) {
    throw new Error("No usable UTXO found for ADMIN_ADDRESS.");
  }

  // Add output to self (min UTXO)
  const outAddr = CSL.Address.from_bech32(ADMIN_ADDRESS);
  const minOutValue = CSL.Value.new(CSL.BigNum.from_str("1000000")); // 1 ADA
  txBuilder.add_output(
    CSL.TransactionOutput.new(outAddr, minOutValue)
  );

  // Set TTL (time to live) - current slot + 1000
  const latestBlockResp = await axios.get(`${BLOCKFROST_API}/blocks/latest`, {
    headers: { project_id: BF_KEY }
  });
  const currentSlot = latestBlockResp.data.slot;
  txBuilder.set_ttl(currentSlot + 1000);

  // Create metadata and set auxiliary data hash BEFORE building
  const metadata = createMetadata(payload);
  const aux = CSL.AuxiliaryData.new();
  aux.set_metadata(metadata);
  txBuilder.set_auxiliary_data(aux);

  // Add change
  txBuilder.add_change_if_needed(CSL.Address.from_bech32(ADMIN_ADDRESS));

  // Build transaction body (now includes auxiliary data hash)
  const txBody = txBuilder.build();

  // Load signing key and create witness
  const sk = loadSigningKey(ADMIN_SKEY_PATH);
  
  // Hash the transaction body using Node crypto (blake2b256)
  const blake2 = require('blake2');
  const txBodyBytes = txBody.to_bytes();
  const hash = blake2.createHash('blake2b', { digestLength: 32 });
  hash.update(Buffer.from(txBodyBytes));
  const txBodyHash = CSL.TransactionHash.from_bytes(hash.digest());
  
  const witness = CSL.make_vkey_witness(txBodyHash, sk);

  const witnesses = CSL.TransactionWitnessSet.new();
  const vkeyWitnesses = CSL.Vkeywitnesses.new();
  vkeyWitnesses.add(witness);
  witnesses.set_vkeys(vkeyWitnesses);

  // Create final transaction
  const tx = CSL.Transaction.new(txBody, witnesses, aux);

  // Serialize and submit
  const txHex = Buffer.from(tx.to_bytes()).toString("hex");
  const submitResp = await submitTx(txHex);

  return { txHash: submitResp, receiptHash };
}

// Public function to anchor proof on blockchain
async function anchorProof(payload) {
  payload.server_generated_at = new Date().toISOString();
  try {
    const { txHash, receiptHash } = await buildSignSubmitTx(payload);
    return { ok: true, txHash, receiptHash };
  } catch (err) {
    console.error("anchorProof error:", err);
    return { ok: false, error: err.message };
  }
}

module.exports = { anchorProof };
