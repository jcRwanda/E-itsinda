import { Lucid, Blockfrost } from "lucid-cardano";

export async function initLucid() {
  const lucid = await Lucid.new(
    new Blockfrost(
      "https://cardano-preprod.blockfrost.io/api/v0",
      "PASTE_BLOCKFROST_KEY_HERE"
    ),
    "Preprod"
  );

  return lucid;
}
