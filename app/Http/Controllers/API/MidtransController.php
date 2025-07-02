<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\Payment;
use App\Models\TransaksiPremium;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function getSnapToken(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'harga' => 'required|integer',
            'paket' => 'required|string',
            'payment_method' => 'required|string',
            'id_user' => 'required|integer',
        ]);

        // Midtrans setup
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = $request->order_id;
        $harga = $request->harga;
        $paket = $request->paket;
        $paymentMethod = $request->payment_method;
        $userId = $request->id_user;

        // Simpan ke tabel payments
        Payment::create([
            'id_user' => $userId,
            'order_id' => $orderId,
            'paket' => $paket,
            'harga' => $harga,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'paid_at' => null,
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $harga,
            ],
            'item_details' => [[
                'id' => strtolower(str_replace(' ', '_', $paket)),
                'price' => $harga,
                'quantity' => 1,
                'name' => 'Paket Premium - ' . $paket,
            ]],
            'customer_details' => [
                'first_name' => 'Pengguna',
                'email' => 'user@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json([
            'status' => 'success',
            'snap_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}"
        ]);
    }

    public function handleNotification(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $notif = new Notification();

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            Log::warning("Order ID not found: $orderId");
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $payment->status = 'success';
            $payment->paid_at = now();
            $payment->save();

            // Cek apakah sudah pernah buat transaksi_premium untuk order_id ini
            $existing = TransaksiPremium::where('id_user', $payment->id_user)
                ->where('paket', $payment->paket)
                ->whereDate('tanggal_mulai', now()->toDateString())
                ->first();

            if (!$existing) {
                $tanggalMulai = now();
                $tanggalBerakhir = match (strtolower($payment->paket)) {
                    'premium mingguan' => $tanggalMulai->copy()->addWeek(),
                    'premium tahunan' => $tanggalMulai->copy()->addYear(),
                    default => $tanggalMulai->copy()->addMonth(),
                };

                TransaksiPremium::create([
                    'id_user' => $payment->id_user,
                    'paket' => $payment->paket,
                    'harga' => $payment->harga,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_berakhir' => $tanggalBerakhir,
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
