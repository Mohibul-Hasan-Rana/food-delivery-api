<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DeliveryZone;
use App\Services\OrderAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\OrderService;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Exceptions\DeliveryZoneException;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request): JsonResponse
    {
        try {
            
            $order = $this->orderService->validateAndPlaceOrder($request->validated());
            
            return response()->json([
                'message' => 'Order placed successfully',
                'data' => new OrderResource($order)
            ], 201);
        } catch (DeliveryZoneException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
