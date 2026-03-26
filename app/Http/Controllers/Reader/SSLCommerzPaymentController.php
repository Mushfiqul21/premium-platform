<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Post;
use App\Services\SSLCommerzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SSLCommerzPaymentController extends Controller
{
    protected SSLCommerzService $sslcommerz;

    public function __construct(SSLCommerzService $sslcommerz)
    {
        $this->sslcommerz = $sslcommerz;
    }

    public function initiate(Post $post)
    {
        abort_if($post->isFree(), 403);
        abort_if(Auth::user()->hasUnlocked($post), 403);

        $transactionId = 'TXN-' . uniqid();

        // Store pending payment
        Payment::create([
            'user_id'        => Auth::id(),
            'post_id'        => $post->id,
            'amount'         => $post->price,
            'method'         => Payment::METHOD_SSLCOMMERZ,
            'status'         => Payment::STATUS_PENDING,
            'transaction_id' => $transactionId,
        ]);

        $response = $this->sslcommerz->initiatePayment([
            'amount'       => $post->price,
            'tran_id'      => $transactionId,
            'success_url'  => route('reader.sslcommerz.success'),
            'fail_url'     => route('reader.sslcommerz.fail'),
            'cancel_url'   => route('reader.sslcommerz.cancel'),
            'cus_name'     => Auth::user()->name,
            'cus_email'    => Auth::user()->email,
            'product_name' => $post->title,
            'value_a'      => (string) Auth::id(),
            'value_b'      => (string) $post->id,
        ]);

        if (isset($response['GatewayPageURL']) && $response['GatewayPageURL']) {
            return redirect($response['GatewayPageURL']);
        }

        return back()->with('error', 'Could not initiate payment. Try again.');
    }

    public function success(Request $request)
    {
        $tranId = $request->tran_id ?? $request->input('tran_id');

        if (!$tranId) {
            return redirect()->route('login')
                ->with('error', 'Invalid payment response.');
        }

        $payment = Payment::where('transaction_id', $tranId)->first();

        if (!$payment) {
            return redirect()->route('login')
                ->with('error', 'Payment record not found.');
        }

        $payment->update([
            'status'         => Payment::STATUS_PAID,
            'transaction_id' => $request->bank_tran_id ?? $tranId,
        ]);

        // Log user back in using stored user_id
        $userId = $request->value_a ?? $payment->user_id;
        Auth::loginUsingId($userId);

        $post = Post::findOrFail($payment->post_id);

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
