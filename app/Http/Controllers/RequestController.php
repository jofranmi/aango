<?php

namespace App\Http\Controllers;

use App\Events\Customer\CreateCustomerEvent;
use App\Events\Item\CreateItemEvent;
use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Events\User\CreateUserEvent;
use App\Mail\User\UserPasswordChangeMail;
use App\Models\Customer;
use App\Models\Item;
use App\Models\ItemVehicle;
use App\Models\Order;
use App\Models\Status;
use App\Models\UserType;
use App\Models\Vehicle;
use App\Services\Items\ItemService;
use App\Services\VIN\VINService;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
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
	 * @var Item $item
	 */
    protected $item;

    /**
     * @var ItemService $itemService
     */
    protected $itemService;

	/**
	 * @var ItemVehicle $itemVehicle
	 */
    protected $itemVehicle;

    /**
     * @var Mailer $mailer
     */
    protected $mailer;

    /**
     * @var Order $order
     */
    protected $order;

	/**
	 * @var Status $status
	 */
    protected $status;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var UserPasswordChangeMail $userPasswordChange
     */
    protected $userPasswordChangeMail;

	/**
	 * @var Vehicle $vehicle
	 */
    protected $vehicle;

    /**
     * @var VINService $vinService
     */
    protected $vinService;

	/**
	 * RequestController constructor.
	 * @param Customer $customer
	 * @param Item $item
	 * @param ItemService $itemService
	 * @param ItemVehicle $itemVehicle
	 * @param Mailer $mailer
	 * @param Order $order
	 * @param Status $status
	 * @param User $user
	 * @param UserPasswordChangeMail $userPasswordChangeMail
	 * @param Vehicle $vehicle
	 * @param VINService $vinService
	 */
    public function __construct(Customer $customer, Item $item, ItemService $itemService, ItemVehicle $itemVehicle, Mailer $mailer, Order $order, Status $status, User $user, UserPasswordChangeMail $userPasswordChangeMail, Vehicle $vehicle, VINService $vinService)
    {
        $this->customer = $customer;
        $this->item = $item;
        $this->itemService = $itemService;
        $this->itemVehicle = $itemVehicle;
        $this->mailer = $mailer;
        $this->order = $order;
        $this->status = $status;
        $this->user = $user;
        $this->userPasswordChangeMail = $userPasswordChangeMail;
        $this->vehicle = $vehicle;
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
	 * @return ResponseFactory|Response
	 */
	public function getVehicleFromVIN(Request $request)
	{
		$vehicle = $this->vinService->decodeVIN($request->vin);

		return response($vehicle);
	}

    /**
     * @param Request $request
     */
    public function createOrder(Request $request)
    {
        event(new NotificationEvent('Order create request has been sent and will be processed shortly', 'alert-info'));
        event(new CreateOrderEvent($request));
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

	/**
	 * @param Request $request
	 */
	public function createItemType(Request $request)
	{
		event(new NotificationEvent('Item create request has been sent and will be processed shortly', 'alert-info'));
		event(new CreateItemEvent($request));
	}

    public function editCustomer(Request $request)
    {
        $customer = $this->customer->find($request->id);

        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->zip_code = $request->zip_code;
        $customer->phone = $request->phone;

        $update = $customer->save();

        if (!$update) {
        	event(new NotificationEvent('There was an error updating the customer information', 'alert-danger'));
        	return;
        }

		event(new NotificationEvent('Customer information has been updated', 'alert-success'));
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
	 */
    public function removeUserFromCustomer(Request $request)
    {
        $update = $this->user
            ->find($request->id)
            ->update(['customer_id' => 0]);

        if (!$update) {
			event(new NotificationEvent('There was an error removing the user from the customer', 'alert-danger'));
			return;
		}

        event(new NotificationEvent('User has been removed from the customer', 'alert-success'));
    }

    /**
     * @param Request $request
     */
    public function addUserToCustomer(Request $request)
    {
        $user = $this->user
            ->find($request->user_id);
        $customer = $this->customer
            ->find($request->customer_id);

        $update = $user->update(['customer_id' => $customer->id]);

        if (!$update) {
        	event(new NotificationEvent('There was an error adding the user to the customer', 'alert-danger'));
        	return;
        }

        event(new NotificationEvent('User has been added to customer', 'alert-success'));
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function getUser(Request $request)
    {
        $user = $this->user
            ->withTrashed()
            ->with('customer')
            ->find($request->id);

        return response($user);
    }

    /**
     * @param Request $request
     */
    public function editUser(Request $request)
    {
        $password = $request->password;
        $user = $this->user
            ->withTrashed()
            ->find($request->id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($password != '') {
            $user->password = Hash::make($password);
        }

        $update = $user->save();

        if (!$update) {
            event(new NotificationEvent('There was an error updating the user information', 'alert-danger'));
            return;
        }

		if ($password != '') {
			$this->userPasswordChangeMail->setPassword($password);
			$this->mailer
				->to($user->email)
				->send($this->userPasswordChangeMail);
		}

		event(new NotificationEvent('User information has been updated', 'alert-success'));
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function deleteUser(Request $request)
    {
        $user = $this->user
            ->withTrashed()
            ->find($request->id);

        if ($user->deleted_at != null) {
            $user->deleted_at = null;
            $user->save();

            event(new NotificationEvent('Account for ' . $user->name . ' has been restored', 'alert-success'));

            return;
        }

        $delete = $user->delete();

        if (!$delete) {
            event(new NotificationEvent('There was an error deleting the account', 'alert-success'));

            return;
        }

        event(new NotificationEvent('Account for ' . $user->name . ' has been deleted', 'alert-success'));
    }

	/**
	 * @param Request $request
	 */
    public function updateOrderStatus(Request $request)
	{
		$order = $this->order->find($request->id);
		$status = $this->status->find($request->status);
		$users = $order->customer->users;

		$update = $order->update(['status_id' => $status->id]);

		if (!$update) {
			event(new NotificationEvent('There was an error updating the order status', 'alert-danger'));
			return;
		}

		$order->comments()->create([
		    'user_id' => auth()->user()->id,
            'comment' => 'Order status has been changed to ' . $status->name,
        ]);

		event(new NotificationEvent('Order status has been updated', 'alert-success'));

		foreach ($users as $user) {
			event(new NotificationEvent('Status for ' . $order->vin . ' has been changed to ' . $status->name, 'alert-info', $user));
		}
	}

	/**
	 * @param Request $request
	 * @return ResponseFactory|Response
	 */
	public function getKeysForMake(Request $request)
	{
		$keys = $this->itemVehicle->query();

		if ($request->make != 'All') {
			$keys->where('make', $request->make);
		}
		$keys->with('item');
		$keys = $keys->get();

		return response($keys);
	}

	/**
	 * @param Request $request
	 * @return ResponseFactory|Response
	 */
	public function getItemVehicle(Request $request)
	{
		$key = $this->itemVehicle
			->with('item')
			->find($request->id);

		return response($key);
	}

	/**
	 * @param Request $request
	 */
	public function editItemVehicle(Request $request)
	{
		$key = $this->itemVehicle->find($request->id);
		$item = $this->item->find($request->type);

		$key->item_id = $item->id;
		$key->year_from = $request->yearFrom;
		$key->year_to = $request->yearTo;
		$key->make = $request->make;
		$key->model = $request->model;
		$key->price = $request->price;

		$update = $key->save();

		if (!$update) {
			event(new NotificationEvent('There was an error updating the key', 'alert-danger'));

			return;
		}

		event(new NotificationEvent('Key has been updated', 'alert-success'));
	}

	/**
	 * @param Request $request
	 * @return ResponseFactory|Response
	 */
	public function getModelsFromMake(Request $request)
	{
		$models = $this->vehicle
			->where('make', $request->make)
			->select('model')
			->distinct()
			->orderBy('model', 'asc')
			->get();

		return response($models);
	}

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function getOrderDetailsFromVIN(Request $request)
    {
        $order = $this->order
            ->with(['comments' => function ($query) {
                $query->orderBy('created_at', 'desc')->with('user');
            }])
            ->with('items.item')
            ->with('status')
            ->with('user')
            ->find($request->id)
            ->setHidden(['updated_at']);

        return response($order);
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response|void
     */
    public function createOrderComment(Request $request)
    {
        $order = $this->order->find($request->id);

        $comment = $order->comments()->create([
            'user_id' => auth()->user()->id,
            'comment' => $request->comment,
        ]);

        if (!$comment) {
            event(new NotificationEvent('There was an error adding the comment', 'alert-danger'));

            return;
        }

        $comment->load('user');

        $users = $order->customer->users;

        event(new NotificationEvent('Comment has been added to order', 'alert-success'));

        foreach ($users as $user) {
            event(new NotificationEvent('New comment for ' . $order->vin . ': ' . $comment->comment, 'alert-info', $user));
        }

        return response($comment);
    }
}
