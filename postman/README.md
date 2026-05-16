# Postman Testing

Import `Collection.json` into Postman.

## Variables

Set these collection variables:

```text
baseUrl = http://localhost:8000
gatewayKey = change-me-gateway-key
```

For hosted testing, replace `baseUrl` with the deployed API Gateway URL.

## Main Folder To Check

Use the folder named:

```text
External Services Through Gateway
```

This folder shows the gateway calling external services for:

- Geocode
- Weather
- Country
- Currency
- Travel Guide
- Travel Search Aggregation

## Run Order

1. `System / Health Check`
2. `Auth / Register`
3. `Auth / Login`
4. `External Services Through Gateway / Geocode`
5. `External Services Through Gateway / Weather`
6. `External Services Through Gateway / Country`
7. `External Services Through Gateway / Currency`
8. `External Services Through Gateway / Travel Guide`
9. `External Services Through Gateway / Travel Search Aggregation`
10. `Hotels / List Hotels`
11. `Booking / Create Booking`
12. `Booking / Make Payment`
13. `Booking / List Bookings`

Protected routes require:

```http
X-API-KEY: {{gatewayKey}}
```
