# Figma Mockup Assets

`travel-app-modern-ui-mockup.svg` is the recommended modern version. It uses a mobile travel-app style with large destination visuals, rounded cards, soft shadows, and bottom navigation.

`travel-app-website-ui-mockup.svg` is the desktop website version. Use this if your instructor wants a website mockup rather than a mobile app mockup. It includes five website pages: Dashboard, Destination Search, Weather & Map, Hotels, and Booking & Payment.

`travel-app-ui-mockup.svg` is the earlier desktop-style version.

The mobile/app mockup files contain the 10 required screens for the travel app flow:

- Login
- Register
- Home Dashboard
- Search Destination
- Weather View
- Maps Result
- Hotel Listing
- Hotel Details
- Booking Payment
- Booking Confirmation

Import the SVG that matches your rubric into the Figma file. Use the modern mobile SVG for app screens, or use the website SVG for a desktop website mockup. The design is built as vector shapes and text so it can be edited after import.

The screens are aligned with the backend/API Gateway demo flow:

1. Auth service: register, login, profile
2. Gateway aggregation: destination search
3. Maps service: geocoding
4. Weather service: forecast
5. Hotels service: listing and details
6. Payment service: booking and payment

Use this explanation during defense:

> The user interacts with one secured API Gateway. The gateway checks `X-API-KEY`, forwards internal service secrets, calls the Auth, Maps, Weather, Hotels, and Payment services, and returns the combined travel experience to the frontend.
