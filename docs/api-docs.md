# API Documentation

## Base URL

```text
http://api-gateway:8000
```

## Public Header

```http
X-API-KEY: your-gateway-key
```

## Public Routes

### Health

- `GET /health`

### Auth

- `POST /register`
- `POST /login`
- `GET /profile/{id}`

### Travel Data

- `GET /weather?city=Tokyo`
- `GET /geocode?city=Tokyo`
- `GET /country?country=Japan`
- `GET /currency?base=PHP&quote=USD&amount=100`
- `GET /travel-guide?topic=Tokyo`
- `GET /travel-search?city=Tokyo`

### Hotels

- `GET /hotels`
- `GET /hotels?city=Tokyo`
- `POST /hotels`
- `GET /hotels/{id}`
- `PUT /hotels/{id}`
- `DELETE /hotels/{id}`

### Bookings and Payments

- `POST /booking`
- `GET /bookings`
- `GET /bookings/{id}`
- `POST /payment`

## Internal Note

Downstream services are not meant to be called directly from the client. They expect the gateway's internal secret and are protected by middleware.
