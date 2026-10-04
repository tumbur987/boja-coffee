<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Transaction;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ], [
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
        ]);

        $transaction = Transaction::where('order_id', $validated['order_id'])->first();

        if (! $transaction) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($transaction->status !== 'selesai') {
            return response()->json(['success' => false, 'message' => 'Pesanan belum selesai.'], 422);
        }

        $data = ['rating' => $validated['rating']];

        if (! empty($validated['comment'])) {
            $data['comment'] = $validated['comment'];
        }

        $feedback = Feedback::updateOrCreate(
            ['transaction_id' => $transaction->id],
            $data
        );

        return response()->json(['success' => true, 'feedback' => $feedback]);
    }
}
