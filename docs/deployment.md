# Deployment

## Required Services

- api-gateway
- auth-service
- weather-service
- maps-service
- hotels-service
- payment-service

## Required Databases

- travel_auth
- travel_hotels
- travel_payment

## Environment Values

- Gateway API key
- One internal secret per service
- Service hostnames instead of localhost
- External API base URLs

## Suggested Local Ports

- 8000 api-gateway
- 8001 auth-service
- 8002 weather-service
- 8003 maps-service
- 8004 hotels-service
- 8005 payment-service

## Production Notes

- Route client traffic through the gateway only.
- Keep internal service secrets private.
- Add live URLs and screenshots after deployment.
- Use container hostnames or deployment hostnames, not `localhost`.
- Start the stack with `docker compose up --build` from the repository root.
- For Render deployment steps, see `docs/render-deployment.md`.
