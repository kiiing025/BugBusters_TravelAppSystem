# Architecture

Client -> API Gateway -> Auth / Weather / Maps / Hotels / Payment

## Services
- Auth Service: local DB
- Weather Service: OpenWeather API
- Maps Service: Google Maps API
- Hotels Service: local DB or mock data
- Payment Service: local DB, simulated payments

## Notes
Use API Gateway as the single entry point for the client.
