#!/bin/bash

DOMAIN="civara.us"
SERVER_IP="184.94.213.20"
TO="yourtest@gmail.com"   # Replace with your test Gmail/Yahoo address
FROM="civara4u@civara.us"

echo "🔎 Checking DNS for $DOMAIN ..."
echo "-----------------------------------"

# 1. A Record
A_RECORD=$(dig A $DOMAIN +short)
[[ "$A_RECORD" == "$SERVER_IP" ]] && \
  echo "✅ A record correct: $A_RECORD" || \
  echo "❌ A record mismatch: $A_RECORD"

# 2. WWW
WWW_RECORD=$(dig A www.$DOMAIN +short)
[[ "$WWW_RECORD" == "$SERVER_IP" ]] && \
  echo "✅ www.$DOMAIN correct" || \
  echo "❌ www.$DOMAIN mismatch: $WWW_RECORD"

# 3. MX
MX_RECORDS=$(dig MX $DOMAIN +short)
echo "$MX_RECORDS" | grep -q "mail.protonmail.ch." && echo "✅ MX mail.protonmail.ch ok" || echo "❌ Missing MX mail.protonmail.ch"
echo "$MX_RECORDS" | grep -q "mailsec.protonmail.ch." && echo "✅ MX mailsec.protonmail.ch ok" || echo "❌ Missing MX mailsec.protonmail.ch"

# 4. SPF
SPF=$(dig TXT $DOMAIN +short | grep spf1)
[[ "$SPF" == *"_spf.protonmail.ch"* ]] && \
  echo "✅ SPF ok" || echo "❌ SPF missing/bad: $SPF"

# 5. DKIM
for i in "" 2 3; do
  DKIM=$(dig CNAME protonmail${i:+$i}._domainkey.$DOMAIN +short)
  [[ -n "$DKIM" ]] && echo "✅ DKIM protonmail${i:+$i} ok: $DKIM" || echo "❌ DKIM protonmail${i:+$i} missing"
done

# 6. DMARC
DMARC=$(dig TXT _dmarc.$DOMAIN +short)
[[ "$DMARC" == *"v=DMARC1"* ]] && echo "✅ DMARC ok: $DMARC" || echo "❌ DMARC missing"

echo "-----------------------------------"
echo "✅ DNS check completed"

# --- EMAIL TEST via Proton Mail Bridge ---
echo "📧 Sending test email via Proton Mail Bridge..."

SMTP_SERVER="127.0.0.1"
SMTP_PORT="1025"
USERNAME="your-bridge-username"   # Replace
PASSWORD="your-bridge-password"   # Replace

echo "Subject: Civara.us Email Test
From: $FROM
To: $TO

This is an automated test email from Civara.us." | \
swaks --server $SMTP_SERVER --port $SMTP_PORT \
  --auth LOGIN --auth-user "$USERNAME" --auth-password "$PASSWORD" \
  --from "$FROM" --to "$TO"

echo "✅ Test email sent. Check $TO inbox and examine headers for SPF/DKIM/DMARC."

