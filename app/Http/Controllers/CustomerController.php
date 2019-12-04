<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * @var Customer $customer
     */
    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $customers = $this->customer->all();

        return view('customer.index')
            ->with('customers', $customers)
            ->with('states', json_encode(config('aango.states')));
    }
}
