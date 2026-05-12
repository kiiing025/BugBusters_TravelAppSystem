# Architecture

## Flow

Client -> API Gateway -> Internal Services -> External APIs

The five backend microservices are grouped inside `services/`.

## Gateway Responsibilities

- Enforces the public `X-API-KEY` header.
- Routes all client traffic through one entry point.
- Aggregates responses from several services in one controller action.
- Sends `X-Internal-Service-Key` to downstream services.

## Internal Services

- Auth Service: registration, login, and profile storage.
- Weather Service: Open-Meteo forecast lookup.
- Maps Service: Open-Meteo geocoding lookup.
- Hotels Service: hotel catalog CRUD.
- Payment Service: booking and payment CRUD.

## External APIs

- Open-Meteo Geocoding API
- Open-Meteo Weather Forecast API
- REST Countries API
- Frankfurter currency API
- Wikipedia REST API

## Security Model

- Public clients talk to the gateway only.
- The gateway is protected with `X-API-KEY`.
- The downstream services accept only the gateway's internal secret.
- Root and health routes stay public for service checks.

## Deployment Notes

- Use service-name URLs, not `localhost`.
- Configure the gateway and each service with its `.env.example`.
- Keep the MySQL databases separate for auth, hotels, and payment.
