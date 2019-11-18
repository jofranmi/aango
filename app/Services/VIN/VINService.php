<?php

namespace App\Services\VIN;

use App\Models\Vehicle;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Collection;

class VINService
{
    /**
     * @var GuzzleClient
     */
    protected $guzzle;

    /**
     * @var Vehicle
     */
    protected $vehicle;

    /**
     * @var string
     */
    public $url;

    public function __construct(GuzzleClient $guzzle, Vehicle $vehicle)
    {
        $this->guzzle = $guzzle;
        $this->vehicle = $vehicle;
        $this->url = strval(env('VIN_DECODE_URL'));
    }

    /**
     * @param string $vin
     * @return array
     */
    public function decodeVIN(string $vin)
    {
        $request = $this->transformVINResponse($this->guzzle->get(
            'https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvalues/' . $vin,
            [
                'query' => [
                    'format' => 'json',
                ]
            ]
        ));

        return $request;
    }

    /**
     * @param $response
     * @return array
     */
    public function transformVINResponse($response)
    {
        $array = collect(json_decode($response->getBody()->getContents()));
        $errorCode = $array['Results'][0]->ErrorCode;
        $vehicle = false;

        if ($errorCode == '0') {
            $vehicle = $this->verifyAndAddVehicleByDetails(
                $array['Results'][0]->ModelYear,
                $array['Results'][0]->Make,
                $array['Results'][0]->Model
            );
        }

        $values = [
            'vehicle' => $vehicle,
            'errorCode' => $errorCode,
            'errorResponse' => $array['Results'][0]->ErrorText,
        ];

        return $values;
    }

    /**
     * @param $year
     * @param $make
     * @param $model
     * @return Vehicle
     */
    public function verifyAndAddVehicleByDetails($year, $make, $model): Vehicle
    {
        return $this->vehicle->firstOrCreate([
                'year' => $year,
                'make' => $make,
                'model' => $model,
            ]);
    }
}
