const express = require("express");
const router = express.Router();
const axios = require("axios");
const { anchorProof } = require("./blockchainService");

/* ============================================================
   LANGUAGE TRANSLATIONS
============================================================ */
const translations = {
    KINY: {
        welcome: (name, group) => `CON Murakaza neza ${name}!\nItsinda: ${group}\n\n`,
        mainMenu: "1. Ibyerekeye Itsinda\n2. Gutanga Umusanzu\n3. Gusaba Inguzanyo\n4. Reba Ubwizigame\n9. Language/Ururimi\n0. Sohoka",
        langMenu: "CON Hitamo ururimi / Choose language:\n1. Kinyarwanda (KINY)\n2. English (ENG)\n0. Subira/Back",
        groupDetails: "CON Ibyerekeye Itsinda:\n",
        groupName: "Izina: ",
        groupCode: "Kode: ",
        viewBalance: "1. Reba Ubwizigame\n",
        contribute: "2. Tanga umusanzu\n",
        requestLoan: "3. Saba inguzanyo\n",
        back: "0. Subira",
        exit: "0. Sohoka",
        enterAmount: "CON Shyiramo amafaranga ugiye gutanga:",
        enterLoanAmount: "CON Shyiramo amafaranga wifuza:",
        invalidAmount: "END Umubare winjiye si wo.",
        paymentFailed: "Kwishyura byanze. Gerageza nyuma.",
        contributionSuccess: (amount, group) => `END Umusanzu wa ${amount} RWF wakiriwe!\nItsinda: ${group}\n`,
        loanSuccess: (amount, group) => `END Icyifuzo cyawe cyo gusaba ${amount} RWF cyakiriwe!\nItsinda: ${group}`,
        balance: (balance) => `END Ubwizigame bwawe: ${balance} RWF`,
        thankYou: "END Murakoze gukoresha E-Itsinda!",
        invalidOption: "END Ibyo wahisemo ntibyemewe.",
        noAccount: "END Ooh! Nta konti ufite kuri E-Itsinda.\nMwiyandikishe kuri App cyangwa Web.",
        langChanged: (lang) => `END Ururimi rwahinduwe: ${lang === 'KINY' ? 'Kinyarwanda' : 'English'}`
    },
    ENG: {
        welcome: (name, group) => `CON Welcome ${name}!\nGroup: ${group}\n\n`,
        mainMenu: "1. Group Details\n2. Make Contribution\n3. Request Loan\n4. Check Balance\n9. Language/Ururimi\n0. Exit",
        langMenu: "CON Hitamo ururimi / Choose language:\n1. Kinyarwanda (KINY)\n2. English (ENG)\n0. Subira/Back",
        groupDetails: "CON Group Details:\n",
        groupName: "Name: ",
        groupCode: "Code: ",
        viewBalance: "1. Check Balance\n",
        contribute: "2. Make contribution\n",
        requestLoan: "3. Request loan\n",
        back: "0. Back",
        exit: "0. Exit",
        enterAmount: "CON Enter amount to contribute:",
        enterLoanAmount: "CON Enter loan amount:",
        invalidAmount: "END Invalid amount entered.",
        paymentFailed: "Payment failed. Please try again.",
        contributionSuccess: (amount, group) => `END Contribution of ${amount} RWF received!\nGroup: ${group}\n`,
        loanSuccess: (amount, group) => `END Your loan request of ${amount} RWF has been received!\nGroup: ${group}`,
        balance: (balance) => `END Your balance: ${balance} RWF`,
        thankYou: "END Thank you for using E-Itsinda!",
        invalidOption: "END Invalid option selected.",
        noAccount: "END Ooh! You don't have an E-Itsinda account.\nPlease register via App or Web.",
        langChanged: (lang) => `END Language changed: ${lang === 'KINY' ? 'Kinyarwanda' : 'English'}`
    }
};

// Session storage for user language preferences (in-memory for demo)
const userLanguages = {};

