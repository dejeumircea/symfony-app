<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use Predis\Client as PredisClient;
class ApiService
{
    public const CACHE_KEY = 'star_wars';

    public function __construct(private readonly Client $client, private readonly PredisClient $redisClient)
    {
    }

    public function getApiData(string $url, string $method = 'GET'): array
    {
        return json_decode($this->client->request($method, $url)->getBody()->getContents(), true);
    }

    public function getDisplayData(): array
    {
        $data = $this->redisClient->get(self::CACHE_KEY);
        return  $data ? json_decode($data, true) :  $this->normalizeData();
    }
    public function normalizeData(): array
    {
        $results = $this->getApiData('https://swapi.dev/api/people/');

        $values = [];
        foreach ($results['results'] as $key => $result) {
            $values[$key]['name'] =  $result['name'];
            $values[$key]['gender'] =  $result['gender'];
            $values[$key]['home_planet'] = $this->getApiData($result['homeworld'])['name'];
        }

        $this->redisClient->set(self::CACHE_KEY, json_encode($values),'EX', 10);

        return $values;
    }
}
