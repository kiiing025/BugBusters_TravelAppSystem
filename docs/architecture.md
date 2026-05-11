# Architecture

Client -> API Gateway -> Auth / Weather / Maps / Hotels / Payment

## API Gateway Responsibilities

- Acts as the single client entry point.
- Protects gateway routes with `X-API-KEY` API-key authentication.
- Loads downstream service URLs from `api-gateway/config/services.php`.
- Keeps downstream API calls in separate service classes under `api-gateway/app/Services`.
- Provides `GET /travel-search`, where one gateway controller action calls Maps, Weather, and Hotels services.

## Services

- Auth Service: local database-backed user registration, login, and profile APIs.
- Weather Service: external Open-Meteo weather API integration.
- Maps Service: external Open-Meteo geocoding API integration.
- Hotels Service: local database-backed hotel APIs.
- Payment Service: local database-backed booking and simulated payment APIs.

## Deployment Notes

Use environment variables for service discovery. The examples avoid hardcoded loopback URLs and use deployable hostnames such as `http://auth-service:8000`.
