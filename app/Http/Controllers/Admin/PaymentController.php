<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'post'])->latest()->get();
        return view('admin.payments.index', compact('payments'));
    }
}