function getLang(phone) {
    return userLanguages[phone] || 'KINY'; // Default to Kinyarwanda
}

function setLang(phone, lang) {
    userLanguages[phone] = lang;
}

function t(phone, key, ...args) {
    const lang = getLang(phone);
    const text = translations[lang][key];
    return typeof text === 'function' ? text(...args) : text;
}

/* ============================================================
   HELPERS
============================================================ */
function normalizePhone(phone) {
    // +25078... → 078...
    return phone.replace(/^\+?250/, "");
}

async function checkPhoneAPI(phone) {
    try {
        const { data } = await axios.post(
            "https://itsinda.garatech.rw/api/check-phone",
            { mobile: phone }
        );
        return data;
    } catch (err) {
        console.error("API ERROR:", err.message);
        return null;
    }
}

/* ============================================================
   MAIN USSD ENDPOINT
============================================================ */
router.post("/", async (req, res) => {
    let { phoneNumber, text } = req.body;

    // 1. Normalize phone number
    phoneNumber = normalizePhone(phoneNumber);

    // 2. Check user from your backend API
    const api = await checkPhoneAPI("+250" + phoneNumber);

    if (!api || api.status !== "success" || !api.data.registered) {
        return res.send(t(phoneNumber, 'noAccount'));
    }

    // 3. Extract user and group information
    const user = api.data.user;
    const group = user.branch;

    // Attach user data to request (useful for flow handlers)
    req.eUser = {
        id: user.id,
        phone: phoneNumber,
        firstname: user.firstname,
        lastname: user.lastname,
        groupCode: group.code,
        groupName: group.name
    };

    // 4. Start USSD menu
    if (!text || text === "") {
        return res.send(
            t(phoneNumber, 'welcome', user.firstname, group.name) +
            t(phoneNumber, 'mainMenu')
        );
    }

    // Handle menu
    const steps = text.split("*");
    const main = steps[0];

    switch (main) {
        case "1":
            return res.send(await menuGroupDetails(req.eUser));

        case "2": // Contribution amount input
            if (steps.length === 1) return res.send(t(phoneNumber, 'enterAmount'));
            return res.send(await confirmContribution(req.eUser, steps[1]));

        case "3": // Loan request amount input
            if (steps.length === 1) return res.send(t(phoneNumber, 'enterLoanAmount'));
            return res.send(await confirmLoan(req.eUser, steps[1]));

        case "4":
            return res.send(await checkBalance(req.eUser));

        case "9": // Language selection
            if (steps.length === 1) {
                return res.send(t(phoneNumber, 'langMenu'));
            } else if (steps[1] === "1") {
                setLang(phoneNumber, 'KINY');
                return res.send(t(phoneNumber, 'langChanged', 'KINY'));
            } else if (steps[1] === "2") {
                setLang(phoneNumber, 'ENG');
                return res.send(t(phoneNumber, 'langChanged', 'ENG'));
            }
            return res.send(t(phoneNumber, 'invalidOption'));

        case "0":
            return res.send(t(phoneNumber, 'thankYou'));

        default:
            return res.send(t(phoneNumber, 'invalidOption'));
    }
});

/* ============================================================
   MENUS & ACTION HANDLERS
============================================================ */

async function menuGroupDetails(user) {
    return (
        t(user.phone, 'groupDetails') +
        t(user.phone, 'groupName') + `${user.groupName}\n` +
        t(user.phone, 'groupCode') + `${user.groupCode}\n\n` +
        t(user.phone, 'viewBalance') +
        t(user.phone, 'contribute') +
        t(user.phone, 'requestLoan') +
        t(user.phone, 'back')
    );
}

/* ---------------------- MoMo Payment ---------------------- */
// Track used payment references to prevent duplicates
const usedPaymentRefs = new Set();

// Store pending payments waiting for MoMo callback
// Key: req_ref, Value: {user, amount, timestamp, sessionId, momoTxId}
const pendingPayments = new Map();

