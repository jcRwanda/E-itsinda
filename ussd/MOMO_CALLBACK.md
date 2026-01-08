# Mobile Money (MoMo) Payment Callback System

## Overview

The E-Itsinda USSD service now implements an **asynchronous payment flow** to properly handle mobile money payments. This means the user receives an immediate response to approve the payment on their phone, and the actual processing (database save + blockchain anchoring) happens when the payment is confirmed.

## Payment Flow

### 1. User Initiates Contribution
```
User dials: *384*1309#
Selects: 2 (Make Contribution)
Enters: 1500 (Amount in RWF)
```

### 2. USSD Immediate Response
```
END Icyifuzo cyo kwishyura cyoherejwe!
Amafaranga: 1500 RWF
Emeza kwishyura kuri telefone yawe.
Uzakira ubutumwa bwo kwemeza.
```

Translation:
```
Payment request sent!
Amount: 1500 RWF
Please approve payment on your phone.
You will receive SMS confirmation.
```

### 3. Behind the Scenes
- USSD service calls MoMo Pay API at `https://pay-proxy.itec.rw/api2/pay`
- Generates unique payment reference: `momo_<timestamp>_<random>`
- Stores pending payment session in memory
- Returns immediately (doesn't wait for payment completion)

### 4. User Approves Payment
- User receives MoMo prompt on their phone
- Enters PIN and confirms payment
- MoMo processes the transaction

### 5. MoMo Callback
- MoMo API sends callback to: `https://ussd.garatech.rw/ussd/callback`
- Callback contains payment status and transaction details
- USSD service retrieves pending payment session

### 6. Processing on Success
If payment successful:
1. **Blockchain Anchoring**: Transaction proof stored on Cardano Preview testnet
2. **Database Save**: Contribution saved via Laravel API
3. **SMS Notification**: User receives confirmation SMS with:
   - Amount contributed
   - Group name
   - Blockchain transaction hash
   - Link to view on CardanoScan

If payment failed:
- User receives SMS with failure reason
- Pending session cleaned up

## Technical Details

### Pending Payments Storage
```javascript
const pendingPayments = new Map();
// Key: req_ref (unique payment reference)
// Value: {
//   user: {...},         // User info (phone, name, group)
//   amount: Number,      // Contribution amount
//   sessionId: String,   // Internal session ID
//   momoTxId: String,    // MoMo transaction reference
//   timestamp: Number    // Unix timestamp
// }
```

### Automatic Cleanup
Pending payments older than **10 minutes** are automatically removed to prevent memory leaks.

### Callback Endpoint

**URL**: `POST https://ussd.garatech.rw/ussd/callback`

**Expected Payload**:
```json
{
  "req_ref": "momo_1765656000000_abcd1234",
  "status": "success",          // or "failed"
  "amount": 1500,
  "phone": "789140853",
  "message": "Payment successful",
  "transaction_id": "MOMO-12345678"
}
```

**Response**:
```json
{
  "status": "success",          // or "error"
  "message": "Payment processed"
}
```

### MoMo API Request
```javascript
{
  amount: Number(amount),
  phone: phone.replace(/^\+?250/, ''),
  key: "YOUR_MOMO_API_KEY",
  req_ref: reqRef,               // Unique reference
  note: "E-Itsinda Contribution",
  message: `Contribution of ${amount} RWF`,
  callbackurl: "https://ussd.garatech.rw/ussd/callback"
}
```

## SMS Notifications

### Success SMS (Kinyarwanda)
```
Umusanzu wa 1500 RWF wakiriwe!
Itsinda: Gahanga saving group
TX: 39d1139bafb3...
Reba: preview.cardanoscan.io/transaction/39d1139bafb3...
```

### Success SMS (English)
```
Contribution of 1500 RWF received!
Group: Gahanga saving group
TX: 39d1139bafb3...
View: preview.cardanoscan.io/transaction/39d1139bafb3...
```

### Failure SMS (Kinyarwanda)
```
Kwishyura 1500 RWF byanze.
Impamvu: Insufficient funds
```

### Failure SMS (English)
```
Payment failed for 1500 RWF contribution.
Reason: Insufficient funds
```

## Testing

### Test Contribution Flow
```bash
# Initiate contribution
curl -X POST http://localhost:8080/ussd/eitsinda \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "sessionId=test123&serviceCode=*384*1309%23&phoneNumber=%2B250789140853&text=2*1500"

# Response:
# END Icyifuzo cyo kwishyura cyoherejwe!
# Amafaranga: 1500 RWF
# Emeza kwishyura kuri telefone yawe.
# Uzakira ubutumwa bwo kwemeza.
```

### Simulate MoMo Callback (Success)
```bash
curl -X POST http://localhost:8080/ussd/callback \
  -H "Content-Type: application/json" \
  -d '{
    "req_ref": "momo_1765656000000_abcd1234",
    "status": "success",
    "amount": 1500,
    "phone": "789140853",
    "message": "Payment successful",
    "transaction_id": "MOMO-12345678"
  }'
```

**Note**: For testing, you need the actual `req_ref` generated during contribution initiation. Check PM2 logs to find it.

### Simulate MoMo Callback (Failure)
```bash
curl -X POST http://localhost:8080/ussd/callback \
  -H "Content-Type: application/json" \
  -d '{
    "req_ref": "momo_1765656000000_abcd1234",
    "status": "failed",
    "amount": 1500,
    "phone": "789140853",
    "message": "Insufficient funds",
    "transaction_id": null
  }'
```

## Monitoring

### Check Pending Payments
Currently stored in-memory Map. To monitor:
```bash
pm2 logs ussd-service | grep "pending"
```

### Check Callback Logs
```bash
pm2 logs ussd-service | grep -A 10 "MoMo Callback"
```

### Check SMS Notifications
```bash
pm2 logs ussd-service | grep "SMS to"
```

## Future Enhancements

### 1. SMS Integration
Currently, SMS notifications are logged but not sent. To enable:
- Sign up for Africa's Talking SMS API
- Get API credentials
- Uncomment SMS sending code in `sendSMS()` function
- Configure environment variables

### 2. Persistent Storage
Consider moving pending payments from in-memory Map to Redis:
- Survives service restarts
- Supports multiple instances
- Better for production scaling

### 3. Retry Mechanism
Add automatic retry for failed blockchain anchoring:
- Store failed transactions
- Retry with exponential backoff
- Notify on permanent failure

### 4. Payment Status Endpoint
Add endpoint for users to check payment status:
```
GET /ussd/payment-status/:req_ref
```

### 5. Webhook Verification
Add signature verification to callback endpoint:
- Verify callbacks are from MoMo API
- Prevent spoofed requests
- Use shared secret key

## Troubleshooting

### Payment stuck in pending
**Symptom**: User approved payment but no SMS received

**Solution**:
1. Check if callback endpoint is reachable: `curl https://ussd.garatech.rw/ussd/callback`
2. Check PM2 logs for callback errors
3. Verify MoMo API can reach your server (firewall/SSL)
4. Check if pending payment expired (>10 minutes)

### Callback endpoint not found
**Symptom**: MoMo API returns 404

**Solution**:
1. Verify route in `app.js`: `app.use('/ussd', eItsindaRoute);`
2. Restart service: `pm2 restart ussd-service`
3. Test manually: `curl http://localhost:8080/ussd/callback`

### Database save fails
**Symptom**: Payment successful but not in database

**Solution**:
1. Check Laravel API: `https://itsinda.garatech.rw/api/ussd/contribution`
2. Review DB error logs in PM2
3. Blockchain proof still succeeds (immutable record)

### Blockchain anchoring fails
**Symptom**: Payment in DB but no blockchain TX

**Solution**:
1. Check admin wallet balance
2. Verify Blockfrost API key in `.env`
3. Check `blockchainService.js` logs
4. User still gets confirmation (DB record exists)

## Security Considerations

1. **API Key Protection**: Never commit MoMo API key to git
2. **Callback Verification**: Implement signature verification
3. **Rate Limiting**: Add rate limits to prevent abuse
4. **Input Validation**: Validate all callback data
5. **HTTPS Only**: Ensure callback URL uses HTTPS
6. **Session Cleanup**: Automatic cleanup prevents memory leaks

## Support

For issues or questions:
- Check PM2 logs: `pm2 logs ussd-service`
- View blockchain TXs: https://preview.cardanoscan.io/
- Contact: support@garatech.rw
