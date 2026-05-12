<?php

return [
    'open_meteo' => [
        'geocoding_url' => rtrim(env('OPEN_METEO_GEOCODING_URL', 'https://geocoding-api.open-meteo.com/v1/search'), '/'),
    ],
];
