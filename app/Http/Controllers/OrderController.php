<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\Status;
use App\Models\UserType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends AbstractController
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
     * @var UserType $userType
     */
    protected $userType;

    /**
     * OrderController constructor.
     * @param Customer $customer
     * @param Item $item
     * @param Order $order
     * @param Status $status
     * @param User $user
     * @param UserType $userType
     */
    public function __construct(Customer $customer, Item $item, Order $order, Status $status, User $user, UserType $userType)
    {
        $this->customer = $customer;
        $this->item = $item;
        $this->order = $order;
        $this->status = $status;
        $this->user = $user;
        $this->userType = $userType;
    }

    public function index()
    {
        $customers = null;
        $items = $this->item->all();
        $status = $this->status->all();

        if (Gate::forUser(Auth::user())->allows('customer')) {
            $orders = Auth::user()->customer->orders
                ->load('user:id,name')
                ->load('status:id,name')
                ->load('items.item');
        } else {
            $customers = $this->customer->all();
            $orders = $this->order
                ->with('user:id,name')
                ->with('status:id,name')
                ->with('items.item')
                ->get();
        }

        return view('order.view')
            ->with('customers', $customers)
            ->with('items', $items)
            ->with('orders', $orders)
            ->with('status', $status);
    }


    public function filterOrders(Request $request)
    {
        parent::__construct($this->order);

        $orders = $this->filterModelByQuery($request);

        return response($orders);
    }

    public function createView()
    {
        return view('Order.create');
    }
}
