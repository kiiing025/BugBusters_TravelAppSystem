# API Documentation

## Base URL

```text
http://api-gateway:8000
```

All business endpoints are routed through the API Gateway. Send this header on each protected request:

```http
X-API-KEY: change-me-gateway-key
```

## Routes

### Auth

- `POST /register`
- `POST /login`
- `GET /profile/{id}`

### Travel Data

- `GET /weather?city=Davao`
- `GET /geocode?city=Davao`
- `GET /travel-search?city=Davao`

`/travel-search` is the gateway aggregation endpoint. It calls the Maps, Weather, and Hotels services and combines their responses.

### Hotels

- `GET /hotels`
- `GET /hotels?city=Davao`
- `POST /hotels`
- `GET /hotels/{id}`
- `PUT /hotels/{id}`
- `DELETE /hotels/{id}`

### Bookings and Payments

- `POST /booking`
- `GET /bookings`
- `GET /bookings/{id}`
- `POST /payment`
