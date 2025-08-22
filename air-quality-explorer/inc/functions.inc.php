<?php

    function e($value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

// Create a function to generate header content
    function generateHeaders(): array
    {
        return ['headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-API-KEY' => getenv('OpenAQ_API_KEY'),
        ]];
    }

    function generateGetRequest($client, $path)
    {
        return $client->get($path, generateHeaders());
    }

    function generateResponseBody($response): array
    {
        return json_decode($response->getBody(), TRUE);
    }

    function startNewGuzzleClient(): GuzzleHttp\Client
    {
        return new GuzzleHttp\Client([
            'base_uri' => 'https://api.openaq.org/v1/',
        ]);
    }