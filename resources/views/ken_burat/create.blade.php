@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-4 mt-4">
        <h2>Create Data</h2>

        <form action="{{ route('ken-burat.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="is_pogi">Is Pogi?</label>
                <select name="is_pogi" class="form-control">
                    <option value="1" selected>Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('ken-burat.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection