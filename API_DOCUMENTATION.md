# API Documentation

## Authentication Endpoints

### Register

Register a new user in the application.

**Endpoint:** `POST /api/register`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| full_name | string | Yes | User's full name (max 255 characters) |
| phonenumber | string | Yes | User's phone number (must be unique) |
| address | string | Yes | User's address (max 255 characters) |
| password | string | Yes | User's password (min 8 characters) |
| role | string | No | User's role (defaults to 'citizen' if not provided) |

**Example cURL Command:**

```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "full_name": "John Doe",
    "phonenumber": "1234567890",
    "address": "123 Main Street, City",
    "password": "securepassword123"
  }'
```

**Example with Role:**

```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "full_name": "Jane Smith",
    "phonenumber": "0987654321",
    "address": "456 Oak Avenue, Town",
    "password": "mypassword456",
    "role": "citizen"
  }'
```

**Success Response (201 Created):**

```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "full_name": "John Doe",
      "phonenumber": "1234567890",
      "address": "123 Main Street, City",
      "role": "citizen",
      "created_at": "2026-02-06T17:44:25.000000Z",
      "updated_at": "2026-02-06T17:44:25.000000Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz1234567890"
  }
}
```

**Error Response (422 Unprocessable Entity):**

```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "phonenumber": [
      "The phonenumber has already been taken."
    ],
    "password": [
      "The password field must be at least 8 characters."
    ]
  }
}
```

**Notes:**
- Replace `http://localhost` with your actual API base URL
- The response includes an authentication token that should be used for subsequent authenticated requests
- Store the token securely and include it in the Authorization header for protected endpoints: `Authorization: Bearer {token}`

### Login (Mobile)

Login for mobile app users.

**Endpoint:** `POST /api/login`

**Example cURL Command:**

```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phonenumber": "1234567890",
    "password": "securepassword123"
  }'
```

### Login (Admin)

Login for admin users.

**Endpoint:** `POST /api/login/admin`

**Example cURL Command:**

```bash
curl -X POST http://localhost/api/login/admin \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phonenumber": "admin_phone",
    "password": "admin_password"
  }'
```

**Note:** Only users with 'admin' or 'superadmin' role can use this endpoint.

### Logout

Logout and revoke the current authentication token.

**Endpoint:** `POST /api/logout`

**Example cURL Command:**

```bash
curl -X POST http://localhost/api/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {your_token_here}"
```

## Using the Token

After successful registration or login, use the returned token to authenticate subsequent requests:

```bash
curl -X GET http://localhost/api/user \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz1234567890"
```