async function processMoMoPayment(phone, amount) {
    const crypto = require('crypto');
    
    // Generate UUID format for req_ref: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
    
    // Generate unique reference and ensure it's not reused
    let reqRef;
    do {
        reqRef = generateUUID();
    } while (usedPaymentRefs.has(reqRef));
    
    // Mark as used
    usedPaymentRefs.add(reqRef);
    
    // Format phone for MoMo API: ensure it starts with 0 (not +250)
    let momoPhone = phone;
    if (momoPhone.startsWith('+250')) {
        momoPhone = '0' + momoPhone.substring(4);
    } else if (momoPhone.startsWith('250')) {
        momoPhone = '0' + momoPhone.substring(3);
    } else if (!momoPhone.startsWith('0')) {
        momoPhone = '0' + momoPhone;
    }
    
    console.log('Initiating MoMo payment:', { amount, phone: momoPhone, req_ref: reqRef });
    
    try {
        const requestBody = {
            amount: Number(amount),
            phone: momoPhone,
            key: "eGx562IiN7y31CmZCnYgFRCfAHYKtQIsucFwvKD6v21OugznarsIYpJTT2xbTOotVO4T2eiGvxfqANaCznOKxQ==",
            req_ref: reqRef,
            note: "E-Itsinda",
            message: `invoice ${reqRef}`,
            callbackurl: "https://ussd.garatech.rw/ussd/callback"
        };
        
        console.log('MoMo API Request Body:', JSON.stringify(requestBody, null, 2));
        
        const response = await axios.post('https://pay-proxy.itec.rw/api2/pay', requestBody);
        
        console.log('MoMo payment initiated successfully:', response.data);
        
        return {
            success: true,
            data: response.data,
            req_ref: reqRef
        };
    } catch (error) {
        // Remove from used set if payment failed
        usedPaymentRefs.delete(reqRef);
        
        const errorData = error.response && error.response.data;
        console.error('MoMo payment error:', {
            status: error.response && error.response.status,
            data: errorData,
            message: error.message,
            fullError: JSON.stringify(errorData, null, 2)
        });
        return {
            success: false,
            error: (errorData && errorData.message) || error.message
        };
    }
}

/* ---------------------- Contribution ---------------------- */
async function confirmContribution(user, amount) {
    if (!amount || isNaN(amount) || Number(amount) <= 0) {
        return t(user.phone, 'invalidAmount');
    }

    // Initiate MoMo payment
    const paymentResult = await processMoMoPayment(user.phone, amount);
    
    if (!paymentResult.success) {
        return `END ${t(user.phone, 'paymentFailed')}\n${paymentResult.error}`;
    }

    // Generate session ID
    const sessionId = "ussd_" + Date.now();
    const reqRef = paymentResult.req_ref;
    
    // Save pending transaction to database immediately
    const pendingPayload = {
        phone: user.phone,
        amount: Number(amount),
        type: "contribution",
        momo_tx_id: reqRef,
        session_id: sessionId,
        status: "PENDING", // Mark as pending
        blockchain_tx: null,
        blockchain_url: null,
        metadata: {
            phone: user.phone,
            group: user.groupCode,
            type: "contribution",
            amount: Number(amount),
            momo_tx_id: reqRef,
            session_id: sessionId,
            status: "PENDING",
            timestamp: new Date().toISOString()
        }
    };
    
    // Save to database as pending
    try {
        const dbResponse = await axios.post(
            "https://itsinda.garatech.rw/api/ussd/contribution",
            pendingPayload
        );
        console.log("Pending transaction saved:", dbResponse.data);
    } catch (dbErr) {
        console.error("DB Save Error (pending):", dbErr.message);
        // Continue even if DB save fails
    }

    // Store pending payment info for callback processing
    pendingPayments.set(reqRef, {
        user: user,
        amount: Number(amount),
        sessionId: sessionId,
        momoTxId: reqRef,
        timestamp: Date.now()
    });

    // Clean up old pending payments (older than 10 minutes)
    const now = Date.now();
    for (const [key, value] of pendingPayments.entries()) {
        if (now - value.timestamp > 10 * 60 * 1000) {
            pendingPayments.delete(key);
        }
    }

    // Return waiting message - actual processing happens in callback
    const lang = getLang(user.phone);
    if (lang === 'ENG') {
        return `END Payment request sent!\n` +
               `Amount: ${amount} RWF\n` +
               `Please approve payment on your phone.\n` +
               `You will receive SMS confirmation.`;
    } else {
        return `END Icyifuzo cyo kwishyura cyoherejwe!\n` +
               `Amafaranga: ${amount} RWF\n` +
               `Emeza kwishyura kuri telefone yawe.\n` +
               `Uzakira ubutumwa bwo kwemeza.`;
    }
}

