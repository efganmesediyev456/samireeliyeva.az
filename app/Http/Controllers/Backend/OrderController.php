<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrdersDataTable;
use App\DataTables\OrderItemsDataTable;
use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Order::class;
    }

    public function index(OrdersDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.orders.index');
    }

    public function show(Order $order, OrderItemsDataTable $dataTable)
    {
        return $dataTable
            ->with('order', $order)
            ->render('backend.pages.orders.show', compact('order'));
    }


    public function updateStatus(Request $request, $order)
    {
        $order = Order::where('order_number', $order)->first();
        $request->validate([
            'status' => 'required|integer|in:' . implode(',', array_column(OrderStatusEnum::cases(), 'value'))
        ]);
        try {
            DB::beginTransaction();
            $order->status->delete();
            $orderStatus = new OrderStatus();
            $orderStatus->order_id = $order->id;
            $orderStatus->status = $request->status;
            $orderStatus->save();
            DB::commit();
            $statusEnum = OrderStatusEnum::from($request->status);
            $statusName = $statusEnum->toString();
            return redirect()->back()->with('success', "Sifariş statusu \"$statusName\" olaraq yeniləndi.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Status yeniləmə əməliyyatı zamanı xəta: ' . $e->getMessage());
        }
    }

}
