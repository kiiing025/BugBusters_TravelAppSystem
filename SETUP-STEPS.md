# STEP-BY-STEP SETUP GUIDE

## STEP 1 - Extract the folder
Download the zip and extract it.
Put it in Desktop or Documents.

## STEP 2 - Open in VS Code
In VS Code:
- File
- Open Folder
- choose `travel-app-project-template`

## STEP 3 - Create GitHub repo
Create a new empty repository in GitHub.
Suggested name: `travel-app-api-gateway`

Then in terminal inside the project folder:
```bash
git init
git add .
git commit -m "Initial project scaffold"
git branch -M main
git remote add origin YOUR_GITHUB_REPO_URL
git push -u origin main
```

## STEP 4 - Create the real Lumen app inside each service folder
Important:
This template gives you the folder structure and starter files.
You still need to install Lumen in each service folder.

Run these commands one by one from the project root:

```bash
composer create-project laravel/lumen auth-service-temp
composer create-project laravel/lumen weather-service-temp
composer create-project laravel/lumen maps-service-temp
composer create-project laravel/lumen hotels-service-temp
composer create-project laravel/lumen payment-service-temp
composer create-project laravel/lumen api-gateway-temp
```

Then copy the contents of each `*-temp` folder into the matching service folder:
- auth-service-temp -> auth-service
- weather-service-temp -> weather-service
- maps-service-temp -> maps-service
- hotels-service-temp -> hotels-service
- payment-service-temp -> payment-service
- api-gateway-temp -> api-gateway

After copying, delete the `*-temp` folders.

## STEP 5 - Set your `.env` files
For each service, create `.env` based on `.env.example` and set:
- app name
- app env
- app key if needed
- port
- DB database
- DB username
- DB password
- API keys for weather/maps if needed

Suggested ports:
- auth-service: 8001
- weather-service: 8002
- maps-service: 8003
- hotels-service: 8004
- payment-service: 8005
- api-gateway: 8000

## STEP 6 - Import SQL files
Use MySQL or phpMyAdmin.
Create databases first, then import:
- `database/auth.sql`
- `database/hotels.sql`
- `database/payment.sql`
- `database/sample_data.sql`

Suggested databases:
- travel_auth
- travel_hotels
- travel_payment

## STEP 7 - Build services in this order
1. Auth
2. Weather
3. Maps
4. Hotels
5. Payment
6. API Gateway

Why this order:
- Auth is needed early for protected routes.
- Weather and Maps are simple integrations.
- Hotels and Payment depend more on your local DB logic.
- Gateway is best after services already work.

## STEP 8 - Test each service separately
Use Postman.
Do not start with the gateway.
Test direct service routes first.

## STEP 9 - Connect the API Gateway
After all services work, make gateway routes:
- `/api/auth/*`
- `/api/weather`
- `/api/maps`
- `/api/hotels`
- `/api/payment`

## STEP 10 - Document everything
Update:
- `docs/api-docs.md`
- `docs/architecture.md`
- `docs/deployment.md`
- `README.md`

## STEP 11 - Export Postman collection
Save it into:
- `postman/collection.json`

## STEP 12 - Deploy
Deploy each service and the gateway.
Then change your environment variables to point to the live URLs.
