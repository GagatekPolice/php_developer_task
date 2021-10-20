<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\HereapiClient;

use Shoper\Recruitment\Task\Constants\ApiConstants;
use Shoper\Recruitment\Task\Entity\Destination;
use Shoper\Recruitment\Task\Entity\Headquarter;
use Shoper\Recruitment\Task\Request\QueryParameter;

class HereapiClient
{
    /** @var string wartość klucza używana do autoryzacji przez api */
    private $apiKey;

    /** @var string nazwa hosta pod którym znajduje się API */
    private $hostname;

    public function __construct(string $hostname, string $apiKey)
    {
        $this->hostname = $hostname;
        $this->apiKey = $apiKey;
    }

    public function getDistance(string $transportMode, Headquarter $headquarter, Destination $destination)
    {
         $response =$this->send(
            'routes',
            [
                new QueryParameter('transportMode', $transportMode),
                new QueryParameter('origin', $headquarter->getCoordinates()),
                new QueryParameter('destination', $destination->getCoordinates()),
                new QueryParameter('return', 'summary'),
            ],
            ApiConstants::METHOD_GET
        );
        return json_decode($response, true)['routes'][0]['sections'][0]['summary']['length'];
    }

    private function generateUrl(string $path, ?array $QueryParameters): string
    {
        $url = $this->hostname . $path;
        $url .= isset($QueryParameters) ? '?' : '';
        
        foreach ($QueryParameters as $parmeter) {
            $url .= substr($url, -1) === '?'
                ? $parmeter->getFormatedQueryParameter()
                : '&' . $parmeter->getFormatedQueryParameter();
        }

        $url .=  $this->apiKey 
            ? (substr($path, -1) === substr($url, -1)
                ? "apiKey={$this->apiKey}"
                : "&apiKey={$this->apiKey}"
                )
            : '';

        return $url;
    }

    private function send(string $path, array $QueryParameters, string $method)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
        CURLOPT_URL => $this->generateUrl($path, $QueryParameters),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        ]);
        
        $response = curl_exec($curl);   

        if (curl_errno($curl)) {
            throw new \Exception('Curl error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
            case 200:  
                return $response;
              break;
            case 400:
                throw new \Exception('HereApi validation  error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
            default:
                throw new \Exception('Unexpected HereApi HTTP code response: ' . $http_code, ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
          }
    }
}