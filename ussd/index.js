const express = require("express");
const router = express.Router();

/* ============================================================
   CONFIGURATION — EASY TO CHANGE TEST USER & GROUP
============================================================ */
const TEST_NUMBER = "0781230980";   // Number for testing/demo
const TEST_GROUP = "G999";          // Group for that number

/* ============================================================
   GROUPS DATABASE (In-Memory — replace with DB later)
============================================================ */
const groups = {
    "G001": { name: "Abishyize Hamwe Gahanga", type: "Daily", members: ["0781234567"], savings: 15000 },
    "G002": { name: "Twiteze Imbere Women SG", type: "Weekly", members: ["0781234567"], savings: 75000 },
    "G003": { name: "Youth Empowerment Tontine", type: "Monthly", members: ["0781234567"], savings: 25000 },

    // TEST group
    [TEST_GROUP]: {
        name: "E-Itsinda Test Group",
        type: "Daily",
        members: [TEST_NUMBER],
        savings: 50000
    }
};

/* ============================================================
   USER → GROUP MAP
============================================================ */
const userGroups = {
    "0781234567": ["G001", "G002", "G003"],
    [TEST_NUMBER]: [TEST_GROUP]
};

/* ============================================================
   PENDING REQUEST STORAGE
============================================================ */
const pendingRequests = {
    [TEST_NUMBER]: { groupCode: null, loanAmount: 0 }
};

/* ============================================================
   MAIN ENDPOINT
============================================================ */
router.post("/", async (req, res) => {
    let { phoneNumber, text } = req.body;
    
    // Normalize phone number - remove country code if present
    // Africa's Talking sends: +250781234567 or 250781234567
    phoneNumber = phoneNumber.replace(/^\+?250/, '0');
    
    let response;

    // Ensure test number always has pendingRequests entry
    if (!pendingRequests[phoneNumber]) {
        pendingRequests[phoneNumber] = { groupCode: null, loanAmount: 0 };
    }

    if (!text || text === "") {
        response =
            "CON Murakaza neza kuri E-Itsinda!\n" +
            "1. Kwiyandikisha mu Itsinda\n" +
            "2. Amatsinda mfitemo\n" +
            "3. Gutanga Umusanzu\n" +
            "4. Gusaba Inguzanyo\n" +
            "5. Reba Ubwizigame\n" +
            "6. Aho uhagaze mu mazina\n" +
            "7. Izindi Serivisi\n" +
            "0. Sohoka";
    } else {
        const steps = text.split("*");
        response = await processFlow(phoneNumber, steps);
    }

    res.set("Content-Type", "text/plain");
    res.send(response);
});

/* ============================================================
   FLOW ROUTER — DECIDES LEVEL OF ACTION
============================================================ */
async function processFlow(phone, steps) {
    const lvl = steps.length;

    if (lvl === 1) return level1(phone, steps[0]);
    if (lvl === 2) return level2(phone, steps[0], steps[1]);
    if (lvl === 3) return level3(phone, steps[0], steps[1], steps[2]);

    return "END Habaye ikosa rito. Ongera ugerageze.";
}

/* ============================================================
   LEVEL 1 — MAIN MENU
============================================================ */
async function level1(phone, opt) {
    switch (opt) {
        case "1": return "CON Shyiramo Kode y'Itsinda:\n0. Subira";
        case "2": return await showUserGroups(phone);
        case "3": return await selectGroup(phone, "umusanzu");
        case "4": return await selectGroup(phone, "inguzanyo");
        case "5": return await selectGroup(phone, "balance");

        case "6":
            return (
                "CON Uko uhagaze:\n" +
                "• Umubare: 7\n" +
                "• Mazina yo kuwa: 03/12/2025\n" +
                "• Urwego: #3\n0. Subira"
            );

        case "7":
            return (
                "CON Izindi Serivisi:\n" +
                "1. Gushiraho Itsinda Rishya\n" +
                "2. Gutanga Ikibazo\n" +
                "3. Ubufasha\n" +
                "4. Gusohoza\n" +
                "0. Subira"
            );

        case "0": return "END Murakoze gukoresha E-Itsinda!";
        default: return "END Icyo wahisemo nticyumvikanye.";
    }
}

