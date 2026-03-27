@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Data</h2>

    <form action="{{ route('ken-burat.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>
                <input type="checkbox" name="is_pogi" checked>
                Is Pogi
            </label>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('ken-burat.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection