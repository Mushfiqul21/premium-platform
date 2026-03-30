@extends('layouts.app')

@section('title', 'Manage Payments')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-gray-700">All Payments</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Reader</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Post</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Amount</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Method</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Transaction ID</th>
                <th class="text-left px-6 py-3 text-gray-500 font-medium">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $payment->user->name }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $payment->post->title }}</td>
                <td class="px-6 py-4 text-gray-600">${{ number_format($payment->amount, 2) }}</td>
                <td class="px-6 py-4">
                    @if($payment->method === \App\Models\Payment::METHOD_STRIPE)
                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full">Stripe</span>
                    @else
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">SSLCommerz</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($payment->status === \App\Models\Payment::STATUS_PAID)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Paid</span>
                    @elseif($payment->status === \App\Models\Payment::STATUS_PENDING)
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Pending</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Failed</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-400 text-xs">{{ $payment->transaction_id }}</td>
                <td class="px-6 py-4 text-gray-400">{{ $payment->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-400">No payments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
