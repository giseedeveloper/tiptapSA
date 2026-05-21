# TAPTAP_sauth — WhatsApp Cloud API (kuanza hapa)

Bot ya `tiptopbot` **tayari v2.0** (Meta Graph API, hakuna Baileys). Fuata hatua hizi kwa mpangilio.

## 1. Jaza Meta credentials

Kwenye **`.env` ya Laravel** na **`tiptopbot/.env`** (thamani sawa):

| Variable | Mahali Meta |
|----------|-------------|
| `WHATSAPP_PHONE_NUMBER_ID` | WhatsApp → API Setup → Phone number ID |
| `WHATSAPP_ACCESS_TOKEN` | API Setup → token |
| `WHATSAPP_VERIFY_TOKEN` | Unachagua (mf. `TIPTAP_BOT`) |
| `WHATSAPP_APP_SECRET` | App settings → Basic → **App secret** (lazima!) |
| `WHATSAPP_GRAPH_VERSION` | `v20.0` |

Pia:

```env
WHATSAPP_BOT_NOTIFY_URL=https://<bot-host>/notify
WHATSAPP_BOT_NOTIFY_SECRET=<random-long-string>
```

`NOTIFY_SECRET` kwenye `tiptopbot/.env` = **ile ile** na `WHATSAPP_BOT_NOTIFY_SECRET`.

```env
API_BASE_URL=https://<laravel-domain>/api/bot
BOT_TOKEN=<sanctum-token-kutoka-admin-bots>
PORT=3000
BIND=0.0.0.0
```

## 2. Kagua usanidi

```bash
php artisan whatsapp:doctor
php artisan whatsapp:doctor --probe   # baada ya bot kuwa live
```

## 3. Database

```bash
php artisan migrate
```

Hakikisha jedwali `bot_sessions` lipo (sessions za mazungumzo).

## 4. Meta Developer Console → Webhook

On **Step 2 → Configure Webhooks** (screenshot ya Meta):

| Field | Weka hivi (TAPTAP_sauth) |
|-------|---------------------------|
| **Callback URL** | `https://<APP_URL-yako>/api/whatsapp/webhook` — mfano ikiwa site ni `https://tiptapafrica.co.tz/public` basi path inaweza kuwa chini ya domain halisi; tumia URL ambayo `php artisan whatsapp:doctor` inaonyesha kama *Meta webhook (forwarder)* |
| **Verify token** | Thamani sawa na `WHATSAPP_VERIFY_TOKEN` (mf. `TIPTAP_BOT`) |

Bonyeza **Verify and save**. Server lazima iwe live na HTTPS kabla ya verify.

**Onyo ya Meta (app haijachapishwa):** Unaweza kuthibitisha webhook na kutuma **test** kutoka dashboard. Ujumbe halisi wa wateja hutafika hadi app ichapishwe (Publish) na nambari isajiliwe (Step: Register phone number).

**Inapendekezwa (forwarder):** Meta → Laravel → bot `/inbound`.

**Mbadala:** Callback URL = `https://wa-notify.tiptapafrica.co.tz/webhook` (moja kwa moja kwenye bot; hakuna Laravel forward).

Baada ya save, subscribe field **`messages`**.

## 5. Anzisha bot (VPS: **TIPTAP SOUTH AFRICA**)

```bash
cd tiptopbot
npm install
npm start
# au: docker compose up -d --build
curl http://127.0.0.1:3000/health
```

## 6. HTTPS + nginx (production)

Bot lazima iwe HTTPS. Nginx inaweza proxy:

- `/webhook` — Meta (ikiwa direct)
- `/inbound` — Laravel forward
- `/notify` — Laravel bill push

Tazama `tiptopbot/deploy/nginx-wa-notify.example.conf` (sasishwa kwa port 3000).

## 7. Jaribu

1. Tuma WhatsApp kwa nambari ya biashara.
2. Bot inajibu (logs: `tiptopbot` console).
3. Scan QR meza → order flow.
4. Bill image: URL ya PNG lazima iwe **HTTPS public** (`WHATSAPP_BILL_IMAGE_BASE_URL` ikiwa Laravel iko chini ya `/public`).

## Matatizo ya kawaida

| Tatizo | Suluhisho |
|--------|-----------|
| Meta: *callback URL or verify token couldn't be validated* | Tazama [Webhook verify failed](#webhook-verify-failed-meta-error) hapa chini |
| Webhook verify fails (token) | `WHATSAPP_VERIFY_TOKEN` lazima iwe **sawa kabisa** na Meta (case-sensitive) kwenye `tiptopbot/.env` **kwenye VPS** |
| 401 signature | Jaza `WHATSAPP_APP_SECRET` Laravel + bot |
| Bot haipokei messages | `WHATSAPP_BOT_NOTIFY_URL` sahihi; forward URL ni `.../inbound` si `.../notify/inbound` |
| Bill haifiki | `NOTIFY_SECRET` sawa; `/notify` reachable; picha HTTPS |

### Webhook verify failed (Meta error)

Meta inatumia `GET /webhook?hub.mode=subscribe&hub.verify_token=...&hub.challenge=...`

**1. Nginx inarudisha 404 (sababu kuu)**  
Ikiwa `curl` inarudisha HTML `404 Not Found`, Meta haitaweza kuthibitisha — ongeza `location = /webhook` kwenye nginx (tazama `tiptopbot/deploy/nginx-wa-notify.example.conf`), kisha `sudo nginx -t && sudo systemctl reload nginx`.

**2. Bot ya zamani bado inaendesha**  
`GET /health` ya **tiptopbot v2** inapaswa kurudisha `service: tiptopbot`. Ikiwa unaona `{"ok":true,"ready":true}` pekee, hiyo si v2 — deploy tena:

```bash
cd tiptopbot && docker compose down && docker compose build --no-cache bot && docker compose up -d bot
```

**3. Verify token hailingani**  
Meta: `TATAP_south@2026` → lazima `WHATSAPP_VERIFY_TOKEN=TATAP_south@2026` kwenye `tiptopbot/.env` (VPS). Badilisha Meta **au** `.env`, si mchanganyiko.

**4. Jaribu kabla ya Meta**

```bash
curl -sS "https://wa-notify.tiptapafrica.co.tz/webhook?hub.mode=subscribe&hub.verify_token=TOKEN_YAKO&hub.challenge=12345"
```

Inapaswa kurudisha `12345` na HTTP 200 — si HTML 404.

**DNS:** `wa-notify.tiptapafrica.co.tz` → VPS ya bot (angalia `dig +short`); nginx na docker zifanyike **kwenye VPS hiyo**.
