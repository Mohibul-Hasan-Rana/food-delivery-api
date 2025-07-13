<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DeliveryZone;
use App\Services\OrderAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $assignmentService;

    public function __construct(OrderAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Place a new order (with delivery zone validation and assignment)
     */
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_latitude' => 'required|numeric',
            'delivery_longitude' => 'required|numeric',
            // ...other order fields validation...
        ]);

        $zone = DeliveryZone::where('restaurant_id', $request->restaurant_id)->get()
            ->first(fn($z) => $this->assignmentService->validateAddressInZone($z, $request->delivery_latitude, $request->delivery_longitude));

        if (!$zone) {
            return response()->json(['error' => 'Address is outside delivery zone'], 422);
        }

        $nearestMan = $this->assignmentService->findNearestDeliveryMan($request->delivery_latitude, $request->delivery_longitude);
        if (!$nearestMan) {
            return response()->json(['error' => 'No delivery man available nearby'], 422);
        }

        return DB::transaction(function () use ($request, $zone, $nearestMan) {
            $order = Order::create([
                'restaurant_id' => $request->restaurant_id,
                'delivery_zone_id' => $zone->id,
                'delivery_man_id' => $nearestMan->id,
                // ...other order fields...
            ]);
            $this->assignmentService->assignDeliveryMan($order, $nearestMan);
            return response()->json(['order' => $order], 201);
        });
    }
}
