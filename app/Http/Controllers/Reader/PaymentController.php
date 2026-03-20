<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout(Post $post)
    {
        abort_if($post->isFree(), 403);
        abort_if(Auth::user()->hasUnlocked($post), 403);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => 'usd',
                    'product_data' => [
                        'name' => $post->title,
                    ],
                    'unit_amount'  => $post->price * 100,
                ],
                'quantity'   => 1,
            ]],
            'mode'                 => 'payment',
            'success_url'          => route('reader.payment.success') . '?session_id={CHECKOUT_SESSION_ID}&post_id=' . $post->id,
            'cancel_url'           => route('reader.posts.show', $post),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'paid') {
            $post = Post::findOrFail($request->post_id);

            // avoid duplicate payment records
            $exists = Payment::where('user_id', Auth::id())
                              ->where('post_id', $post->id)
                              ->where('status', Payment::STATUS_PAID)
                              ->exists();

            if (!$exists) {
                Payment::create([
                    'user_id'        => Auth::id(),
                    'post_id'        => $post->id,
                    'amount'         => $post->price,
                    'method'         => Payment::METHOD_STRIPE,
                    'status'         => Payment::STATUS_PAID,
                    'transaction_id' => $session->payment_intent,
                ]);
            }

            return redirect()->route('reader.posts.show', $post)
                             ->with('success', 'Post unlocked successfully! 🎉');
        }

        return redirect()->route('reader.posts.show', Post::findOrFail($request->post_id))
                         ->with('error', 'Payment failed. Please try again.');
    }

    public function cancel(Post $post)
    {
        return redirect()->route('reader.posts.show', $post)
                         ->with('error', 'Payment cancelled.');
    }
}
