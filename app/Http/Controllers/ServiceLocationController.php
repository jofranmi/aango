<?php

namespace App\Http\Controllers;

use App\Models\ServiceLocation;
use Illuminate\Contracts\Support\Renderable;

class ServiceLocationController extends Controller
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
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $locations = $this->serviceLocation->all();

        return view('service-location.index')
            ->with('locations', $locations)
            ->with('states', json_encode(config('aango.states')));
    }
}