/* ============================================================
   LEVEL 2 — SUB-MENU PROCESSING
============================================================ */
async function level2(phone, main, sub) {
    switch (main) {
        case "1": return await processJoinGroup(phone, sub);
        case "2": return await groupDetails(phone, sub);
        case "3": return "CON Shyiramo amafaranga ugiye gutanga:";
        case "4":
            pendingRequests[phone] = { groupCode: sub, loanAmount: 0 };
            return "CON Shyiramo amafaranga wifuza:";
        case "5": return await showBalance(phone, sub);
        case "7": return await extraOptions(phone, sub);
        default: return "END Habaye ikibazo.";
    }
}

/* ============================================================
   LEVEL 3 — FINAL ACTIONS
============================================================ */
async function level3(phone, main, sub, input) {
    switch (main) {
        case "1": return "END Icyifuzo cyo kwinjira cyakiriwe.";
        case "3": return await confirmContribution(phone, sub, input);
        case "4": return await confirmLoan(phone, sub, input);
        case "7": return await extraFinal(phone, sub, input);
        default: return "END Habaye ikibazo.";
    }
}

/* ============================================================
   HELPER FUNCTIONS
============================================================ */
async function showUserGroups(phone) {
    const list = userGroups[phone] || [];
    if (list.length === 0) return "END Nta tsinda uri mo.";

    let menu = "CON Hitamo Itsinda:\n";
    list.forEach((g, i) => menu += `${i + 1}. ${groups[g].name}\n`);
    menu += "0. Subira";

    return menu;
}

async function selectGroup(phone, action) {
    const list = userGroups[phone] || [];
    if (list.length === 0) return "END Nta tsinda uri mo.";

    let menu = `CON Hitamo Itsinda ugiye gukora "${action}":\n`;
    list.forEach((g, i) => (menu += `${i + 1}. ${groups[g].name}\n`));
    return menu;
}

async function processJoinGroup(phone, code) {
    if (!groups[code]) return "END Kode y'Itsinda ntibashije kuboneka.";
    return "CON Emeza kwinjira?\n1. Yego\n2. Oya";
}

async function groupDetails(phone, sel) {
    const list = userGroups[phone] || [];
    const index = parseInt(sel) - 1;
    if (!list[index]) return "END Ibyo wahisemo si byo.";

    const g = groups[list[index]];
    return (
        `CON ${g.name}\n` +
        "1. Reba Ubwizigame\n" +
        "2. Tanga umusanzu\n" +
        "3. Saba inguzanyo\n" +
        "0. Subira"
    );
}

async function showBalance(phone, sel) {
    const list = userGroups[phone] || [];
    const index = parseInt(sel) - 1;
    if (!list[index]) return "END Itsinda ntiribonetse.";

    const g = groups[list[index]];
    return `END Ubwizigame bwawe ni: ${g.savings} RWF`;
}

async function confirmContribution(phone, sel, amount) {
    const list = userGroups[phone] || [];
    const index = parseInt(sel) - 1;

    if (!list[index]) return "END Itsinda ntiribonetse.";
    const a = parseInt(amount);
    if (a <= 0) return "END Umubare winjiye si wo.";

    return `CON Emeza gutanga ${a} RWF?\n1. Yego\n2. Oya`;
}

async function confirmLoan(phone, sel, amount) {
    const list = userGroups[phone] || [];
    const index = parseInt(sel) - 1;

    if (!list[index]) return "END Itsinda ntiribonetse.";
    const a = parseInt(amount);
    if (a <= 0) return "END Umubare winjiye si wo.";

    pendingRequests[phone].loanAmount = a;
    return `CON Emeza gusaba ${a} RWF?\n1. Yego\n2. Oya`;
}

async function extraOptions(phone, opt) {
    switch (opt) {
        case "1": return "CON Andika izina ry'Itsinda Rishya:";
        case "2": return "CON Sobanura ikibazo cyawe:";
        case "3": return "END Ubufasha: 0780-123-456";
        case "4": return "END Murakoze!";
        default: return "END Ibyo wahisemo si byo.";
    }
}

async function extraFinal(phone, opt, input) {
    switch (opt) {
        case "1": return `END Itsinda "${input}" ryaremwe. Komeza kuri App.`;
        case "2": return "END Ikibazo cyawe cyakiriwe.";
        default: return "END Murakoze!";
    }
}

module.exports = router;
