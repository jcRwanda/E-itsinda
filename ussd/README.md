# E-Itsinda USSD Service

the USSD interface for the E-Itsinda platform, enabling feature phone users to manage savings groups via Africa's Talking USSD gateway.

## Quick Start

```bash
cd /home/garatech.rw/ussd
npm install
npm start
```



### Environment

- **Node.js**: v12.x or higher
- **Web Server**: OpenLiteSpeed (reverse proxy)
- **SSL**: Let's Encrypt certificate

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
├── app.js          # Express server setup
├── index.js        # USSD menu logic and routing
├── package.json    # Dependencies and scripts
└── README.md       # This file
```
