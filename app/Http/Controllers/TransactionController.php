<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// class TransactionController extends Controller
// {
//     public function store(Request $request){
//         $user_id = Auth::id();
//         $total = 0;
//         $validated = $request->validate([
//             'paid' => 'required|integer',
//         ]);

//         $transacted_at = now();
//         $change = $validated['paid'] - $total;
//         Transaction::create([
//             'user_id' => $user_id,
//             'transacted_at' => $transacted_at,
//             'total' => $total,
//             'paid' => $validated['paid'],
//             'change' => $change,
//         ]);

//         $transaction = Transaction::latest()->first();
//         $priceItem = Item::find(id);
//         $price = $priceItem->price;
//         $validated2 = $request->validate([
//             'item_id' => 'required',
//             'quantity' => 'required|integer',
//         ]);

//         TransactionDetail::create([
//             'transaction_id' => $transaction,
//             'item_id' => $validated2['item_id'],
//             'quantity' => $validated2['quantity'],
//             'price' => $price * $validated2['quantity'],
//             'subtotal' => $price,
//         ]);


//     }
// }
class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $transacted_at = now();

        // Validasi semua input sekaligus
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'paid' => 'required|integer|min:0',
        ]);

        // Ambil item
        $item = Item::find($validated['item_id']);
        $price = $item->price;
        $quantity = $validated['quantity'];
        $total = $price * $quantity;
        $change = $validated['paid'] - $total;

        if ($item->stock < $quantity) {
            return response()->json([
                'message' => 'Stok tidak mencukupi',
            ], 400);
        }

        // Simpan transaksi utama
        $transaction = Transaction::create([
            'user_id' => $user_id,
            'transacted_at' => $transacted_at,
            'total' => $total,
            'paid' => $validated['paid'],
            'change' => $change,
        ]);

        // Simpan detail transaksi
        $detail = TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'item_id' => $item->id,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $total,
        ]);

        $item->stock -= $quantity;
        $item->save();

        return response()->json([
            'message' => 'Transaksi berhasil disimpan',
            'data' => $transaction,
            'detail' => $detail,
        ]);
    }
}
