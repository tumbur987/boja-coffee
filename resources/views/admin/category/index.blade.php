@extends('layouts.admin.app')

@section('title', 'Kategori')

@section('content')

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Milk Based</td>
                <td>
                    <button>edit</button>
                    <button>delete</button>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
