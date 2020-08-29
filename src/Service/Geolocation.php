<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\GreenSpace;
use Exception;

class Geolocation
{
    private string $googleMapUrl;
    private string $googleApiKey;

    public function __construct(string $googleMapUrl, string $googleApiKey)
    {
        $this->googleMapUrl = $googleMapUrl;
        $this->googleApiKey = $googleApiKey;
    }

    public function generateCoordinates(GreenSpace $greenSpace): array
    {
        try {
            $json = json_decode($this->getGeocodeJson(
                $this->googleMapUrl,
                $greenSpace->getAddress(),
                $this->googleApiKey
            ));
        } catch (Exception $exception) {
            return ['lat' => 0, 'long' => 0];
        }

        $lat  = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        return compact('lat', 'long');
    }

    public function getGeocodeJson(string $url, string $address, string $apiKey): string
    {
        $address = str_replace(" ", "+", $address);
        return file_get_contents("${url}?address=${address}&sensor=false&key=${apiKey}");
    }
}
