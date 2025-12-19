use cardano/assets.{lovelace_of, PolicyId, AssetName, quantity_of}
use cardano/transaction.{Transaction}
use cardano/address.{Address}
use aiken/collection/list

/// =======================================================
/// Loan Utility Functions
/// Shared PURE on-chain logic
/// =======================================================

/// -------------------------------------------------------
/// Time utilities
/// -------------------------------------------------------

/// Calculate number of full days late (POSIX seconds)
pub fn days_late(tx_time: Int, due_time: Int) -> Int {
  if tx_time <= due_time { 0 } else { (tx_time - due_time) / 86_400 }
}

/// -------------------------------------------------------
/// Interest calculations
/// -------------------------------------------------------

/// Base interest: principal + (principal * n / d)
pub fn base_interest(principal: Int, n: Int, d: Int) -> Int {
  principal + (principal * n / d)
}

/// Late interest applied per day
pub fn late_interest(amount: Int, days: Int, late_n: Int, late_d: Int) -> Int {
  amount + (amount * late_n * days / late_d)
}

/// Protocol fee calculation
pub fn protocol_fee(amount: Int, n: Int, d: Int) -> Int {
  amount * n / d
}

/// -------------------------------------------------------
/// Asset & NFT checks
/// -------------------------------------------------------

/// Checks if a specific NFT exists in ANY input UTxO
pub fn has_loan_nft(tx: Transaction, policy: PolicyId, name: AssetName) -> Bool {
  tx.inputs |> list.any(fn(input) { quantity_of(input.output.value, policy, name) == 1 })
}

/// -------------------------------------------------------
/// Payment verification helpers
/// -------------------------------------------------------

/// Verify lovelace paid to a specific address
pub fn paid_to_address(tx: Transaction, addr: Address, min_lovelace: Int) -> Bool {
  tx.outputs |> list.any(fn(out) { out.address == addr && lovelace_of(out.value) >= min_lovelace })
}

/// -------------------------------------------------------
/// Protocol / DAO fee address (hard-coded for testing)
/// -------------------------------------------------------
pub fn protocol_address() -> Address {
  Address.Test("mock_protocol_address")
}


