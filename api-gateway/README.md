# API Gateway

This service is the only public entry point for the travel system.

## Public Rules

- Send `X-API-KEY` on every protected request.
- Use `GET /health` for a public health check.
- Call the gateway instead of the downstream services directly.

## Internal Routing

The gateway forwards requests to:

- `auth-service`
- `weather-service`
- `maps-service`
- `hotels-service`
- `payment-service`

It also talks to these external APIs:

- Open-Meteo Geocoding API
- Open-Meteo Weather Forecast API
- REST Countries API
- Frankfurter currency API
- Wikipedia REST API

## Main Endpoints

- `POST /register`
- `POST /login`
- `GET /profile/{id}`
- `GET /weather`
- `GET /geocode`
- `GET /country`
- `GET /currency`
- `GET /travel-guide`
- `GET /travel-search`
- `GET /hotels`
- `POST /hotels`
- `GET /hotels/{id}`
- `PUT /hotels/{id}`
- `DELETE /hotels/{id}`
- `POST /booking`
- `POST /payment`
- `GET /bookings`
- `GET /bookings/{id}`

## Notes

- Downstream services require `X-Internal-Service-Key`.
- The gateway keeps all client-facing security in one place.
- All service URLs are configured in `config/services.php`.
