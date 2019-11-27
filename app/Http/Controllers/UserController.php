<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\UserType;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @var Customer $customer
     */
    protected $customer;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var UserType $userType
     */
    protected $userType;

    /**
     * UserController constructor.
     * @param Customer $customer
     * @param User $user
     * @param UserType $userType
     */
    public function __construct(Customer $customer, User $user, UserType $userType)
    {
        $this->customer = $customer;
        $this->user = $user;
        $this->userType = $userType;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $users = null;
        $userTypes = null;
        $customers = null;

        if (Gate::forUser(Auth::user())->allows('admin')) {
            $users = $this->user
                //->withTrashed()
                ->with('customer')
                ->get();
            $userTypes = $this->userType->all();
            $customers = $this->customer->all();
        } else if (Gate::forUser(Auth::user())->allows('office')) {
            $users = $this->user
                ->customers()
                ->with('customer')
                ->get();
            $userTypes = $this->userType
                ->customer()
                ->all();
            $customers = $this->customer->all();
        }

        return view('user.index')
            ->with('customers', $customers)
            ->with('users', $users)
            ->with('userTypes', $userTypes);
    }
}
