# BugBusters Travel App System

This repository contains a microservice-based Travel App System. The client should call the **API Gateway** only; the gateway applies API-key security and forwards requests to the separate backend services.

## Services

1. `api-gateway` - secured single entry point and route aggregator
2. `auth-service` - registration, login, and profile APIs
3. `weather-service` - external weather API integration
4. `maps-service` - external geocoding/maps API integration
5. `hotels-service` - hotel CRUD APIs
6. `payment-service` - booking and payment APIs

## Gateway Security

All gateway business routes require an API key:

```http
X-API-KEY: change-me-gateway-key
```

The root gateway route (`GET /`) is public so deployment tools can verify that the gateway is online.

## Important Routes

- `POST /register`
- `POST /login`
- `GET /profile/{id}`
- `GET /weather?city=Davao`
- `GET /geocode?city=Davao`
- `GET /travel-search?city=Davao` - calls maps, weather, and hotels services in one gateway controller action
- `GET /hotels?city=Davao`
- `POST /booking`
- `POST /payment`

## Configuration

Do not hardcode loopback URLs in code. Each service uses `.env` values and service-specific config files. The default examples use network service names such as `http://auth-service:8000`, which can be mapped by Docker Compose, Kubernetes, or a hosted deployment platform.
