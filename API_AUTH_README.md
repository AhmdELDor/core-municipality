# Authentication API Documentation

Base URL: `http://YOUR_DOMAIN/api/v1`

## 1. Register User
Creates a new user account.

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

## 2. Login (Mobile/User)
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

## 3. Logout
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
