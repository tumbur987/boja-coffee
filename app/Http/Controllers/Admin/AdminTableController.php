<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminTableController extends Controller
{
    public function index()
    {
        $tables = Table::latest()->paginate(10);
        return view('admin.table.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.table.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'number' => 'required|string|max:255|unique:tables,number',
                'code'   => 'unique:tables,code',
            ],
            [
                'number.unique' => 'Nomor meja ini sudah ada. Silakan gunakan nomor lain.',
                'code.unique'   => 'Kode meja ini sudah digunakan.',
            ]
        );

        $code = 'MJA' . str_pad($validated['number'], 3, '0', STR_PAD_LEFT);
        $validated['code'] = $code;

        Table::create($validated);

        return redirect()->route('table.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function show(Table $table)
    {
        return response()->json($table);
    }

    public function edit(Table $table)
    {
        return view('admin.table.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $validated = $request->validate(
            [
                'number' => 'required|string|max:255|unique:tables,number,' . $table->id,
                'code'   => 'unique:tables,code,' . $table->id,
            ],
            [
                'number.unique' => 'Nomor meja ini sudah ada. Silakan gunakan nomor lain.',
                'code.unique'   => 'Kode meja ini sudah digunakan.',
            ]
        );

        $code = 'MJA' . str_pad($validated['number'], 3, '0', STR_PAD_LEFT);
        $table->update([
            'number' => $validated['number'],
            'code'   => $code,
        ]);
        return redirect()->route('table.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('table.index')->with('success', 'Meja berhasil dihapus.');
    }

    public function qrDownload(Table $table)
    {
        $url = route('order.index', $table->code);
        $qr = QrCode::format('svg')
            ->size(400)
            ->generate($url);

        return response($qr, 200, [
            'Content-Type'        => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="qr-meja-' . $table->number . '.svg"',
        ]);
    }

    public function qrPreview(Table $table)
    {
        $url = route('order.index', $table->code);
        $qr = QrCode::format('svg')
            ->size(400)
            ->generate($url);

        return response($qr, 200, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }
}
