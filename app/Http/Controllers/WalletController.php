<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        // Ambil data transaksi milik user yang sedang login saja
        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)
                                    ->orderBy('date', 'desc')
                                    ->get();
        
        // Hitung total hanya untuk user yang sedang login
        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $saldo = $totalIncome - $totalExpense;

        return view('dashboard', compact('transactions', 'totalIncome', 'totalExpense', 'saldo'));
    }

    public function store(Request $request)
    {
        // Logika Update atau Create
        Transaction::updateOrCreate(
            ['id' => $request->id],
            [
                'user_id' => Auth::id(),
                'type'    => $request->type,
                'amount'  => $request->amount,
                'desc'    => $request->desc,
                'date'    => $request->date,
            ]
        );

        // PERBAIKAN: Mengirimkan tipe transaksi (income/expense) agar halaman tidak pindah ke dashboard
        return redirect('/')->with([
            'success' => 'Data berhasil diproses!',
            'active_page' => $request->type // Ini yang akan dibaca oleh JavaScript
        ]);
    }

    public function destroy($id)
    {
        // Pastikan hanya pemilik transaksi yang bisa menghapus
        $transaction = Transaction::where('id', $id)->where('user_id', Auth::id())->first();
        
        if ($transaction) {
            $type = $transaction->type; // Simpan tipenya dulu sebelum dihapus
            $transaction->delete();

            // PERBAIKAN: Setelah hapus, tetap di halaman yang sama (income atau expense)
            return redirect('/')->with([
                'success' => 'Data berhasil dihapus!',
                'active_page' => $type
            ]);
        }

        return redirect('/')->with('error', 'Gagal menghapus data!');
    }
}