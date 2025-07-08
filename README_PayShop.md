
# 📱 PayShop API - Paiement par QR Code & NFC

API Laravel légère permettant :
- La génération de QR Code et tokens NFC
- La validation des paiements côté client
- Le suivi du statut de transaction

---

## 🔐 Authentification

Toutes les routes (sauf `/register` et `/login`) nécessitent un token d'authentification :

- `POST /api/register`
- `POST /api/login` → retourne un `token`
- Ajouter le header :  
  `Authorization: Bearer <token>` pour les autres requêtes

---

## 📦 Endpoints disponibles

### 👤 Authentification

#### `POST /api/register`

Créer un utilisateur :

```json
{
  "name": "Omar",
  "email": "omar@example.com",
  "password": "secret123"
}
```

✅ Réponse :

```json
{
  "token": "your-api-token"
}
```

---

#### `POST /api/login`

```json
{
  "email": "omar@example.com",
  "password": "secret123"
}
```

✅ Réponse :

```json
{
  "token": "your-api-token"
}
```

---

## 🔲 Paiement par QR Code

### `POST /api/qrcode/create`

Crée un QR Code à scanner côté client.

```json
{
  "amount": 1000,
  "currency": "DZD"
}
```

✅ Réponse :

```json
{
  "qrcode_id": "7354fe8b-c553-4ebd-8994-63a91775388d",
  "amount": 1000,
  "currency": "DZD",
  "status": "pending"
}
```

---

### `POST /api/qrcode/validate`

Valider le paiement d’un QR Code (par le client) :

```json
{
  "qrcode_id": "7354fe8b-c553-4ebd-8994-63a91775388d"
}
```

✅ Réponse :

```json
{
  "message": "Paiement validé",
  "status": "paid"
}
```

❌ Si le code est invalide :

```json
{
  "error": "QR Code non trouvé"
}
```

---

### `GET /api/qrcode/status/{qrcode_id}`

Vérifie le statut d’un paiement (par polling côté commerçant) :

✅ Réponse :

```json
{
  "qrcode_id": "7354fe8b-c553-4ebd-8994-63a91775388d",
  "status": "paid"
}
```

---

## 📶 Paiement par NFC

### `POST /api/nfc/create`

Crée un token NFC unique pour paiement par contact :

```json
{
  "amount": 1000,
  "currency": "DZD"
}
```

✅ Réponse :

```json
{
  "nfc_token": "AM0II2LORK",
  "amount": 1000,
  "currency": "DZD",
  "status": "pending"
}
```

---

### `POST /api/nfc/validate`

Valider le paiement via NFC (le téléphone lit le tag NFC) :

```json
{
  "nfc_token": "AM0II2LORK"
}
```

✅ Réponse :

```json
{
  "message": "Paiement par NFC validé",
  "nfc_token": "AM0II2LORK",
  "status": "paid",
  "amount": "1000.00",
  "currency": "DZD"
}
```

---

## 🛡️ Sécurité & Middleware

Toutes les routes `/api/qrcode/*` et `/api/nfc/*` peuvent être protégées avec `auth:sanctum`.

Exemple :
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/qrcode/create', [...]);
});
```

---

## 🧪 Tester avec Postman

1. `POST /api/register` → récupérer un token
2. Ajouter un header :  
   `Authorization: Bearer <token>`
3. Tester `/qrcode/create`, `/nfc/create`, `/validate`, `/status`

---

## 📂 Modèle `Transaction`

| Champ       | Type     | Description                    |
|-------------|----------|--------------------------------|
| `id`        | UUID     | Identifiant transaction        |
| `amount`    | float    | Montant en DZD                 |
| `currency`  | string   | Ex: DZD                        |
| `qrcode_id` | UUID     | ID unique du QR Code           |
| `nfc_token` | string   | Token aléatoire pour NFC       |
| `status`    | string   | `pending`, `paid`, etc.        |
| `created_at`| datetime | Horodatage                     |

---

## 📞 Support

Développé par Omar Fatmi – pour toute question ou évolution de l’API, contactez-moi.

---
