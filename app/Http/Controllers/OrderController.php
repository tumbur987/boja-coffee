<?php

namespace App\Http\Controllers;

use App\Models\Table;

class OrderController extends Controller
{
    public function index($code)
    {
        $table = Table::where('code', $code)->firstOrFail();

        return view('client.order', compact('table'));
    }
}
