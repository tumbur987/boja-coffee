@extends('layouts.admin.app')

@section('title', 'Meja')

@section('content')

<table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>No Meja</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Meja 1</td>
                <td>
                    <button>edit</button>
                    <button>delete</button>
                    <button>generate</button>
                </td>
            </tr>
        </tbody>
    </table>

@endsection