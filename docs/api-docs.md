# API Documentation

## Auth Service
- POST /register
- POST /login
- GET /profile

## Weather Service
- GET /weather?city=

## Maps Service
- GET /maps?location=

## Hotels Service
- GET /hotels
- GET /hotels/{id}
- GET /hotels?city=

## Payment Service
- POST /booking
- POST /payment

## API Gateway
- /api/auth/*
- /api/weather
- /api/maps
- /api/hotels
- /api/payment

## Standard Response Format
```json
{
  "status": "success",
  "message": "...",
  "data": {}
}
```
