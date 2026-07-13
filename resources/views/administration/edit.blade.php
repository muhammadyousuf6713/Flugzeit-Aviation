@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Administration</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('administration.update', $header->id) }}">
                            @csrf
                            @method('POST')

                            <!-- Header Section -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Header Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ $header->name }}">
                            </div>

                            <!-- Details Section -->
                            @foreach ($header->details as $detail)
                                <h5>Detail: {{ $detail->title }}</h5>
                                <div class="mb-3">
                                    <label for="detail-title-{{ $detail->id }}" class="form-label">Title</label>
                                    <input type="text" name="details[{{ $detail->id }}][title]" class="form-control"
                                        id="detail-title-{{ $detail->id }}" value="{{ $detail->title }}">
                                </div>
                                <div class="mb-3">
                                    <label for="detail-description-{{ $detail->id }}"
                                        class="form-label">Description</label>
                                    <textarea name="details[{{ $detail->id }}][description]" class="form-control"
                                        id="detail-description-{{ $detail->id }}">{{ $detail->description }}</textarea>
                                </div>

                                <!-- Contacts Section -->
                                @foreach ($detail->contacts as $contact)
                                    <h6>Contact: {{ $contact->name }}</h6>
                                    <div class="mb-3">
                                        <label for="contact-name-{{ $contact->id }}" class="form-label">Name</label>
                                        <input type="text" name="contacts[{{ $contact->id }}][name]"
                                            class="form-control" id="contact-name-{{ $contact->id }}"
                                            value="{{ $contact->name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact-number-{{ $contact->id }}" class="form-label">Number</label>
                                        <input type="text" name="contacts[{{ $contact->id }}][number]"
                                            class="form-control" id="contact-number-{{ $contact->id }}"
                                            value="{{ $contact->number }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact-email-{{ $contact->id }}" class="form-label">Email</label>
                                        <input type="email" name="contacts[{{ $contact->id }}][email]"
                                            class="form-control" id="contact-email-{{ $contact->id }}"
                                            value="{{ $contact->email }}">
                                    </div>
                                @endforeach
                            @endforeach

                            <!-- Authors Section -->
                            @foreach ($header->authors as $author)
                                <h5>Author: {{ $author->name }}</h5>
                                <div class="mb-3">
                                    <label for="author-name-{{ $author->id }}" class="form-label">Name</label>
                                    <input type="text" name="authors[{{ $author->id }}][name]" class="form-control"
                                        id="author-name-{{ $author->id }}" value="{{ $author->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="author-email-{{ $author->id }}" class="form-label">Email</label>
                                    <input type="email" name="authors[{{ $author->id }}][email]" class="form-control"
                                        id="author-email-{{ $author->id }}" value="{{ $author->email }}">
                                </div>
                                <div class="mb-3">
                                    <label for="author-description-{{ $author->id }}"
                                        class="form-label">Description</label>
                                    <textarea name="authors[{{ $author->id }}][description]" class="form-control"
                                        id="author-description-{{ $author->id }}">{{ $author->description }}</textarea>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('administration.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
