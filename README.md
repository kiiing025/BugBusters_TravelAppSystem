# BugBusters Travel App System

BugBusters is a microservice-based travel system built around a secured API Gateway. The gateway is the only public entry point; it applies the public `X-API-KEY` check and forwards requests to the downstream services with internal service secrets.

The five backend microservices live together under the `services/` folder.

## Services

- `api-gateway` - public entry point, security, and travel aggregation
- `auth-service` - user registration, login, and profile data
- `weather-service` - Open-Meteo forecast integration
- `maps-service` - Open-Meteo geocoding integration
- `hotels-service` - hotel catalog and maintenance APIs
- `payment-service` - booking and payment APIs

## External APIs

- Open-Meteo Geocoding API
- Open-Meteo Weather Forecast API
- REST Countries API
- Frankfurter currency API
- Wikipedia REST API

## Security

- Public requests must include `X-API-KEY` when calling the gateway.
- Internal service calls use `X-Internal-Service-Key` automatically from the gateway.
- Direct access to the downstream microservices is blocked unless the shared secret is present.

## Core Gateway Routes

- `GET /health`
- `POST /register`
- `POST /login`
- `GET /profile/{id}`
- `GET /weather?city=Tokyo`
- `GET /geocode?city=Tokyo`
- `GET /country?country=Japan`
- `GET /currency?base=PHP&quote=USD&amount=100`
- `GET /travel-guide?topic=Tokyo`
- `GET /travel-search?city=Tokyo`
- `GET /hotels?city=Tokyo`
- `POST /hotels`
- `GET /hotels/{id}`
- `PUT /hotels/{id}`
- `DELETE /hotels/{id}`
- `POST /booking`
- `POST /payment`
- `GET /bookings`
- `GET /bookings/{id}`

## Environment

Use the service-specific `.env.example` files as the starting point. The gateway points to service names instead of localhost, so it is ready for Docker, container hosting, or any deployment platform that resolves those hostnames.

## Quick Start

1. Configure the `.env` file for each service.
2. Create the MySQL databases used by the auth, hotels, and payment services.
3. Run `docker compose up --build` from the repository root, or run each service on its configured port.
4. Call the gateway, not the downstream services, for normal client traffic.

## Group Work

The project is structured for a 5-member group. A clean split is:

1. Gateway and security
2. Auth service
3. Weather and geocoding
4. Hotels and booking flow
5. Payment and external travel enrichment
