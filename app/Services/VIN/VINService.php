<?php

namespace App\Services\VIN;

use App\Models\Vehicle;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Collection;

/**
 * Class VINService
 * @package App\Services\VIN
 */
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
    public function decodeVIN(string $vin): array
    {
    	$sequence = $this->transformVinToSequence($vin);

    	$vehicle = $this->vehicle
			->where('vin_sequence', 'LIKE', '%'. $sequence . '%')
			->first();

    	$request = [
    		'errorCode' => '0',
    		'errorResponse' => 'Vehicle type was already saved',
			'vehicle' => $vehicle,
		];

    	if (!$vehicle) {
			$request = $this->transformVINResponse($this->guzzle->get(
				'https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvalues/' . $vin,
				[
					'query' => [
						'format' => 'json',
					]
				]
			));
		}

        return $request;
    }

    /**
     * @param $response
     * @return array
     */
    public function transformVINResponse($response): array
    {
        $array = collect(json_decode($response->getBody()->getContents()));
        $errorCode = $array['Results'][0]->ErrorCode;
        $vehicle = false;

        if ($errorCode == '0') {
            $vehicle = $this->verifyAndAddVehicleByDetails(
                $array['Results'][0]->ModelYear,
                $array['Results'][0]->Make,
                $array['Results'][0]->Model,
                $array['Results'][0]->VIN
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
	 * @param $vin
	 * @return Vehicle
	 */
    public function verifyAndAddVehicleByDetails($year, $make, $model, $vin): Vehicle
    {
        return $this->vehicle->firstOrCreate([
        	'year' => $year,
			'make' => $make,
			'model' => $model,
			'vin_sequence' => $this->transformVinToSequence($vin),
		]);
    }

	/**
	 * Gets the first 11 digits of a vin that contain make, model and year
	 * Gets the first 8 digits
	 * Ignores the 9th digit as it is only to check for integrity
	 * Gets the last 2 digits from the 11 digit string
	 * Combines them and makes a 10 digit string which is just the 11 digit string without the 9th check digit
	 * @param $vin
	 * @return string
	 */
    public function transformVinToSequence($vin)
	{
		$eleven = substr($vin, 0, 11);
		$eight = substr($vin, 0, 8);
		$two = substr($eleven, 9, 2);

		return ($eight . $two);
	}
}
