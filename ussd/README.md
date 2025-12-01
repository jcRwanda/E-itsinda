# E-Itsinda USSD Service

This folder contains the USSD interface for the E-Itsinda platform, enabling feature phone users to manage savings groups via Africa's Talking USSD gateway.

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

### app.js
Main Express server configuration:
- Body parser middleware for form data
- USSD route mounted at `/ussd/eitsinda`
- Homepage route
- Error handlers

### index.js
USSD business logic:
- `normalizePhoneNumber()`: Converts between +250 and 0 formats
- `groups`: In-memory storage of savings groups
- `userGroups`: Maps phone numbers to group codes
- `processEItsindaRequest()`: Handles menu navigation
- POST `/` endpoint: Processes Africa's Talking callbacks

## Phone Number Format

The service automatically normalizes phone numbers:
- Input: `+250781230980` → `0781230980`
- Input: `0781230980` → `0781230980`

Africa's Talking sends numbers in international format (+250), which is normalized internally.

## Response Types

- **CON**: Continue session (show menu)
- **END**: End session (final message)

## Development

```bash
# Run with auto-reload
npm run dev

# Production mode
npm start
```

## Production Deployment

The service is deployed behind OpenLiteSpeed reverse proxy:

1. **Virtual Host**: ussd.garatech.rw
2. **Proxy Target**: http://127.0.0.1:8080
3. **Context**: `/` → External processor "ussdapp"

To restart after code changes:
```bash
# Restart the service
pm2 restart ussd

# Or restart manually
pkill -f "node app.js"
npm start
```

## Dependencies

- **express**: Web framework
- **body-parser**: Parse form data from Africa's Talking
- **africastalking**: Africa's Talking SDK (if needed for API calls)
- **axios**: HTTP client
- **nodemon**: Development auto-reload

## Troubleshooting

### Service not responding
- Check if Node.js process is running: `ps aux | grep node`
- Check OpenLiteSpeed status: `systemctl status lsws`
- Verify port 8080 is listening: `netstat -tlnp | grep 8080`

### Phone number not recognized
- Ensure phone number is in correct format (0781230980 or +250781230980)
- Check `userGroups` object in index.js for test account

### Menu not displaying
- Verify callback URL in Africa's Talking dashboard
- Check server logs for errors
- Test endpoint with cURL to isolate issue

## Support

For issues specific to:
- Africa's Talking integration: https://help.africastalking.com
- OpenLiteSpeed configuration: https://openlitespeed.org/support/
- E-Itsinda platform: See main project README
