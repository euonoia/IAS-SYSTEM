@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ken Burat List</h2>

    <a href="{{ route('ken-burat.create') }}" class="btn btn-primary mb-3">Add New</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Is Pogi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->description }}</td>
                <td>{{ $row->is_pogi ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('ken-burat.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('ken-burat.delete', $row->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection