<?php

namespace App\Http\Controllers;
use App\Models\Mesas;
use App\Models\Orders;
use App\Resources\Orders\PostOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Orders::with('table')->get();
        $tables = Mesas::all();
        return inertia('Orders/Index', [
            'orders' => $orders,
            'tables' => $tables
        ]);
    }

    public function store(Request $request)
    {
        $order = new PostOrder();

        $order($request);

    return redirect()->route('dashboard')->with('success', 'Order created successfully.');
    }

     public function update(Request $request, $id) {
        $order = Orders::findOrFail($id);
        $order->mesa_id = $request->table_id;
        $order->status = $request->status;
        $order->total = $request->subtotal;
        $order->save();

        if ($request->has('items')) {
            $order->orderDetails()->delete();
            foreach ($request->items as $item) {
                $order->orderDetails()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                
            }
            
        }

        return redirect()->route('dashboard')->with('success', 'Orden actualizada correctamente');
    }

    public function addTip(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $request->validate([
            'tip' => 'nullable|numeric|min:0',
            'tip_percent' => 'nullable|numeric|min:0|max:100',
        ]);
        $order->tip = $request->tip !== null && $request->tip !== ''
            ? floatval($request->tip)
            : 0;
        $order->tip_percent = $request->tip_percent !== null && $request->tip_percent !== ''
            ? floatval($request->tip_percent)
            : null;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Tip added successfully', 'tip' => $order->tip]);
    }
 }