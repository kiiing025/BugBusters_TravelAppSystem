# Render Environment Variables

Use these values when creating each Render web service. In Render, put the text before `=` in the left box and the text after `=` in the right box.

Do not paste your real Aiven password into GitHub or screenshots. Only enter it directly in Render.

For `auth-service`, `hotels-service`, and `payment-service`, add this Render secret file:

```text
Filename: aiven-ca.pem
Content: paste the full contents of the downloaded Aiven CA certificate
```

## auth-service

Service name:

```text
bugbusters-auth-service
```

Build settings:

```text
Root Directory = services/auth-service
Dockerfile Path = Dockerfile
```

Environment variables:

```text
APP_NAME=Auth Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bugbusters-auth-service.onrender.com
APP_TIMEZONE=UTC
LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=bugbusters-travel-mysql-bugbusters-4a8d.c.aivencloud.com
DB_PORT=19229
DB_DATABASE=travel_auth
DB_USERNAME=avnadmin
DB_PASSWORD=PASTE_AIVEN_PASSWORD_HERE
DB_SSL_CA=/etc/secrets/aiven-ca.pem
DB_SSL_VERIFY_SERVER_CERT=true
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
ACCEPTED_SECRETS=change-me-auth-secret
```

## hotels-service

Service name:

```text
bugbusters-hotels-service
```

Build settings:

```text
Root Directory = services/hotels-service
Dockerfile Path = Dockerfile
```

Environment variables:

```text
APP_NAME=Hotels Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bugbusters-hotels-service.onrender.com
APP_TIMEZONE=UTC
LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=bugbusters-travel-mysql-bugbusters-4a8d.c.aivencloud.com
DB_PORT=19229
DB_DATABASE=travel_hotels
DB_USERNAME=avnadmin
DB_PASSWORD=PASTE_AIVEN_PASSWORD_HERE
DB_SSL_CA=/etc/secrets/aiven-ca.pem
DB_SSL_VERIFY_SERVER_CERT=true
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
ACCEPTED_SECRETS=change-me-hotels-secret
```

## payment-service

Service name:

```text
bugbusters-payment-service
```

Build settings:

```text
Root Directory = services/payment-service
Dockerfile Path = Dockerfile
```

Environment variables:

```text
APP_NAME=Payment Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bugbusters-payment-service.onrender.com
APP_TIMEZONE=UTC
LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=bugbusters-travel-mysql-bugbusters-4a8d.c.aivencloud.com
DB_PORT=19229
DB_DATABASE=travel_payment
DB_USERNAME=avnadmin
DB_PASSWORD=PASTE_AIVEN_PASSWORD_HERE
DB_SSL_CA=/etc/secrets/aiven-ca.pem
DB_SSL_VERIFY_SERVER_CERT=true
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
ACCEPTED_SECRETS=change-me-payment-secret
```

## weather-service

Service name:

```text
bugbusters-weather-service
```

Build settings:

```text
Root Directory = services/weather-service
Dockerfile Path = Dockerfile
```

Environment variables:

```text
APP_NAME=Weather Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bugbusters-weather-service.onrender.com
APP_TIMEZONE=UTC
LOG_CHANNEL=stack
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
OPEN_METEO_FORECAST_URL=https://api.open-meteo.com/v1/forecast
ACCEPTED_SECRETS=change-me-weather-secret
```

## maps-service

Service name:

```text
bugbusters-maps-service
```

Build settings:

```text
Root Directory = services/maps-service
Dockerfile Path = Dockerfile
```

Environment variables:

```text
APP_NAME=Maps Service
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bugbusters-maps-service.onrender.com
APP_TIMEZONE=UTC
LOG_CHANNEL=stack
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
OPEN_METEO_GEOCODING_URL=https://geocoding-api.open-meteo.com/v1/search
ACCEPTED_SECRETS=change-me-maps-secret
```

## api-gateway

Deploy this service last, after the five downstream service URLs exist.

Service name:

```text
bugbusters-api-gateway
```

Build settings:

```text
Root Directory = api-gateway
Dockerfile Path = Dockerfile
```

Environment variables:

```text
APP_NAME=Travel API Gateway
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bugbusters-api-gateway.onrender.com
APP_TIMEZONE=UTC
LOG_CHANNEL=stack
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SERVICE_CONNECT_TIMEOUT=20
SERVICE_TIMEOUT=90
GATEWAY_API_KEY=change-me-gateway-key
AUTH_SERVICE_URL=https://bugbusters-auth-service.onrender.com
AUTH_SERVICE_SECRET=change-me-auth-secret
WEATHER_SERVICE_URL=https://bugbusters-weather-service.onrender.com
WEATHER_SERVICE_SECRET=change-me-weather-secret
MAPS_SERVICE_URL=https://bugbusters-maps-service.onrender.com
MAPS_SERVICE_SECRET=change-me-maps-secret
HOTELS_SERVICE_URL=https://bugbusters-hotels-service.onrender.com
HOTELS_SERVICE_SECRET=change-me-hotels-secret
PAYMENT_SERVICE_URL=https://bugbusters-payment-service.onrender.com
PAYMENT_SERVICE_SECRET=change-me-payment-secret
REST_COUNTRIES_URL=https://restcountries.com/v3.1
FRANKFURTER_URL=https://api.frankfurter.dev/v1
WIKIPEDIA_REST_URL=https://en.wikipedia.org/api/rest_v1
```

For Postman after deployment:

```text
baseUrl=https://bugbusters-api-gateway.onrender.com
gatewayKey=change-me-gateway-key
```
