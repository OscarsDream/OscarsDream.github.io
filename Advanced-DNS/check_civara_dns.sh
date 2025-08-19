#!/bin/bash

DOMAIN="civara.us"
SERVER_IP="184.94.213.20"

echo "🔎 Checking DNS for $DOMAIN ..."
echo "-----------------------------------"

# 1. Check A record
A_RECORD=$(dig A $DOMAIN +short)
if [[ "$A_RECORD" == "$SERVER_IP" ]]; then
  echo "✅ A record points to $SERVER_IP"
else
  echo "❌ A record mismatch. Got: $A_RECORD"
fi

# 2. Check www CNAME
WWW_RECORD=$(dig A www.$DOMAIN +short)
if [[ "$WWW_RECORD" == "$SERVER_IP" ]]; then
  echo "✅ www.$DOMAIN resolves correctly"
else
  echo "❌ www.$DOMAIN does not resolve correctly. Got: $WWW_RECORD"
fi

# 3. Check MX records
MX_RECORDS=$(dig MX $DOMAIN +short)
if echo "$MX_RECORDS" | grep -q "10 mail.protonmail.ch."; then
  echo "✅ MX record for mail.protonmail.ch found"
else
  echo "❌ Missing MX: mail.protonmail.ch"
fi
if echo "$MX_RECORDS" | grep -q "20 mailsec.protonmail.ch."; then
  echo "✅ MX record for mailsec.protonmail.ch found"
else
  echo "❌ Missing MX: mailsec.protonmail.ch"
fi

# 4. Check SPF record
SPF=$(dig TXT $DOMAIN +short | grep spf1)
if [[ "$SPF" == *"include:_spf.protonmail.ch"* ]]; then
  echo "✅ SPF record is correct"
else
  echo "❌ SPF record missing or incorrect. Got: $SPF"
fi

# 5. Check DKIM records
for i in "" 2 3; do
  DKIM=$(dig CNAME protonmail${i:+$i}._domainkey.$DOMAIN +short)
  if [[ -n "$DKIM" ]]; then
    echo "✅ DKIM record protonmail${i:+$i}._domainkey exists → $DKIM"
  else
    echo "❌ DKIM record protonmail${i:+$i}._domainkey missing"
  fi
done

# 6. Check DMARC
DMARC=$(dig TXT _dmarc.$DOMAIN +short)
if [[ "$DMARC" == *"v=DMARC1"* ]]; then
  echo "✅ DMARC record found: $DMARC"
else
  echo "❌ DMARC record missing"
fi

echo "-----------------------------------"
echo "✅ DNS check completed!"

