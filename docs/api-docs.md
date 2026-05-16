# Draft API Documentation

## Project

BugBusters Travel App API Gateway System

## Overview

The API Gateway is the only client-facing API. Postman and any frontend client call the gateway, then the gateway forwards requests to the correct internal service.

The gateway also calls external travel APIs and combines their responses into a single travel result.

## Base URL

Local Docker testing:

```text
http://localhost:8000
```

Hosted testing:

```text
https://your-api-gateway-url.onrender.com
```

## Required Header

Protected routes require:

```http
X-API-KEY: change-me-gateway-key
```

The public health route does not require this header.

## Gateway Security

Client requests use:

```http
X-API-KEY
```

Internal service calls use:

```http
X-Internal-Service-Key
```

The client does not call the downstream services directly. The gateway sends the internal secret automatically when it calls Auth, Weather, Maps, Hotels, and Payment services.

## External Services Used Through The Gateway

| Gateway Route | External/Internal Source | Purpose |
| --- | --- | --- |
| `GET /geocode?city=Tokyo` | Open-Meteo Geocoding via Maps Service | Converts a city name into latitude, longitude, country, and country code. |
| `GET /weather?city=Tokyo` | Maps Service plus Open-Meteo Forecast via Weather Service | Gets coordinates first, then gets current weather data. |
| `GET /country?country=Japan` | REST Countries API | Gets country information such as capital, region, population, and currency. |
| `GET /currency?base=PHP&quote=USD&amount=100` | Frankfurter API | Converts money from one currency to another. |
| `GET /travel-guide?topic=Tokyo` | Wikipedia REST API | Gets a short travel/topic summary. |
| `GET /travel-search?city=Tokyo` | Maps, Weather, REST Countries, Frankfurter, Wikipedia, Hotels Service | Aggregates the main travel search response. |

## Standard Response Shape

Most routes return JSON in this format:

```json
{
  "status": "success",
  "message": "Request message",
  "data": {}
}
```

If validation or authentication fails, the route returns an error status code with a JSON error response.

## Routes

### Health Check

```http
GET /health
```

Purpose: confirms that the API Gateway is running.

Header required: no.

Expected result: `status` is `success`.

### Register User

```http
POST /register
```

Header required: yes.

Body:

```json
{
  "full_name": "Josh Cruz",
  "email": "josh@example.com",
  "password": "123456"
}
```

Purpose: sends user registration data through the gateway to the Auth Service.

Expected result: user record is created and a `user_id` is returned.

### Login User

```http
POST /login
```

Header required: yes.

Body:

```json
{
  "email": "josh@example.com",
  "password": "123456"
}
```

Purpose: verifies a registered user through the Auth Service.

Expected result: login success response with user information.

### Get Profile

```http
GET /profile/1
```

Header required: yes.

Purpose: retrieves a user profile by ID through the Auth Service.

### Geocode City

```http
GET /geocode?city=Tokyo
```

Header required: yes.

Purpose: calls the Maps Service, which uses Open-Meteo Geocoding to find location data for a city.

Expected data includes city, country, latitude, and longitude.

### Weather By City

```http
GET /weather?city=Tokyo
```

Header required: yes.

Purpose: the gateway first gets coordinates from the Maps Service, then sends the coordinates to the Weather Service for Open-Meteo forecast data.

Expected data includes location and weather information.

### Country Information

```http
GET /country?country=Japan
```

Header required: yes.

Purpose: calls REST Countries through the gateway to get country metadata.

Expected data includes country name, capital, region, population, currency code, and flag URL when available.

### Currency Conversion

```http
GET /currency?base=PHP&quote=USD&amount=100
```

Header required: yes.

Purpose: calls Frankfurter through the gateway to convert a currency amount.

Expected data includes base currency, quote currency, amount, exchange rate, and converted amount.

### Travel Guide

```http
GET /travel-guide?topic=Tokyo
```

Header required: yes.

Purpose: calls Wikipedia REST through the gateway to get a short topic summary.

Expected data includes title, extract or summary, and source URL when available.

### Travel Search Aggregation

```http
GET /travel-search?city=Tokyo
```

Header required: yes.

Purpose: demonstrates the main gateway aggregation flow.

The gateway combines:

- Maps Service
- Weather Service
- REST Countries API
- Frankfurter currency API
- Wikipedia REST API
- Hotels Service

Expected result: one response containing location, weather, country, currency, travel guide, and hotels data.

### List Hotels

```http
GET /hotels?city=Tokyo
```

Header required: yes.

Purpose: retrieves hotel records through the Hotels Service.

### Create Hotel

```http
POST /hotels
```

Header required: yes.

Body:

```json
{
  "hotel_name": "BugBusters Hotel",
  "city": "Tokyo",
  "address": "Shibuya, Tokyo",
  "price_per_night": 150.00,
  "rating": 4.6
}
```

Purpose: creates a hotel record through the Hotels Service.

### Create Booking

```http
POST /booking
```

Header required: yes.

Body:

```json
{
  "user_id": 1,
  "hotel_id": 1,
  "check_in": "2026-05-20",
  "check_out": "2026-05-22",
  "total_amount": 240.00
}
```

Purpose: creates a booking through the Payment Service.

Expected result: a `booking_id` is returned.

### Make Payment

```http
POST /payment
```

Header required: yes.

Body:

```json
{
  "booking_id": 1,
  "amount": 240.00
}
```

Purpose: creates a payment record and marks the booking as paid.

Expected result: a `payment_id` is returned and the booking status becomes `paid`.

### List Bookings

```http
GET /bookings
```

Header required: yes.

Purpose: retrieves bookings and related payment data through the Payment Service.

## Postman Test Order

Run these requests in order:

1. `GET /health`
2. `POST /register`
3. `POST /login`
4. `GET /profile/1`
5. `GET /geocode?city=Tokyo`
6. `GET /weather?city=Tokyo`
7. `GET /country?country=Japan`
8. `GET /currency?base=PHP&quote=USD&amount=100`
9. `GET /travel-guide?topic=Tokyo`
10. `GET /travel-search?city=Tokyo`
11. `GET /hotels?city=Tokyo`
12. `POST /booking`
13. `POST /payment`
14. `GET /bookings`

## Postman Variables

```text
baseUrl = http://localhost:8000
gatewayKey = change-me-gateway-key
```

For hosted testing, replace `baseUrl` with the Render API Gateway URL.

## Validation Checklist

- Health check returns success.
- Protected routes fail without `X-API-KEY`.
- Protected routes pass with the correct `X-API-KEY`.
- Geocode returns location data from an external API.
- Weather returns forecast data through the gateway.
- Country, currency, and travel guide routes return external API data.
- Travel search returns aggregated data from multiple sources.
- Booking and payment routes save and return records.
