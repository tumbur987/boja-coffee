@extends('layouts.admin.app')

@section('title', 'User')

@section('content')

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>qwe</td>
                <td>qwe</td>
                <td>qwe</td>
                <td>qwe</td>
                <td>
                    <button>edit</button>
                    <button>delete</button>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
