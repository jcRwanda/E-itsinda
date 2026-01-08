const express = require('express');
const bodyParser = require('body-parser');
const eItsindaRoute = require('./index');

const app = express();
const PORT = process.env.PORT || 8080;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

// USSD endpoint for Africa's Talking
app.use('/ussd/eitsinda', eItsindaRoute);

// MoMo callback endpoint (using same router)
app.use('/ussd', eItsindaRoute);

// Simple homepage
app.get('/', (req, res) => {
    res.send(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>E-Itsinda USSD Service</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 50px; text-align: center; }
                h1 { color: #a007c2ff; }
            </style>
        </head>
        <body>
            <h1>🏦 E-Itsinda USSD Service</h1>
            <p>✓ Service is running successfully!</p>
            <p>USSD Endpoint: POST /ussd/eitsinda</p>
        </body>
        </html>
    `);
});

// Error handling
app.use((err, req, res, next) => {
    console.error('Error:', err);
    res.status(500).send('Internal server error');
});

app.listen(PORT, () => {
    console.log(`E-Itsinda USSD Service running on port ${PORT}`);
});