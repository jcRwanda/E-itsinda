### Loan Repayment SmartContract
## How to run test for all projects

```bash
# 1. Build the project
aiken build
# 2. Run all tests
aiken check
# 3. Run specific test
aiken check -m "test_on_time_repayment"
# 4. Run with verbose output to see traces
aiken check -v
# 5. To run the simple example
aiken run examples/simple_test.
```


# Loan Repayment Smart Contract

A Cardano smart contract for managing loan repayments with automated interest calculation, late fees, and protocol fees.

## 📁 Project Structure

```
repay-smart-contract/
├── aiken.toml           # Project configuration
├── lib/                 # Shared utilities
│   └── loan_utils.ak    # Loan calculation functions
├── validators/          # On-chain validators
│   └── repayment.ak     # Main repayment validator
├── tests/               # Test files
│   ├── repayment.test.ak
│   └── debug.test.ak
├── examples/            # Example scripts
├── onchain/             # On-chain builds
└── repayment-frontend/  # React frontend
```

## 🚀 Quick Start

### Prerequisites
- Aiken v1.1.21
- Node.js (for frontend)

### Installation
```bash
# Clone the repository
git clone <your-repo>
cd repay-smart-contract

# Build the smart contract
aiken check
```

## 🧪 Running Tests

Currently experiencing an issue with test execution in Aiken v1.1.21:

```bash
aiken check
```

**Current Output:**
```
Compiling cybercode230/repay-smart-contract 0.0.0 (.)
Resolving cybercode230/repay-smart-contract
  Fetched 1 package in 0.03s from cache
Compiling aiken-lang/stdlib v3.0.0 (./build/packages/aiken-lang-stdlib)
Collecting all tests scenarios across all modules
Summary 0 errors, 0 warnings
```

**Issue:** Tests compile successfully but test results are not displayed. The message "Collecting all tests scenarios across all modules" appears, but no test execution output follows.

## 🔧 Smart Contract Features

### Loan Parameters
- Principal amount
- Interest rate (numerator/denominator)
- Loan duration with due date
- Late fee rate per day
- Protocol fee percentage
- Borrower and lender addresses
- Loan NFT identification

### Validator Logic
1. **Borrower Authorization** - Only the borrower can initiate repayment
2. **NFT Verification** - Requires loan NFT in transaction inputs
3. **Time Calculation** - Computes days late from transaction time
4. **Interest Calculation** - Base interest + late fees if applicable
5. **Payment Verification** - Ensures correct amounts to lender and protocol

### Utility Functions
- `days_late()` - Calculate number of full days late
- `base_interest()` - Calculate principal + interest
- `late_interest()` - Apply daily late fees
- `protocol_fee()` - Calculate protocol commission
- `has_loan_nft()` - Verify NFT presence
- `paid_to_address()` - Verify payment to specific address

## 📝 Usage Example

```aiken
// Create a loan datum
let loan = LoanDatum {
  loan_id: #"4c4e2d31",
  principal: 10_000_000,  // 10 ADA
  interest_rate_n: 10,    // 10%
  interest_rate_d: 100,
  start_time: 1_700_000_000,
  due_time: 1_700_086_400,  // +1 day
  borrower: borrower_address,
  lender: lender_address,
  late_rate_n: 1,         // 1% per day
  late_rate_d: 100,
  protocol_fee_n: 2,      // 2%
  protocol_fee_d: 100,
  loan_nft_policy: #"706f6c696379",
  loan_nft_name: #"4e465431"
}
```

## 🐛 Known Issues

### Test Execution Problem
Aiken v1.1.21 compiles successfully but doesn't display test results. The output shows "Collecting all tests scenarios across all modules" but never shows whether tests pass or fail.

**Workarounds tried:**
1. Changing module naming conventions
2. Creating minimal test files
3. Various `aiken.toml` configurations
4. Clean rebuilds

**Possible causes:**
- Bug in Aiken v1.1.21 test runner
- Module import issues
- Configuration mismatch

## 🛠️ Development

### Building
```bash
# Clean build
rm -rf build
aiken check
```

### Testing Workarounds
Since test results aren't displaying, consider:
1. Manual verification of validator logic
2. Using the Aiken REPL for interactive testing
3. Checking the compiled UPLC for correctness

### Frontend
```bash
cd repayment-frontend
npm install
npm run dev
```

## 📊 Loan Calculations

**On-time repayment:**
```
Base amount = principal + (principal × interest_n ÷ interest_d)
Protocol fee = base_amount × protocol_fee_n ÷ protocol_fee_d
Total to lender = base_amount
```

**Late repayment:**
```
Late days = floor((tx_time - due_time) ÷ 86400)
Amount with late = base_amount + (base_amount × late_n × late_days ÷ late_d)
Protocol fee = amount_with_late × protocol_fee_n ÷ protocol_fee_d
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📄 License

Apache 2.0

## ❓ Getting Help

If you encounter the test execution issue or have questions:
1. Check Aiken documentation for v1.1.21
2. Review the test file structure
3. Consider upgrading Aiken version if possible
4. Use manual validation of contract logic

---

**Note:** The smart contract compiles successfully but test execution output is not visible in Aiken v1.1.21. Manual verification of contract logic is recommended until this issue is resolved.