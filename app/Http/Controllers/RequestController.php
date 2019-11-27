<?php

namespace App\Http\Controllers;

use App\Events\Customer\CreateCustomerEvent;
use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Events\User\CreateUserEvent;
use App\Mail\user\UserCreateMail;
use App\Mail\User\UserPasswordChangeMail;
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
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Hash;

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
     * @var Mailer $mailer
     */
    protected $mailer;

    /**
     * @var Order $order
     */
    protected $order;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var UserPasswordChangeMail $userPasswordChange
     */
    protected $userPasswordChangeMail;

    /**
     * @var VINService $vinService
     */
    protected $vinService;

    /**
     * RequestController constructor.
     * @param Customer $customer
     * @param ItemService $itemService
     * @param Mailer $mailer
     * @param Order $order
     * @param User $user
     * @param UserPasswordChangeMail $userPasswordChangeMail
     * @param VINService $vinService
     */
    public function __construct(Customer $customer, ItemService $itemService, Mailer $mailer, Order $order, User $user, UserPasswordChangeMail $userPasswordChangeMail, VINService $vinService)
    {
        $this->customer = $customer;
        $this->itemService = $itemService;
        $this->mailer = $mailer;
        $this->order = $order;
        $this->user = $user;
        $this->userPasswordChangeMail = $userPasswordChangeMail;
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
        event(new CreateOrderEvent(null, $request->key, $request->vehicle, $request->vin));
    }

    /**
     * @param Request $request
     */
    public function createCustomer(Request $request)
    {
        event(new NotificationEvent('Customer create request has been sent and will be processed shortly', 'alert-info'));
        event(new CreateCustomerEvent($request));
    }

    /**
     * @param Request $request
     */
    public function createUser(Request $request)
    {
        event(new NotificationEvent('User create request has been sent and will be processed shortly', 'alert-info'));
        event(new CreateUserEvent($request));
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

        if ($customer->save()) {
            event(new NotificationEvent('Customer information has been updated', 'alert-success'));
        } else {
            event(new NotificationEvent('There was an error updating the customer information', 'alert-danger'));
        }

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

        if ($update) {
            event(new NotificationEvent('User has been removed from the customer', 'alert-success'));
        } else {
            event(new NotificationEvent('There was an error removing the user from the customer', 'alert-danger'));
        }

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

        if ($user->update(['customer_id' => $customer->id])) {
            event(new NotificationEvent('User has been added to customer', 'alert-success'));
        } else {
            event(new NotificationEvent('There was an error adding the user to the customer', 'alert-danger'));
        }

        return response($user);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function getUser(Request $request)
    {
        $user = $this->user
            ->with('customer')
            ->find($request->id);

        return response($user);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function editUser(Request $request)
    {
        $password = $request->password;
        $user = $this->user->find($request->id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($password != '') {
            $user->password = Hash::make($password);
        }

        if ($user->save()) {
            event(new NotificationEvent('User information has been updated', 'alert-success'));

            if ($password != '') {
                $this->userPasswordChangeMail->setPassword($password);
                $this->mailer
                    ->to($user->email)
                    ->send($this->userPasswordChangeMail);
            }
        } else {
            event(new NotificationEvent('There was an error updating the user information', 'alert-danger'));
        }

        return response($user);
    }
}
