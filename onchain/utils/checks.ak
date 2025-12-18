use aiken/collection/list
use cardano/transaction.{Output}

// Verify that a public key is in the extra signatories
fn check_signature(extra_signatories: list PubKeyHash, pkh: PubKeyHash) -> Bool {
  list.has(extra_signatories, pkh)
}

// Ensure there is **exactly one continuing output** at the same address
fn check_continuing_output(outputs: list Output, addr: Address) -> Output {
  expect [output] =
    list.filter(outputs, fn(o: Output) { o.address == addr })
  output
}
