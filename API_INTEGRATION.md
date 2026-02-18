# Carri Logistics - API Integration Guide

This document provides examples of how to integrate with the Carri Logistics API, specifically for the Mobile App (Carriers/Drivers) and Admin functionalities.

## Base URL
`https://your-domain.com/api/v1`

---

## 1. Authentication (Login with Device Locking)

Carriers must provide a `connection_id` (Unique Device ID) during login. The first time they log in, this ID is registered. Subsequent logins must match this ID.

**Endpoint:** `POST /auth/login`

**cURL Example:**
```bash
curl -X POST https://your-domain.com/api/v1/auth/login \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "email": "driver@example.com",
           "password": "password123",
           "device_name": "iPhone 15 Pro",
           "connection_id": "uuid-device-789-xyz"
         }'
```

**Success Response:**
`200 OK` with Bearer Token and User info.

**Error Response (Device Locked):**
`422 Unprocessable Entity`
```json
{
    "message": "Este dispositivo no está autorizado para esta cuenta de transportista.",
    "errors": {
        "connection_id": ["Este dispositivo no está autorizado para esta cuenta de transportista."]
    }
}
```

---

## 2. Fleet GPS Tracking

Used by the Mobile App to send periodic coordinate updates.

**Endpoint:** `POST /fleet/tracking`
**Authorization:** `Bearer {token}`

**cURL Example:**
```bash
curl -X POST https://your-domain.com/api/v1/fleet/tracking \
     -H "Authorization: Bearer 1|xxxxxxxxxxxx" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "lat": 40.416775,
           "lng": -3.703790,
           "speed": 65.5,
           "order_uuid": "550e8400-e29b-41d4-a716-446655440000",
           "carrier_id": 1
         }'
```

---

## 3. Order Events & ePOD (Image Upload)

Report order status changes and upload proof of delivery images.

**Endpoint:** `POST /orders/{orderUuid}/events`
**Authorization:** `Bearer {token}`
**Content-Type:** `multipart/form-data`

**cURL Example:**
```bash
curl -X POST https://your-domain.com/api/v1/orders/550e8400-e29b-41d4-a716-446655440000/events \
     -H "Authorization: Bearer 1|xxxxxxxxxxxx" \
     -H "Accept: application/json" \
     -F "type=unloaded" \
     -F "description=Entrega finalizada con éxito." \
     -F "proof_image=@/path/to/signature_image.jpg" \
     -F "location[lat]=40.416775" \
     -F "location[lng]=-3.703790"
```

---

## 4. Admin: Reset Carrier Device

Allows an admin to clear the registered `connection_id` so a driver can link a new device.

**Endpoint:** `POST /users/{id}/reset-device`
**Authorization:** `Bearer {token}` (Admin only)

**cURL Example:**
```bash
curl -X POST https://your-domain.com/api/v1/users/5/reset-device \
     -H "Authorization: Bearer 2|yyyyyyyyyyyy" \
     -H "Accept: application/json"
```
