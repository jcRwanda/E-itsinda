use cardano/assets.{lovelace_of, PolicyId, AssetName, quantity_of}
use cardano/transaction.{Transaction}
use cardano/address.{Address, Credential}
use aiken/collection/list

pub fn protocol_address() -> Address {
  let payment = Credential.VerificationKey(
    #"000102030405060708090a0b0c0d0e0f101112131415161718191a1b"
  )
  Address {
    payment_credential: payment,
    stake_credential: None,
  }
}

pub fn days_late(tx_time: Int, due_time: Int) -> Int {
  if tx_time <= due_time {
    0
  } else {
    (tx_time - due_time) / 86_400
  }
}

pub fn base_interest(principal: Int, n: Int, d: Int) -> Int {
  principal + (principal * n) / d
}

pub fn late_interest(amount: Int, days: Int, late_n: Int, late_d: Int) -> Int {
  amount + (amount * late_n * days) / late_d
}

pub fn protocol_fee(amount: Int, n: Int, d: Int) -> Int {
  (amount * n) / d
}

pub fn has_loan_nft(tx: Transaction, policy: PolicyId, name: AssetName) -> Bool {
  tx.inputs
    |> list.any(fn(input) {
      quantity_of(input.output.value, policy, name) == 1
    })
}

pub fn paid_to_address(tx: Transaction, addr: Address, min_lovelace: Int) -> Bool {
  tx.outputs
    |> list.any(fn(out) {
      out.address == addr && lovelace_of(out.value) >= min_lovelace
    })
}