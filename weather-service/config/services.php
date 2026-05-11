<?php

return [
    'open_meteo' => [
        'forecast_url' => rtrim(env('OPEN_METEO_FORECAST_URL', 'https://api.open-meteo.com/v1/forecast'), '/'),
    ],
];
