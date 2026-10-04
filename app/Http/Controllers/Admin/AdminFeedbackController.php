<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $ratingFilter = $request->input('rating');
        $ratingFilter = in_array($ratingFilter, ['1', '2', '3', '4', '5'], true) ? (int) $ratingFilter : null;

        $query = Feedback::with(['transaction.table'])->latest();

        if ($ratingFilter) {
            $query->where('rating', $ratingFilter);
        }

        $feedbacks = $query->paginate(10)->appends(['rating' => $ratingFilter]);

        $total = Feedback::count();
        $average = $total ? round((float) Feedback::avg('rating'), 1) : 0;
        $satisfied = Feedback::where('rating', '>=', 4)->count();
        $satisfiedPercent = $total ? round($satisfied / $total * 100) : 0;
        $withComment = Feedback::whereNotNull('comment')->count();
        $distribution = Feedback::selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        return view('admin.feedback.index', compact(
            'feedbacks', 'total', 'average', 'satisfied', 'satisfiedPercent',
            'withComment', 'distribution', 'ratingFilter'
        ));
    }
}
