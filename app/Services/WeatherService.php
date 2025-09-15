<?php

namespace App\Services;

use GuzzleHttp\Client;

class WeatherService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('WEATHER_API_KEY');
    }

    public function getWeather($city)
    {
        $response = $this->client->get("https://api.openweathermap.org/data/2.5/weather", [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric', // for Celsius, use 'imperial' for Fahrenheit
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
