<?php

namespace App\Services\ServiceLocation;

use App\Models\ServiceLocation;

/**
 * Class ServiceLocationService
 * @package App\Services\ServiceLocation
 */
class ServiceLocationService
{
    /**
     * @var ServiceLocation $serviceLocation
     */
    protected $serviceLocation;

    public function __construct(ServiceLocation $serviceLocation)
    {
        $this->serviceLocation = $serviceLocation;
    }

    /**
     * @param array $serviceLocation
     * @return ServiceLocation
     */
    public function getOrCreateServiceLocation(array $serviceLocation): ServiceLocation
    {
        return $this->serviceLocation
            ->firstOrCreate([
                'name' => $serviceLocation['name'],
                'address' => $serviceLocation['address'],
                'address2' => $serviceLocation['address'],
                'city' => $serviceLocation['city'],
                'state' => $serviceLocation['state'],
                'zip_code' => $serviceLocation['zip_code'],
                'phone' => $serviceLocation['phone'],
            ]);
    }
}
