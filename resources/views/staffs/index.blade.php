<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff</title>
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
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Staff</li>
                </ol>
            </nav>
            <!-- Alert Success -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Alert Error -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Data Staff</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                    <i class="fas fa-plus"></i> Add Staff
                </button>
            </div>

            <form action="{{ route('staffs.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" id="search-icon">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        class="form-control border-start-0 transition"
                        placeholder="Search name or email..."
                        value="{{ request('search') }}"
                        style="transition: box-shadow 0.3s ease-in-out;"
                        onfocus="this.style.boxShadow='0 0 5px rgba(0,123,255,.5)'"
                        onblur="this.style.boxShadow='none'"
                    >
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffs as $staff)
                            <tr>
                                <td>{{ ($staffs->currentPage() - 1) * $staffs->perPage() + $loop->iteration }}</td>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ ucfirst($staff->role) }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editStaffModal{{ $staff->id_staff }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('staffs.destroy', $staff->id_staff) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Staff Modal -->
                            <div class="modal fade" id="editStaffModal{{ $staff->id_staff }}" tabindex="-1"
                                aria-labelledby="editStaffModalLabel{{ $staff->id_staff }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editStaffModalLabel{{ $staff->id_staff }}">Edit Staff</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('staffs.update', $staff->id_staff) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="name{{ $staff->id_staff }}" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name{{ $staff->id_staff }}" name="name"
                                                        value="{{ $staff->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email{{ $staff->id_staff }}" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email{{ $staff->id_staff }}"
                                                        name="email" value="{{ $staff->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="role{{ $staff->id_staff }}" class="form-label">Role</label>
                                                    <select class="form-select" id="role{{ $staff->id_staff }}" name="role" required>
                                                        <option value="admin" {{ $staff->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                        <option value="operator" {{ $staff->role == 'operator' ? 'selected' : '' }}>Operator</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password{{ $staff->id_staff }}" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password{{ $staff->id_staff }}"
                                                        name="password">
                                                    <small class="text-muted">Leave blank if you don't want to change the password.</small>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- Tombol Previous -->
                    <li class="page-item {{ $staffs->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $staffs->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Tombol Halaman -->
                    @php
                        $currentPage = $staffs->currentPage();
                        $lastPage = $staffs->lastPage();
                        $start = max($currentPage - 2, 1);
                        $end = min($currentPage + 2, $lastPage);
                    @endphp

                    @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $staffs->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $staffs->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $staffs->url($lastPage) }}">{{ $lastPage }}</a>
                        </li>
                    @endif

                    <!-- Tombol Next -->
                    <li class="page-item {{ !$staffs->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $staffs->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Add Staff Modal -->
        <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStaffModalLabel">Add Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('staffs.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="operator">Operator</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Credit -->
        <footer class="mt-5 text-center">
            <p>&copy; {{ date('Y') }} Rental PS 5 Sejiwa. Dibuat dengan ❤ oleh Tim Developer.</p>
        </footer>

        <script>
            // Menutup alert secara otomatis setelah 5 detik
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var alerts = document.querySelectorAll('.alert');
                    alerts.forEach(function(alert) {
                        alert.style.display = 'none';
                    });
                }, 5000); // 5000 milidetik = 5 detik
            });
        </script>
    @endsection
</body>

</html>
