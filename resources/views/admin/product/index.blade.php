@extends('layouts.admin.app')

@section('title', 'Product')

@section('content')

    <table id="example1" class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Menu</th>
                <th>Jumlah Stok</th>
                <th>Harga Menu</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Gula Aren</td>
                <td>100</td>
                <td>Rp.23.000</td>
                <td>gambar</td>
                <td>
                    <button>edit</button>
                    <button>delete</button>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
