@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">

        {{-- 📊 Top Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Total Inquiries</p>
                                    <h3 class="font-weight-bolder mb-0 text-primary">
                                        {{ $totalInquiries }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-file-alt text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Total Users</p>
                                    <h3 class="font-weight-bolder mb-0 text-info">
                                        {{ $totalUsers }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="fas fa-users-cog text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Total Customers</p>
                                    <h3 class="font-weight-bolder mb-0 text-success">
                                        {{ $totalCustomers }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ route('followups.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 hover-shadow transition-all">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-xs mb-1 text-uppercase font-weight-bold text-muted">Follow-ups</p>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-warning font-weight-bolder h4 mb-0" title="Today">{{ $todaysFollowupsCount }}</span>
                                            <span class="text-muted">/</span>
                                            <span class="text-danger font-weight-bolder h4 mb-0" title="Overdue">{{ $overdueFollowupsCount }}</span>
                                            <span class="text-muted">/</span>
                                            <span class="text-success font-weight-bolder h4 mb-0" title="Upcoming">{{ $upcomingFollowupsCount }}</span>
                                        </div>
                                        <p class="text-xxs mb-0 text-muted">Today / Overdue / Next</p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="fas fa-calendar-check text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- 📈 Inquiry Status Breakdown --}}
            <div class="col-lg-7">
                <div class="card header-card h-100 border-0 shadow-sm">
                    <div class="card-header pb-0 p-3 bg-white">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i>Inquiry Status Overview</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Count</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inquiryStatusCounts as $status => $count)
                                        @php
                                            $percentage = $totalInquiries > 0 ? ($count / $totalInquiries) * 100 : 0;
                                            $color = 'secondary'; // Default grey
                                            $statusLower = strtolower($status);
                                            
                                            if($statusLower == 'open') $color = 'secondary';
                                            elseif($statusLower == 'completed' || $statusLower == 'confirm' || $statusLower == 'confirmed') $color = 'success';
                                            elseif($statusLower == 'cancelled') $color = 'danger';
                                            elseif($statusLower == 'in-progress' || $statusLower == 'quotation') $color = 'warning';
                                            elseif($statusLower == 'hold') $color = 'secondary';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <span class="badge bg-gradient-{{ $color }} me-3" style="{{ $color == 'warning' ? 'color: #343a40 !important;' : '' }}">{{ $status }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold"> {{ $count }} </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="progress-wrapper w-75 mx-auto">
                                                    <div class="progress-info">
                                                        <div class="progress-percentage">
                                                            <span class="text-xs font-weight-bold">{{ round($percentage) }}%</span>
                                                        </div>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-{{ $color }}" role="progressbar"
                                                            style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 👥 User Performance --}}
            <div class="col-lg-5">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header pb-0 p-3 bg-white">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-user-tie me-2 text-info"></i>User Workload</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            @foreach ($userPerformance as $user)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                            <i class="fas fa-user text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $user->name }}</h6>
                                            <span class="text-xs">Assigned Inquiries</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-sm font-weight-bold">
                                        <a href="{{ route('inquiry.index', ['sales_person' => $user->id]) }}" class="text-primary text-decoration-underline" title="View Assigned Inquiries">
                                            {{ $user->inquiry_count }}
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- 📅 Latest Follow-ups --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header pb-0 bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-warning"></i>Latest Follow-ups</h6>
                            <a href="{{ route('inquiry.index') }}" class="btn btn-sm btn-outline-primary mb-0">View All</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 1%; white-space: nowrap;">Inq ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 1%; white-space: nowrap;">Customer</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="width: 1%; white-space: nowrap;">Follow-up Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 1%; white-space: nowrap;">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestFollowups as $followup)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="text-xs font-weight-bold">#{{ $followup->inquiry_id }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $followup->inquiry->customer->customer_name ?? 'N/A' }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $followup->inquiry->customer->customer_cell ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $date = $followup->followup_date ? \Carbon\Carbon::parse($followup->followup_date) : null;
                                                @endphp
                                                @if ($date)
                                                    <span class="text-xs font-weight-bold">{{ $date->format('d M Y') }}</span>
                                                @else
                                                    <span class="text-xs font-weight-bold text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if ($date && $date->isToday())
                                                    <span class="badge badge-sm bg-gradient-warning px-3 text-dark">Today</span>
                                                @elseif ($date && $date->isPast())
                                                    <span class="badge badge-sm bg-gradient-danger px-3">Overdue</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-success px-3">Upcoming</span>
                                                @endif
                                            </td>
                                            <td class="align-middle" style="max-width: 0; width: 100%;">
                                                <div class="d-flex flex-column">
                                                    <span class="text-secondary text-xs font-weight-bold text-truncate w-100 d-block" data-bs-toggle="tooltip" title="{{ $followup->remarks }}">{{ $followup->remarks }}</span>
                                                    @if($followup->createdBy)
                                                        <span class="text-xxs text-muted mt-1"><i class="fa fa-user me-1"></i>{{ $followup->createdBy->name }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-check-circle text-success h1 mb-2"></i>
                                                    <span class="text-muted text-sm">All clear! No pending follow-ups.</span>
                                                </div>
                                            </td>
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
@endsection
