<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function getTransactionStats()
{
    $success = Booking::where('status', 'success')->count();
    $pending = Booking::where('status', 'pending')->count();
    $failed = Booking::where('status', 'canceled')->count(); // Menghitung "canceled" sebagai "failed"

    return response()->json([
        'success' => $success,
        'pending' => $pending,
        'failed' => $failed // Data "canceled" masuk ke "failed"
    ]);
}
}
