<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaction</title>
    <style>
        .pagination .page-item .page-link:hover {
            background-color: #2196C5;
            color: white;
        }

        .pagination .page-item.active .page-link:hover {
            background-color: #2196C5;
        }
    </style>
</head>

<body>
    @extends('layouts.app')

    @section('content')
        <div class="container">
            @if (isset($alert))
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show">
                    {{ $alert['message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transaction</li>
                </ol>
            </nav>


            <!-- Header & Create Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Transaction</h1>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Booking
                </a>
            </div>

            <!-- Search and Filter Section -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('bookings.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari customer / telepon / console" value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Date</label>
                                <input type="date" name="filter_date" class="form-control"
                                    value="{{ request('filter_date') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="filter_status">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('filter_status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="success" {{ request('filter_status') == 'success' ? 'selected' : '' }}>
                                        Success</option>
                                    <option value="canceled" {{ request('filter_status') == 'canceled' ? 'selected' : '' }}>
                                        Canceled</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Console</label>
                                <select class="form-select" name="filter_console">
                                    <option value="">Semua Console</option>
                                    @foreach ($consoles as $console)
                                        <option value="{{ $console->typeConsole }}"
                                            {{ request('filter_console') == $console->typeConsole ? 'selected' : '' }}>
                                            {{ $console->typeConsole }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Phone Number</th>
                            <th>Date</th>
                            <th>Console</th>
                            <th>Time</th>
                            <th>Estimate</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Playing Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->customer_name }}</td>
                                <td>{{ $booking->phone_number }}</td>
                                <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                                <td>
                                    @if ($booking->console)
                                        {{ $booking->console->typeConsole }} ({{ $booking->console->consoleRoom }})
                                        <span
                                            class="badge bg-{{ $booking->console->availability == 'Ready' ? 'success' : 'danger' }}">
                                            {{ $booking->console->availability }}
                                        </span>
                                    @else
                                        <span class="text-danger">Console not available</span>
                                    @endif
                                </td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->estimated_hours }} jam</td>
                                <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $booking->status == 'success' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $booking->playing_status == 'Play' ? 'success' : 'secondary' }}">
                                        {{ $booking->playing_status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('bookings.edit', $booking->id_booking) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('bookings.destroy', $booking->id_booking) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus booking ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item {{ $bookings->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $bookings->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @php
                        $currentPage = $bookings->currentPage();
                        $lastPage = $bookings->lastPage();
                        $start = max($currentPage - 2, 1);
                        $end = min($currentPage + 2, $lastPage);
                    @endphp
                    @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $bookings->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $bookings->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $bookings->url($lastPage) }}">{{ $lastPage }}</a>
                        </li>
                    @endif
                    <li class="page-item {{ !$bookings->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $bookings->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Footer Credit -->
        <footer class="mt-5 text-center">
            <p>&copy; {{ date('Y') }} Rental PS 5 Sejiwa. Dibuat dengan ❤ oleh Tim Developer.</p>
        </footer>
    @endsection
</body>

</html>
