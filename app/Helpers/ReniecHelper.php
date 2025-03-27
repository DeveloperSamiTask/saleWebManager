<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ReniecHelper
{
    public static function consultarDni($dni)
    {
        $token = 'apis-token-12833.TGdxW2oVrCooXhQnGPaj48mCEICakWTY';
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://api.apis.net.pe/v2/reniec/dni', [
                'headers' => [
                    'Referer' => 'https://apis.net.pe/consulta-dni-api',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => ['numero' => $dni], // Pasar el DNI como query param
                'timeout' => 5, // Tiempo máximo de espera en segundos
            ]);

            $persona = json_decode($response->getBody(), true);

            if (!$persona || isset($persona['error'])) {
                return ['error' => 'Datos no encontrados o inválidos'];
            }

            return [
                'success' => true,
                'first_name' => $persona['nombres'] ?? '',
                'last_name' => $persona['apellidoPaterno'] ?? '',
                'mother_last_name' => $persona['apellidoMaterno'] ?? ''
            ];
        } catch (RequestException $e) {
            return ['error' => 'Error al conectar con la API: ' . $e->getMessage()];
        }
    }
}
