itsinda-loan-dapp/
│
├── onchain/                         # Aiken smart contracts
│   ├── aiken.toml
│   ├── validators/
│   │   ├── loan_validator.ak        # Main loan validator
│   │   └── README.md                # Validator explanation
│   │
│   ├── types/
│   │   ├── loan_datum.ak            # LoanDatum definition
│   │   ├── loan_redeemer.ak         # LoanRedeemer enum
│   │   └── loan_status.ak           # Requested | Active | Repaid | Defaulted
│   │
│   ├── utils/
│   │   ├── time.ak                  # Validity range helpers
│   │   ├── value.ak                 # ADA / asset helpers
│   │   └── checks.ak                # Signature & output checks
│   │
│   └── tests/
│       └── loan_validator_test.ak   # Unit tests (optional but strong)
│
├── offchain/                        # Off-chain transaction builders
│   ├── package.json
│   ├── tsconfig.json
│   │
│   ├── src/
│   │   ├── config/
│   │   │   ├── network.ts           # Preprod / Mainnet config
│   │   │   └── addresses.ts         # Script & wallet addresses
│   │   │
│   │   ├── contracts/
│   │   │   └── loan.ts              # Compiled validator + CBOR
│   │   │
│   │   ├── transactions/
│   │   │   ├── requestLoan.ts       # Borrower requests loan
│   │   │   ├── acceptLoan.ts        # Lender funds loan
│   │   │   ├── payInterest.ts       # Borrower pays interest
│   │   │   ├── repayLoan.ts          # Borrower repays full loan
│   │   │   └── claimDefault.ts      # Lender claims default
│   │   │
│   │   ├── datums/
│   │   │   ├── loanDatum.ts         # Datum builders
│   │   │   └── loanRedeemer.ts      # Redeemer builders
│   │   │
│   │   ├── utils/
│   │   │   ├── wallet.ts            # Connect wallet (Nami, Lace)
│   │   │   ├── utxos.ts             # Find script UTxOs
│   │   │   └── time.ts              # Slot ↔ POSIX helpers
│   │   │
│   │   └── index.ts                 # Entry point
│   │
├── frontend/                        # Web UI
│   ├── package.json
│   ├── vite.config.ts
│   │
│   ├── src/
│   │   ├── pages/
│   │   │   ├── Home.tsx             # Landing page
│   │   │   ├── RequestLoan.tsx      # Borrower UI
│   │   │   ├── LenderDashboard.tsx  # Lender UI
│   │   │   ├── ActiveLoans.tsx      # Loan list
│   │   │   └── LoanDetails.tsx      # Single loan view
│   │   │
│   │   ├── components/
│   │   │   ├── WalletConnect.tsx
│   │   │   ├── LoanCard.tsx
│   │   │   ├── LoanForm.tsx
│   │   │   └── StatusBadge.tsx
│   │   │
│   │   ├── services/
│   │   │   ├── loanService.ts       # Calls off-chain tx builders
│   │   │   └── walletService.ts
│   │   │
│   │   ├── hooks/
│   │   │   └── useWallet.ts
│   │   │
│   │   ├── types/
│   │   │   └── loan.ts              # Frontend loan types
│   │   │
│   │   └── main.tsx
│
├── docs/
│   ├── architecture.md              # System design
│   ├── state-machine.md             # Loan lifecycle diagram
│   └── hackathon-notes.md           # Judge explanation
│
├── .gitignore
└── README.md
