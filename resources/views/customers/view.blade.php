@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Customer Details</h6>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $view->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $view->customer_email }}</p>
                            <p><strong>Mobile:</strong> {{ $view->customer_cell }}</p>
                            <p><strong>Address:</strong> {{ $view->customer_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>City:</strong> {{ $view->city->name ?? 'N/A' }}</p>
                            <p><strong>Sales Person:</strong> {{ $view->salePerson->name ?? 'N/A' }}</p>
                            <p><strong>Customer Type:</strong> {{ $view->customer_type }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge bg-{{ $view->status ? 'success' : 'danger' }}">
                                    {{ $view->status ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Inquiries History</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Inquiry ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Travel Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Confirmed Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Calculated Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inquiry as $inq)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $inq->id_inquiry }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $inq->type_name }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-{{ getStatusColor($inq->status) }}">
                                            {{ ucfirst($inq->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $inq->travel_date ? \Carbon\Carbon::parse($inq->travel_date)->format('d M Y') : 'N/A' }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $inq->confirmed_amount ? number_format($inq->confirmed_amount) : 'N/A' }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $inq->calculated_amount ? number_format($inq->calculated_amount) : 'N/A' }}
                                        </p>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No inquiries found for this customer</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
function getStatusColor($status) {
    switch(strtolower($status)) {
        case 'confirmed': return 'success';
        case 'pending': return 'warning';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
@endphp

@endsection
