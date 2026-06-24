@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1 class="mb-4">View About Header</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $header->title }}</h5>
            <p class="card-text">
                <strong>Status:</strong> {{ $header->status ? 'Active' : 'Inactive' }}<br>
                <strong>Created At:</strong> {{ $header->created_at->format('d-m-Y') }}<br>
                <strong>Updated At:</strong> {{ $header->updated_at->format('d-m-Y') }}
            </p>
        </div>
    </div>

    <a href="{{ route('header.edit', $header->id) }}" class="btn btn-warning mt-3">Edit Header</a>
    <a href="{{ route('header.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
