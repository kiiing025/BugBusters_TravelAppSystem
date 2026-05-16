# API Documentation

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
https://bugbusters-api-gateway.onrender.com
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

## Error Response Standard

The API documentation includes expected errors so testers know what should happen when a request has a mistake.

Standard error shape:

```json
{
  "status": "error",
  "message": "Error explanation",
  "data": []
}
```

Some gateway aggregation routes may return:

```json
{
  "status": "partial_error",
  "message": "Some service data could not be completed.",
  "data": {}
}
```

Common status codes:

| Status Code | Meaning | Common Cause |
| --- | --- | --- |
| `400 Bad Request` | Request cannot be completed | Invalid query or external API problem |
| `401 Unauthorized` | Request is not allowed | Missing or wrong `X-API-KEY`, or direct call to an internal service without `X-Internal-Service-Key` |
| `404 Not Found` | Record or route does not exist | Wrong user, hotel, booking, or endpoint URL |
| `409 Conflict` | Duplicate data | Registering an email that already exists |
| `422 Unprocessable Entity` | Validation error | Missing required JSON field or invalid value |
| `502 Bad Gateway` | Downstream service problem | Service is sleeping, unavailable, or returned a non-JSON error page |

Example missing API key:

```json
{
  "status": "error",
  "message": "Unauthorized gateway request. Provide a valid X-API-KEY header.",
  "data": []
}
```

Example duplicate email:

```json
{
  "status": "error",
  "message": "Email already exists",
  "data": []
}
```

Example missing record:

```json
{
  "status": "error",
  "message": "User not found",
  "data": []
}
```

Example downstream service error:

```json
{
  "status": "error",
  "message": "Service returned a non-JSON response.",
  "data": "<html>...</html>"
}
```

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

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `409 Conflict` if the email already exists.
- `422 Unprocessable Entity` if `full_name`, `email`, or `password` is missing or invalid.

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

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `401 Unauthorized` if the password is wrong.
- `404 Not Found` if the email is not registered.
- `422 Unprocessable Entity` if `email` or `password` is missing.

### Get Profile

```http
GET /profile/1
```

Header required: yes.

Purpose: retrieves a user profile by ID through the Auth Service.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `404 Not Found` if the user ID does not exist.

### Geocode City

```http
GET /geocode?city=Tokyo
```

Header required: yes.

Purpose: calls the Maps Service, which uses Open-Meteo Geocoding to find location data for a city.

Expected data includes city, country, latitude, and longitude.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if `city` is missing.
- `502 Bad Gateway` if the external geocoding API or maps service is unavailable.

### Weather By City

```http
GET /weather?city=Tokyo
```

Header required: yes.

Purpose: the gateway first gets coordinates from the Maps Service, then sends the coordinates to the Weather Service for Open-Meteo forecast data.

Expected data includes location and weather information.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if `city` is missing.
- `502 Bad Gateway` if the maps or weather service is unavailable.

### Country Information

```http
GET /country?country=Japan
```

Header required: yes.

Purpose: calls REST Countries through the gateway to get country metadata.

Expected data includes country name, capital, region, population, currency code, and flag URL when available.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if both `country` and `country_code` are missing.
- `404 Not Found` or `502 Bad Gateway` if the country cannot be resolved by the external API.

### Currency Conversion

```http
GET /currency?base=PHP&quote=USD&amount=100
```

Header required: yes.

Purpose: calls Frankfurter through the gateway to convert a currency amount.

Expected data includes base currency, quote currency, amount, exchange rate, and converted amount.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if `base` or `quote` is missing.
- `400 Bad Request` if the currency code is not supported by the external API.

### Travel Guide

```http
GET /travel-guide?topic=Tokyo
```

Header required: yes.

Purpose: calls Wikipedia REST through the gateway to get a short topic summary.

Expected data includes title, extract or summary, and source URL when available.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if `topic` is missing.
- `404 Not Found` if Wikipedia has no matching page.

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

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if `city` is missing.
- `partial_error` if one external service fails but other services still return data.

### List Hotels

```http
GET /hotels?city=Tokyo
```

Header required: yes.

Purpose: retrieves hotel records through the Hotels Service.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `502 Bad Gateway` if the Hotels Service is unavailable.

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

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if `hotel_name`, `city`, `address`, `price_per_night`, or `rating` is missing or invalid.

### Get Hotel

```http
GET /hotels/1
```

Header required: yes.

Purpose: retrieves one hotel record by ID through the Hotels Service.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `404 Not Found` if the hotel ID does not exist.

### Update Hotel

```http
PUT /hotels/1
```

Header required: yes.

Body:

```json
{
  "hotel_name": "Tokyo Grand Hotel",
  "city": "Tokyo",
  "address": "Updated Address, Tokyo",
  "price_per_night": 130.00,
  "rating": 4.8
}
```

Purpose: updates one hotel record through the Hotels Service.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `404 Not Found` if the hotel ID does not exist.
- `422 Unprocessable Entity` if required fields are missing or invalid.

### Delete Hotel

```http
DELETE /hotels/1
```

Header required: yes.

Purpose: deletes one hotel record through the Hotels Service.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `404 Not Found` if the hotel ID does not exist.

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

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `422 Unprocessable Entity` if `user_id`, `hotel_id`, `check_in`, `check_out`, or `total_amount` is missing or invalid.

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

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `404 Not Found` if the booking ID does not exist.
- `422 Unprocessable Entity` if `booking_id` or `amount` is missing or invalid.

### List Bookings

```http
GET /bookings
```

Header required: yes.

Purpose: retrieves bookings and related payment data through the Payment Service.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `502 Bad Gateway` if the Payment Service is unavailable.

### Get Booking

```http
GET /bookings/1
```

Header required: yes.

Purpose: retrieves one booking and its payment data through the Payment Service.

Possible errors:

- `401 Unauthorized` if `X-API-KEY` is missing or invalid.
- `404 Not Found` if the booking ID does not exist.

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
12. `POST /hotels`
13. `GET /hotels/1`
14. `PUT /hotels/1`
15. `POST /booking`
16. `GET /bookings`
17. `GET /bookings/1`
18. `POST /payment`
19. `DELETE /hotels/1`

## Postman Variables

```text
baseUrl = https://bugbusters-api-gateway.onrender.com
gatewayKey = change-me-gateway-key
```

For local Docker testing only, replace `baseUrl` with `http://localhost:8000`. For instructor demo or hosted testing, use the Render API Gateway URL.

## Validation Checklist

- Health check returns success.
- Protected routes fail without `X-API-KEY`.
- Protected routes pass with the correct `X-API-KEY`.
- Geocode returns location data from an external API.
- Weather returns forecast data through the gateway.
- Country, currency, and travel guide routes return external API data.
- Travel search returns aggregated data from multiple sources.
- Booking and payment routes save and return records.
- Intentional mistakes return documented error responses.
