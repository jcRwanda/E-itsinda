# Loan Fixed - Smart Contract

A Cardano smart contract for managing fixed-term loans with deadline-based settlement.

## Overview

This validator implements a simple loan contract between two parties (lender and borrower) with a fixed deadline. The contract allows the borrower to repay before the deadline or allows the lender to claim the collateral after the deadline has passed.

## Contract Logic - Scenario-Based Explanation

### Scenario 1: Happy Path - Borrower Repays on Time

**Actors:**
- Alice (Borrower) - wants to borrow funds
- Bob (Lender) - provides the loan

**Timeline:**
```
Day 0: Loan Creation
  ↓
Day 5: Borrower repays (deadline = Day 10)
  ↓
Day 10: Deadline passes (unused)
```

**What happens:**
1. Bob locks funds in the contract with a datum containing:
   - `borrower`: Alice's verification key hash
   - `lender`: Bob's verification key hash
   - `deadline`: Unix timestamp (e.g., Day 10 at 00:00:00)

2. On Day 5, Alice wants to repay:
   - Alice creates a transaction spending the UTXO
   - She provides `Repay` as the redeemer
   - The transaction MUST be signed by Alice (borrower)
   - The transaction validity range upper bound MUST be ≤ deadline
   - ✅ Validation passes: Alice gets her collateral back, Bob gets repayment

### Scenario 2: Borrower Defaults - Lender Claims Collateral

**Timeline:**
```
Day 0: Loan Creation (deadline = Day 10)
  ↓
Day 10: Deadline passes
  ↓
Day 12: Lender claims collateral
```

**What happens:**
1. Bob (lender) locked funds initially with deadline = Day 10

2. Deadline passes (Day 10), Alice hasn't repaid

3. On Day 12, Bob wants to claim:
   - Bob creates a transaction spending the UTXO
   - He provides `Default` redeemer (any redeemer except `Repay`)
   - The transaction MUST be signed by Bob (lender)
   - The transaction validity range lower bound MUST be > deadline
   - ✅ Validation passes: Bob claims the locked funds

### Scenario 3: Invalid Attempts (Rejections)

#### ❌ Attempt 1: Borrower tries to repay AFTER deadline
```
Day 15 (after deadline): Alice tries to use Repay redeemer
- Transaction validity range lower bound is Day 15 (> deadline)
- REJECTED: Transaction validity range is after deadline
```

#### ❌ Attempt 2: Lender tries to claim BEFORE deadline
```
Day 5 (before deadline): Bob tries to use Default redeemer
- Transaction validity range upper bound is Day 5 (< deadline)
- REJECTED: Transaction validity range hasn't passed the deadline yet
```

#### ❌ Attempt 3: Wrong person tries to repay
```
Day 5: Charlie (not the borrower) tries to spend with Repay
- Transaction is signed by Charlie, not Alice
- REJECTED: Transaction not signed by borrower
```

#### ❌ Attempt 4: Wrong person tries to claim default
```
Day 12: Charlie (not the lender) tries to spend with Default
- Transaction is signed by Charlie, not Bob
- REJECTED: Transaction not signed by lender
```

## Code Structure

### Type Definitions ([lib/itsinda/loan/types.ak](lib/itsinda/loan/types.ak))

```aiken
pub type LoanDatum {
  borrower: VerificationKeyHash,  // Who borrowed the funds
  lender: VerificationKeyHash,    // Who provided the funds
  deadline: Int,                   // Unix timestamp deadline
}

pub type LoanRedeemer {
  Repay    // Borrower wants to repay before deadline
  Default  // Lender wants to claim after deadline
}
```

### Validator Logic ([validators/loan_validator.ak](validators/loan_validator.ak))

The validator implements a `spend` handler that validates two scenarios:

**Repay Branch (Borrower repayment):**
```aiken
when redeemer is {
  Repay -> {
    // Check 1: Transaction must be signed by borrower
    let signed_by_borrower = list.has(self.extra_signatories, datum.borrower)
    
    // Check 2: Transaction validity range upper bound must be before/at deadline
    let before_deadline = (validity_range.upper_bound <= deadline)
    
    // Both conditions must be true
    signed_by_borrower && before_deadline
  }
}
```

**Default Branch (Lender claims):**
```aiken
when redeemer is {
  _ -> {  // Any redeemer except Repay
    // Check 1: Transaction must be signed by lender
    let signed_by_lender = list.has(self.extra_signatories, datum.lender)
    
    // Check 2: Transaction validity range lower bound must be after deadline
    let after_deadline = (validity_range.lower_bound > deadline)
    
    // Both conditions must be true
    signed_by_lender && after_deadline
  }
}
```

## Key Validation Rules

| Action | Who Can Execute | When Can Execute | Required Signature |
|--------|----------------|------------------|-------------------|
| **Repay** | Borrower | Before/at deadline | Borrower's signature |
| **Default** | Lender | After deadline | Lender's signature |

## Time Validation Details

The validator uses the transaction's `validity_range` to enforce time-based rules:

- **validity_range**: An interval defining when the transaction is valid
  - `lower_bound`: Earliest time the transaction can be included
  - `upper_bound`: Latest time the transaction can be included

**For Repay:**
- Upper bound must be ≤ deadline
- Ensures the transaction happens before/at the deadline

**For Default:**
- Lower bound must be > deadline
- Ensures the transaction happens after the deadline



## Building

```sh
aiken build
```

This compiles the validator and generates the Plutus script.

## Testing

You can write tests in any module using the `test` keyword. For example:

```aiken
use itsinda/loan/types.{LoanDatum, Repay}

test borrower_can_repay_before_deadline() {
  // Test implementation
  True
}
```

To run all tests, simply do:

```sh
aiken check
```

To run only tests matching a specific pattern:

```sh
aiken check -m repay
```

## Usage Example

### Step 1: Create the Loan
```typescript
// Lender locks funds with datum
const datum = {
  borrower: "borrower_pubkey_hash",
  lender: "lender_pubkey_hash", 
  deadline: 1704067200000  // Unix timestamp in milliseconds
}

// Lock funds at validator address with this datum
```

### Step 2a: Borrower Repays (Before Deadline)
```typescript
// Borrower creates transaction to spend UTXO
const redeemer = { Repay }
const validityRange = {
  lower_bound: current_time,
  upper_bound: deadline  // Must be ≤ deadline
}
// Transaction must include borrower's signature
```

### Step 2b: Lender Claims Default (After Deadline)
```typescript
// Lender creates transaction to spend UTXO  
const redeemer = { Default }
const validityRange = {
  lower_bound: deadline + 1,  // Must be > deadline
  upper_bound: far_future
}
// Transaction must include lender's signature
```

## Configuration

**aiken.toml**
```toml
[config.default]
network_id = 41  # Preprod testnet
```

For mainnet, use `network_id = 1`



## Security Considerations

### ✅ What the contract ensures:
1. **Authorization**: Only borrower can repay, only lender can claim default
2. **Time constraints**: Repayment only before deadline, default claims only after
3. **Deadline enforcement**: Uses transaction validity ranges for time-based logic

### ⚠️ Important notes:
1. **No amount validation**: The contract doesn't verify loan amounts - handle this off-chain
2. **No collateral check**: Ensure proper collateral is locked when creating the UTXO
3. **Datum must exist**: The validator expects the datum to be present (not `None`)
4. **One-time use**: Each UTXO can only be spent once - this is a feature of UTXOs

## Documentation

If you're writing a library, you might want to generate an HTML documentation for it.

Use:

```sh
aiken docs
```

## Resources

Find more on the [Aiken's user manual](https://aiken-lang.org).
