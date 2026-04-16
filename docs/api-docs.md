# Travel App API Documentation

## Base URL
http://localhost:8000

---

# 1. AUTH SERVICE

## Register
POST /register

Creates a new user.

Request Body:
{
  "full_name": "Josh",
  "email": "josh@example.com",
  "password": "123456"
}

---

## Login
POST /login

Authenticates user.

Request Body:
{
  "email": "josh@example.com",
  "password": "123456"
}

---

## Profile
GET /profile/{id}

Returns user details.

---

# 2. DESTINATION SERVICES

## Weather
GET /weather?city=tokyo

Returns weather data.

---

## Geocode
GET /geocode?city=Tokyo

Returns coordinates.

---

# 3. HOTELS SERVICE (CRUD)

## Create Hotel
POST /hotels

Request Body:
{
  "hotel_name": "BugBusters Hotel",
  "city": "Tokyo",
  "address": "Shibuya",
  "price_per_night": 150,
  "rating": 4.5
}

---

## Get All Hotels
GET /hotels

---

## Get Hotel by ID
GET /hotels/{id}

---

## Update Hotel
PUT /hotels/{id}

Request Body:
{
  "hotel_name": "Updated Hotel",
  "city": "Tokyo",
  "address": "New Address",
  "price_per_night": 180,
  "rating": 4.8
}

---

## Delete Hotel
DELETE /hotels/{id}

---

# 4. BOOKING SERVICE

## Create Booking
POST /booking

Request Body:
{
  "user_id": 1,
  "hotel_id": 1,
  "check_in": "2026-04-20",
  "check_out": "2026-04-22",
  "total_amount": 240.00
}

---

## Get All Bookings
GET /bookings

---

## Get Booking by ID
GET /bookings/{id}

---

# 5. PAYMENT SERVICE

## Make Payment
POST /payment

Request Body:
{
  "booking_id": 1,
  "amount": 240.00
}

---

# 6. SYSTEM

## Health Check
GET /

Returns API Gateway status.

---

# NOTES

- All requests go through the API Gateway
- Services communicate via HTTP
- Weather and Maps use external APIs
- Hotels and Payment use MySQL database