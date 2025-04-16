<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{


    public function index()
    {
        $transactions = Transaction::with('transaction_details.item', 'user')->latest()->get();

        return response()->json([
            'transactions' => $transactions
        ]);
    }

    public function show($id)
    {
        $transaction = Transaction::with('transaction_details.item', 'user')->find($id);

        if(!$transaction){
            return response()->json([
                'message' => 'tidak ada data dengan id ' . $id,
            ], 404);
        }

        return response()->json([
            'transaction' => $transaction
        ]);
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $transacted_at = now();

        // Validasi request utama
        $validated = $request->validate([
            'paid' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;

        // Hitung total dari semua item
        foreach ($validated['items'] as $entry) {
            $item = Item::find($entry['item_id']);
            $total += $item->price * $entry['quantity'];
        }

        $change = $validated['paid'] - $total;

        // Simpan transaksi utama
        $transaction = Transaction::create([
            'user_id' => $user_id,
            'transacted_at' => $transacted_at,
            'total' => $total,
            'paid' => $validated['paid'],
            'change' => $change,
        ]);

        // Simpan detail & kurangi stok
        $details = [];

        foreach ($validated['items'] as $entry) {
            $item = Item::find($entry['item_id']);
            $quantity = $entry['quantity'];
            $subtotal = $item->price * $quantity;

            // Kurangi stok
            $item->stock -= $quantity;
            $item->save();

            $details[] = TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'item_id' => $item->id,
                'quantity' => $quantity,
                'price' => $item->price,
                'subtotal' => $subtotal,
            ]);
        }

        return response()->json([
            'message' => 'Transaksi berhasil disimpan',
            'transaction' => $transaction,
            'details' => $details,
        ]);
    }

    public function report(Request $request){
        $validated = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from'
        ]);

        $transactions = Transaction::with('user', 'transaction_details.item')
                        ->whereBetween('transacted_at', [$validated['from'], $validated['to']])
                        ->get();

        if($transactions->isEmpty()){
            return response()->json([
                'message' => 'tidak ada report rentang tanggal tersebut',
            ],404);
        }
        
        return response()->json([
            'message' => 'laporan transaksi selama ' . $validated['from'] .' sampai ' . $validated['to'],
            'report' => $transactions
        ]);
    }
}
