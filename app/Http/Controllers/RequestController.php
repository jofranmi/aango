<?php

namespace App\Http\Controllers;

use App\Events\Notification\NotificationEvent;
use App\Events\Order\CreateOrderEvent;
use App\Models\Order;
use App\Models\Vehicle;
use App\Services\Items\ItemService;
use App\Services\VIN\VINService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * @var ItemService $itemService
     */
    protected $itemService;

    /**
     * @var Order $order
     */
    protected $order;

    /**
     * @var VINService $vinService
     */
    protected $vinService;

    public function __construct(ItemService $itemService, Order $order, VINService $vinService)
    {
        $this->itemService = $itemService;
        $this->order = $order;
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
        event(new NotificationEvent('Order has been sent and will be processed shortly', 'alert-info'));
        event(new CreateOrderEvent(null, $request->key, $request->vehicle, $request->vin, Auth::user()));
    }
}
