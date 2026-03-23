<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RayhanBapari\SslcommerzPayment\DTOs\PaymentData;
use RayhanBapari\SslcommerzPayment\Facades\Sslcommerz;

class SSLCommerzPaymentController extends Controller
{
    public function initiate(Post $post)
    {
        abort_if($post->isFree(), 403);
        abort_if(Auth::user()->hasUnlocked($post), 403);

        $transactionId = 'TXN-' . uniqid();

        $dto                   = new PaymentData();
        $dto->tran_id          = $transactionId;
        $dto->total_amount     = $post->price;
        $dto->currency         = 'BDT';
        $dto->cus_name         = Auth::user()->name;
        $dto->cus_email        = Auth::user()->email;
        $dto->cus_phone        = '01700000000';
        $dto->cus_add1         = 'Dhaka';
        $dto->cus_city         = 'Dhaka';
        $dto->cus_country      = 'Bangladesh';
        $dto->cus_postcode     = '1200';
        $dto->product_name     = $post->title;
        $dto->product_category = 'Digital';
        $dto->product_profile  = 'non-physical-goods';
        $dto->shipping_method  = 'NO';
        $dto->value_a          = (string) Auth::id();
        $dto->value_b          = (string) $post->id;
        $dto->value_c          = $transactionId;

        // Store pending payment
        Payment::create([
            'user_id'        => Auth::id(),
            'post_id'        => $post->id,
            'amount'         => $post->price,
            'method'         => Payment::METHOD_SSLCOMMERZ,
            'status'         => Payment::STATUS_PENDING,
            'transaction_id' => $transactionId,
        ]);

        $response = Sslcommerz::initiatePayment($dto);

        if ($response->success()) {
            return redirect($response->gatewayPageURL());
        }

        return back()->with('error', 'Could not initiate payment: ' . $response->error());
    }

    public function success(Request $request)
    {
        if (!Sslcommerz::verifyIpnHash($request->post())) {
            abort(403, 'Invalid signature.');
        }

        $validation = Sslcommerz::orderValidate($request->val_id);

        if (!in_array($validation['status'] ?? '', ['VALID', 'VALIDATED'])) {
            return redirect()->route('reader.posts.index')
                             ->with('error', 'Payment validation failed.');
        }

        Payment::where('transaction_id', $request->tran_id)
               ->update([
                   'status'         => Payment::STATUS_PAID,
                   'transaction_id' => $request->bank_tran_id,
               ]);

        $post = Post::findOrFail($request->value_b);

        return redirect()->route('reader.posts.show', $post)
                         ->with('success', 'Post unlocked successfully! 🎉');
    }

    public function fail(Request $request)
    {
        Payment::where('transaction_id', $request->tran_id)
               ->update(['status' => Payment::STATUS_FAILED]);

        return redirect()->route('reader.posts.index')
                         ->with('error', 'Payment failed. Please try again.');
    }

    public function cancel(Request $request)
    {
        Payment::where('transaction_id', $request->tran_id)
               ->update(['status' => Payment::STATUS_FAILED]);

        return redirect()->route('reader.posts.index')
                         ->with('error', 'Payment cancelled.');
    }
}
