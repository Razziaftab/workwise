<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use HttpException;

class AgifyAPI
{
    const AGIFY_API_URL = 'https://api.agify.io';

    /**
     * @throws HttpException
     * @throws GuzzleException
     */
    public function getEstimatedAge(string $name)
    {
        try {

            $client = new Client();

            $query = [
                'name' => $name,
            ];

            return $client->request('GET', self::AGIFY_API_URL, [
                'query' => $query
            ]);
        } catch (Exception $e) {
            return throw new HttpException(500, $e->getMessage());
        }
    }
}
