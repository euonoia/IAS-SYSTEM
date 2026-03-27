@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-4 mt-4">
        <h2>Edit Data</h2>

        <form action="{{ route('ken-burat.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ $item->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ $item->description }}</textarea>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_pogi" class="form-check-input" id="is_pogi" {{ $item->is_pogi ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_pogi">Is Pogi</label>
                </div>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('ken-burat.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection