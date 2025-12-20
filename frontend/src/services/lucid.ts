import { Lucid, Blockfrost } from "lucid-cardano";

export async function initLucid() {
  const lucid = await Lucid.new(
    new Blockfrost(
      "https://cardano-preprod.blockfrost.io/api/v0",
      import.meta.env.VITE_BLOCKFROST_API_KEY
    ),
    "Preprod"
  );

  return lucid;
}
