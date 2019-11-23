<?php

namespace App\Http\Controllers;

use App\Events\Customer\CreateCustomerEvent;
use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Models\Customer;
use App\Models\Order;
use App\Models\UserType;
use App\Models\Vehicle;
use App\Services\Items\ItemService;
use App\Services\VIN\VINService;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * @var Customer $customer
     */
    protected $customer;

    /**
     * @var ItemService $itemService
     */
    protected $itemService;

    /**
     * @var Order $order
     */
    protected $order;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var VINService $vinService
     */
    protected $vinService;

    public function __construct(Customer $customer, ItemService $itemService, Order $order, User $user, VINService $vinService)
    {
        $this->customer = $customer;
        $this->itemService = $itemService;
        $this->order = $order;
        $this->user = $user;
        $this->vinService = $vinService;
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function getItemsFromVIN(Request $request)
    {
        $order = $this->order->where('vin', $request->vin)->first();
        $item = null;

        if ($order) {
            $decode['errorCode'] = 5000;
            $decode['errorResponse'] = 'There is already an order for this vehicle';
        } else {
            $decode = $this->vinService->decodeVIN($request->vin);

            if ($decode['vehicle'] instanceof Vehicle) {
                $item = $this->itemService->getItemForVehicle($decode['vehicle']);
            }

            if ($decode['errorCode'] == 0 && $item == null) {
                $decode['errorCode'] = 5000;
                $decode['errorResponse'] = 'There are no keys for ' . $decode['vehicle']->year . ' ' . $decode['vehicle']->make . ' ' . $decode['vehicle']->model;
            }
        }

        return response([
            'request' => $decode,
            'key' => $item,
            'vin' => $request->vin
        ]);
    }

    /**
     * @param Request $request
     */
    public function createOrder(Request $request)
    {
        event(new NotificationEvent('Order create request has been sent and will be processed shortly', 'alert-info'));
        event(new CreateOrderEvent(null, $request->key, $request->vehicle, $request->vin, Auth::user()));
    }

    /**
     * @param Request $request
     */
    public function createCustomer(Request $request)
    {
        event(new NotificationEvent('Customer create request has been sent and will be processed shortly', 'alert-info'));
        event(new CreateCustomerEvent($request));
    }

    public function editCustomer(Request $request)
    {
        $customer = $this->customer->find($request->id);

        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->zip_code = $request->zip_code;
        //$customer->phone = $request->phone;

        $customer->save();

        return response('Customer has been updated');
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function getCustomerWithUsers(Request $request)
    {
        $customer = $this->customer
            ->with('users')
            ->find($request->id);
        $users = $this->user
            ->where('user_types_id', UserType::CUSTOMER)
            ->where('customer_id', '!=', $customer->id)
            ->get();

        return response([
            'customer' => $customer,
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function removeUserFromCustomer(Request $request)
    {
        $update = $this->user
            ->find($request->id)
            ->update(['customer_id' => 0]);

        return response([$update]);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function addUserToCustomer(Request $request)
    {
        $user = $this->user
            ->find($request->user_id);
        $customer = $this->customer
            ->find($request->customer_id);

        $user->update(['customer_id' => $customer->id]);

        return response($user);
    }
}
