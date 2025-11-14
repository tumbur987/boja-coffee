@extends('layouts.admin.app')

@section('title', 'Transaksi')

@section('content')

    <table id="example1" class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>No</th>
                <th>Kode Meja</th>
                <th>Nama Menu</th>
                <th>Jumlah Menu</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Meja 1</td>
                <td>Gula Aren</td>
                <td>3</td>
                <td>Rp.23.000</td>
                <td>
                    <button>edit</button>
                    <button>delete</button>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
