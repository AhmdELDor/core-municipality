# Authentication API Documentation

Base URL: `http://YOUR_DOMAIN/api/v1`

## 1. OTP Verification (Required before Register)
Before registering, the user's phone number must be verified using WhatsApp OTP.

### 1.1 Send OTP
Sends a One-Time Password to the user's phone via WhatsApp.

- **Endpoint:** `POST /otp/send`
- **Auth Required:** No

#### Request Body
| Parameter | Type | Required | Description |
|---|---|---|---|
| `phonenumber` | string | Yes | Phone number (e.g., +1234567890) |

#### cURL Example
```bash
curl -X POST "http://127.0.0.1:9000/api/v1/otp/send" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"phonenumber": "+1234567890"}'
```

### 1.2 Verify OTP
Verifies the submitted token. Upon success, the system marks the phone number as verified in the database.

- **Endpoint:** `POST /otp/verify`
- **Auth Required:** No

#### Request Body
| Parameter | Type | Required | Description |
|---|---|---|---|
| `phonenumber` | string | Yes | The phone number being verified |
| `token` | string | Yes | The 6-digit code received |

#### cURL Example
```bash
curl -X POST "http://127.0.0.1:9000/api/v1/otp/verify" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phonenumber": "+1234567890",
    "token": "123456"
  }'
```

---

## 2. Register User
Creates a new user account.

**Important:** The `phonenumber` must be verified via the `/otp/verify` endpoint first. If not verified, the API returns `403 Forbidden`.

- **Endpoint:** `POST /register`
- **Auth Required:** No

### Request Headers
| Key | Value |
|---|---|
| Content-Type | application/json |
| Accept | application/json |

### Request Body
| Parameter | Type | Required | Description |
|---|---|---|---|
| `full_name` | string | Yes | Full name of the user |
| `email` | string | Yes | Valid email address |
| `phonenumber` | string | Yes | Unique phone number |
| `password` | string | Yes | Minimum 8 characters |
| `role` | string | No | Default: 'citizen' |

### cURL Example
```bash
curl -X POST "http://127.0.0.1:9000/api/v1/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "full_name": "John Doe",
    "email": "john@example.com",
    "phonenumber": "+1234567890",
    "password": "password123",
    "role": "citizen"
  }'
```

### Success Response (201 Created)
```json
{
    "data": {
        "user": {
            "id": 1,
            "full_name": "John Doe",
            "email": "john@example.com",
            "phonenumber": "+1234567890",
            "role": "citizen"
        },
        "token": "1|laravel_sanctum_token_string..."
    },
    "message": "User registered successfully",
    "code": 201
}
```

---

## 3. Login (Mobile/User)
Authenticates a user using phone number and password.

- **Endpoint:** `POST /login`
- **Auth Required:** No

### Request Headers
| Key | Value |
|---|---|
| Content-Type | application/json |
| Accept | application/json |

### Request Body
| Parameter | Type | Required | Description |
|---|---|---|---|
| `phonenumber` | string | Yes | Registered phone number |
| `password` | string | Yes | User password |

### cURL Example
```bash
curl -X POST "http://127.0.0.1:9000/api/v1/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phonenumber": "+1234567890",
    "password": "password123"
  }'
```

### Success Response (200 OK)
```json
{
    "data": {
        "user": {
            "id": 1,
            "full_name": "John Doe",
            "phonenumber": "+1234567890",
            "role": "citizen"
        },
        "token": "2|new_laravel_sanctum_token..."
    },
    "message": "Login successful",
    "code": 200
}
```

---

## 4. Logout
Invalidates the current user's access token.

- **Endpoint:** `POST /logout`
- **Auth Required:** Yes (Bearer Token)

### Request Headers
| Key | Value |
|---|---|
| Authorization | Bearer {your_token} |
| Accept | application/json |

### cURL Example
```bash
curl -X POST "http://127.0.0.1:9000/api/v1/logout" \
  -H "Authorization: Bearer 2|new_laravel_sanctum_token..." \
  -H "Accept: application/json"
```

### Success Response (200 OK)
```json
{
    "data": null,
    "message": "Logged out successfully",
    "code": 200
}
```