/* ---------------------- Loan Request ---------------------- */
async function confirmLoan(user, amount) {
    if (!amount || isNaN(amount) || Number(amount) <= 0) {
        return t(user.phone, 'invalidAmount');
    }

    // Later: store loan request → DB or blockchain
    return t(user.phone, 'loanSuccess', amount, user.groupName);
}

/* ---------------------- Balance ---------------------- */
async function checkBalance(user) {
    // Fetch balance from API
    try {
        const { data } = await axios.post(
            "https://itsinda.garatech.rw/api/ussd/contributions/get",
            { phone: user.phone }
        );
        
        if (data.status === "success") {
            const balance = data.data.balance;
            const count = data.data.contributions.length;
            const lang = getLang(user.phone);
            return (
                `END ${lang === 'ENG' ? 'Your balance:' : 'Ubwizigame bwawe:'}\n` +
                `${balance} RWF\n` +
                `${lang === 'ENG' ? 'Contributions:' : 'Umusanzu:'} ${count} ${lang === 'ENG' ? 'times' : 'igihe'}\n` +
                `User: ${user.firstname}`
            );
        }
    } catch (err) {
        console.error("Balance API Error:", err.message);
    }

    const lang = getLang(user.phone);
    return (
        `END ${lang === 'ENG' ? 'Balance updating...' : 'Ubwizigame buracyavugururwa.'}\n` +
        `User: ${user.firstname}`
    );
}

/* ============================================================
   MOMO PAYMENT CALLBACK ENDPOINT
============================================================ */

// Helper function to send SMS notification
async function sendSMS(phone, message) {
    try {
        // Using Africa's Talking SMS API (you'll need to configure your API key)
        // For now, we'll just log it - you can integrate AT SMS later
        console.log(`SMS to ${phone}: ${message}`);
        
        // Uncomment when you have AT SMS configured:
        /*
        const response = await axios.post('https://api.africastalking.com/version1/messaging', {
            username: 'YOUR_USERNAME',
            to: '+250' + phone,
            message: message
        }, {
            headers: {
                'apiKey': 'YOUR_API_KEY',
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        return response.data;
        */
        
        return { success: true };
    } catch (error) {
        console.error('SMS send error:', error.message);
        return { success: false };
    }
}

