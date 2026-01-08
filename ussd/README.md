# E-Itsinda USSD Service

This folder contains the USSD interface for the E-Itsinda platform, enabling feature phone users to manage savings groups via Africa's Talking USSD gateway with Cardano blockchain integration for transaction proofs.

## Quick Start

```bash
cd /home/garatech.rw/ussd
npm install
npm start
```

The service runs on port 8080 and is accessible at: https://ussd.garatech.rw/ussd/eitsinda

## Configuration

### Africa's Talking Setup

- **Service Code**: `*384*1309#`
- **Callback URL**: `https://ussd.garatech.rw/ussd/eitsinda`
- **Method**: POST
- **Content-Type**: application/x-www-form-urlencoded

### Cardano Blockchain Setup

The service anchors transaction proofs on Cardano Preview testnet:

1. **Get Blockfrost API Key**: https://blockfrost.io/dashboard
2. **Fund Admin Wallet**: https://docs.cardano.org/cardano-testnets/tools/faucet/
3. **Configure .env**:
```bash
BLOCKFROST_API_KEY=preview_your_key_here
ADMIN_PAYMENT_SKEY=./keys/admin.skey
ADMIN_PAYMENT_VKEY=./keys/admin.vkey
ADMIN_ADDRESS=addr_test1...
NETWORK=preview
```

### Environment

- **Node.js**: v12.x or higher
- **Web Server**: OpenLiteSpeed (reverse proxy)
- **SSL**: Let's Encrypt certificate
- **Blockchain**: Cardano Preview Testnet

## Testing

### Test Account

A pre-configured test account is available:

- **Phone Number**: `0781230980` (or `+250781230980`)
- **Group**: G999 (Indashyikirwa)
- **Balance**: 50,000 RWF
- **Role**: Member

### Testing with Africa's Talking Simulator

**Official Simulator**: https://developers.africastalking.com/simulator

1. Sign in to your Africa's Talking account
2. Navigate to the Sandbox Simulator
3. Select **USSD** tab
4. Enter phone number: `+250781230980`
5. Enter USSD code: `*384*1309#`
6. Click **Dial**
7. Interact with the menu by entering numbers and submitting




### Testing with cURL

```bash
# Main menu
curl -X POST https://ussd.garatech.rw/ussd/eitsinda \
  -d "sessionId=test123" \
  -d "serviceCode=*384*1309#" \
  -d "phoneNumber=%2B250781230980" \
  -d "text="

# View balance (option 1)
curl -X POST https://ussd.garatech.rw/ussd/eitsinda \
  -d "sessionId=test123" \
  -d "serviceCode=*384*1309#" \
  -d "phoneNumber=%2B250781230980" \
  -d "text=1"

# View group info (option 2)
curl -X POST https://ussd.garatech.rw/ussd/eitsinda \
  -d "sessionId=test123" \
  -d "serviceCode=*384*1309#" \
  -d "phoneNumber=%2B250781230980" \
  -d "text=2"
```

## Menu Structure

```
Main Menu
├── 1. Reba amafaranga yawe (View Balance)
├── 2. Reba amakuru y'itsinda (View Group Info)
├── 3. Kwinjira mu itsinda (Join Group)
├── 4. Gusohoka mu itsinda (Leave Group)
├── 5. Gutanga inguzanyo (Request Loan)
├── 6. Kwishyura inguzanyo (Pay Loan)
└── 7. Amateka y'ibikorwa (Transaction History)

Navigation:
- 0. Subira/Gusohoka (Back/Exit)
- 00. Ahabanza (Main Menu)
```

## File Structure

```
ussd/
├── app.js                 # Express server setup
├── index.js               # USSD menu logic and routing
├── blockchainService.js   # Cardano blockchain integration
├── package.json           # Dependencies and scripts
├── .env                   # Environment variables (not committed)
├── keys/                  # Admin wallet files (not committed)
│   ├── admin.skey        # Admin signing key
│   ├── admin.vkey        # Admin verification key
│   └── admin.addr        # Admin address
└── README.md             # This file
```

### app.js
Main Express server configuration:
- Body parser middleware for form data
- USSD route mounted at `/ussd/eitsinda`
- Homepage route
- Error handlers

### index.js
USSD business logic:
- `normalizePhoneNumber()`: Converts between +250 and 0 formats
- `checkPhoneAPI()`: Verifies user registration via backend API
- `confirmContribution()`: Anchors contributions on blockchain
- POST `/` endpoint: Processes Africa's Talking callbacks

### blockchainService.js
Cardano blockchain integration:
- `anchorProof()`: Main function to anchor transaction proofs
- `buildSignSubmitTx()`: Builds, signs, and submits Cardano transactions
- `loadSigningKey()`: Loads admin signing key from file
- Creates metadata transactions under label 674
- Returns transaction hash for verification
└── README.md       # This file
```
