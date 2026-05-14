# Render Deployment Guide

This project can run on Render as Docker web services. The API Gateway is the public entry point for Postman and frontend requests.

## Important Constraint

Render does not provide managed MySQL. The current app uses MySQL for:

- auth-service
- hotels-service
- payment-service

Use an external MySQL provider for those databases, or convert the app to PostgreSQL before using Render Postgres.

## Recommended Render Setup

Use Render web services for all six PHP services:

| Service | Dockerfile Path | Docker Context |
| --- | --- | --- |
| api-gateway | `api-gateway/Dockerfile` | `api-gateway` |
| auth-service | `services/auth-service/Dockerfile` | `services/auth-service` |
| weather-service | `services/weather-service/Dockerfile` | `services/weather-service` |
| maps-service | `services/maps-service/Dockerfile` | `services/maps-service` |
| hotels-service | `services/hotels-service/Dockerfile` | `services/hotels-service` |
| payment-service | `services/payment-service/Dockerfile` | `services/payment-service` |

Create the five downstream services first, then create the gateway after you know their Render URLs.

## Database Setup

Create three MySQL databases with your database provider:

- `travel_auth`
- `travel_hotels`
- `travel_payment`

Run these SQL files in the matching databases:

- `database/auth.sql`
- `database/hotels.sql`
- `database/sample_data.sql` for optional sample hotels
- `database/payment.sql`

## Render Service Settings

For each service:

- New > Web Service
- Connect the GitHub repo
- Runtime/Language: Docker
- Use the Dockerfile Path and Docker Context from the table above
- Keep all services in the same Render region

Render provides the `PORT` variable automatically. The Dockerfiles already use it and default to `8000` for local Docker.

## Downstream Service Env Vars

Set these for `auth-service`:

```text
APP_NAME=Auth Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-auth-service.onrender.com
DB_CONNECTION=mysql
DB_HOST=your-mysql-host
DB_PORT=3306
DB_DATABASE=travel_auth
DB_USERNAME=your-mysql-user
DB_PASSWORD=your-mysql-password
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
ACCEPTED_SECRETS=change-me-auth-secret
```

Set the same DB values for `hotels-service`, but use:

```text
APP_NAME=Hotels Service
APP_URL=https://your-hotels-service.onrender.com
DB_DATABASE=travel_hotels
ACCEPTED_SECRETS=change-me-hotels-secret
```

Set the same DB values for `payment-service`, but use:

```text
APP_NAME=Payment Service
APP_URL=https://your-payment-service.onrender.com
DB_DATABASE=travel_payment
ACCEPTED_SECRETS=change-me-payment-secret
```

Set these for `weather-service`:

```text
APP_NAME=Weather Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-weather-service.onrender.com
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
OPEN_METEO_FORECAST_URL=https://api.open-meteo.com/v1/forecast
ACCEPTED_SECRETS=change-me-weather-secret
```

Set these for `maps-service`:

```text
APP_NAME=Maps Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-maps-service.onrender.com
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
OPEN_METEO_GEOCODING_URL=https://geocoding-api.open-meteo.com/v1/search
ACCEPTED_SECRETS=change-me-maps-secret
```

## Gateway Env Vars

Set these after the five downstream services are deployed:

```text
APP_NAME=Travel API Gateway
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-api-gateway.onrender.com
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

GATEWAY_API_KEY=change-me-gateway-key
AUTH_SERVICE_URL=https://your-auth-service.onrender.com
AUTH_SERVICE_SECRET=change-me-auth-secret
WEATHER_SERVICE_URL=https://your-weather-service.onrender.com
WEATHER_SERVICE_SECRET=change-me-weather-secret
MAPS_SERVICE_URL=https://your-maps-service.onrender.com
MAPS_SERVICE_SECRET=change-me-maps-secret
HOTELS_SERVICE_URL=https://your-hotels-service.onrender.com
HOTELS_SERVICE_SECRET=change-me-hotels-secret
PAYMENT_SERVICE_URL=https://your-payment-service.onrender.com
PAYMENT_SERVICE_SECRET=change-me-payment-secret

REST_COUNTRIES_URL=https://restcountries.com/v3.1
FRANKFURTER_URL=https://api.frankfurter.dev/v1
WIKIPEDIA_REST_URL=https://en.wikipedia.org/api/rest_v1
```

## Postman Configuration

In Postman, set:

```text
baseUrl = https://your-api-gateway.onrender.com
gatewayKey = change-me-gateway-key
```

Public client requests should go through the gateway only, not the downstream service URLs.

## Verification

After deployment, test:

```text
GET {{baseUrl}}/health
GET {{baseUrl}}/geocode?city=Tokyo
POST {{baseUrl}}/register
GET {{baseUrl}}/travel-search?city=Tokyo
POST {{baseUrl}}/booking
POST {{baseUrl}}/payment
```

Protected routes must include:

```text
X-API-KEY: change-me-gateway-key
```

If a downstream service URL is opened directly without `X-Internal-Service-Key`, it should return `401`.

## More Secure Paid Option

For a stricter production setup, make the five downstream services Render Private Services and keep only `api-gateway` as a public Web Service. This better matches the architecture, but Render private services are not available on the free instance type.