// Process payment callback from MoMo API
async function processPaymentCallback(callbackData) {
    const reqRef = callbackData.req_ref;
    const status = callbackData.status; // "success" or "failed"
    
    console.log('MoMo Callback Received:', { reqRef, status, data: callbackData });
    
    // Retrieve pending payment info
    const pending = pendingPayments.get(reqRef);
    
    if (!pending) {
        console.error('No pending payment found for req_ref:', reqRef);
        return { ok: false, error: 'Payment session not found' };
    }
    
    const { user, amount, sessionId, momoTxId } = pending;
    
    // Remove from pending
    pendingPayments.delete(reqRef);
    
    // If payment failed, notify user
    if (status !== 'success') {
        const lang = getLang(user.phone);
        const message = lang === 'ENG' 
            ? `Payment failed for ${amount} RWF contribution. Reason: ${callbackData.message || 'Unknown error'}`
            : `Kwishyura ${amount} RWF byanze. Impamvu: ${callbackData.message || 'Ikosa'}`;
        
        await sendSMS(user.phone, message);
        return { ok: false, error: 'Payment failed' };
    }
    
    // Payment successful - process blockchain and database
    try {
        // Prepare blockchain payload
        const payload = {
            phone: user.phone,
            group: user.groupCode,
            type: "contribution",
            amount: amount,
            momo_tx_id: momoTxId,
            session_id: sessionId,
            timestamp: new Date().toISOString(),
            momo_callback: callbackData
        };

        // Anchor proof on Cardano blockchain
        const blockchainResult = await anchorProof(payload);

        // Prepare data for Laravel API
        const apiPayload = {
            phone: user.phone,
            amount: amount,
            type: "contribution",
            momo_tx_id: momoTxId,
            session_id: sessionId,
            status: "SUCCESS", // Mark as success
            blockchain_tx: blockchainResult.ok ? blockchainResult.txHash : null,
            blockchain_url: blockchainResult.ok ? `https://preview.cardanoscan.io/transaction/${blockchainResult.txHash}` : null,
            metadata: payload
        };

        // Update database status from PENDING to SUCCESS
        let dbResponse = null;
        try {
            dbResponse = await axios.post(
                "https://itsinda.garatech.rw/api/ussd/contribution/update",
                apiPayload
            );
            console.log("DB Update Success (PENDING -> SUCCESS):", dbResponse.data);
        } catch (dbErr) {
            console.error("DB Update Error:", dbErr.message);
        }

        // Send success SMS to user
        const lang = getLang(user.phone);
        let smsMessage;
        
        if (blockchainResult.ok) {
            const shortHash = blockchainResult.txHash.substring(0, 12);
            smsMessage = lang === 'ENG'
                ? `Contribution of ${amount} RWF received!\nGroup: ${user.groupName}\nTX: ${shortHash}...\nView: preview.cardanoscan.io/transaction/${blockchainResult.txHash}`
                : `Umusanzu wa ${amount} RWF wakiriwe!\nItsinda: ${user.groupName}\nTX: ${shortHash}...\nReba: preview.cardanoscan.io/transaction/${blockchainResult.txHash}`;
        } else {
            smsMessage = lang === 'ENG'
                ? `Contribution of ${amount} RWF received!\nGroup: ${user.groupName}\n(Blockchain: ${blockchainResult.error})`
                : `Umusanzu wa ${amount} RWF wakiriwe!\nItsinda: ${user.groupName}\n(Blockchain: ${blockchainResult.error})`;
        }
        
        await sendSMS(user.phone, smsMessage);
        
        return { 
            ok: true, 
            txHash: blockchainResult.txHash,
            dbResponse: dbResponse ? dbResponse.data : null
        };
        
    } catch (error) {
        console.error('Payment callback processing error:', error);
        
        // Send error notification
        const lang = getLang(user.phone);
        const message = lang === 'ENG'
            ? `Payment received but processing error occurred. Please contact support.`
            : `Kwishyura kwakiriye ariko habaye ikosa. Hamagara ubufasha.`;
        
        await sendSMS(user.phone, message);
        
        return { ok: false, error: error.message };
    }
}

// Callback endpoint for MoMo API
router.post("/callback", async (req, res) => {
    try {
        console.log('MoMo Callback Request:', req.body);
        
        const result = await processPaymentCallback(req.body);
        
        // Respond to MoMo API
        res.json({
            status: result.ok ? 'success' : 'error',
            message: result.ok ? 'Payment processed' : result.error
        });
        
    } catch (error) {
        console.error('Callback endpoint error:', error);
        res.status(500).json({
            status: 'error',
            message: error.message
        });
    }
});

module.exports = router;
