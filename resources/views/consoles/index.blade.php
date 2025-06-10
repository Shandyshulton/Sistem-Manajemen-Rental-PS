<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consoles</title>
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
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Console</li>
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
                <h1>Data Consoles</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addConsoleModal">
                    <i class="fas fa-plus"></i> Add Console
                </button>
            </div>

            <form action="{{ route('consoles.index') }}" method="GET" class="mb-4">
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
                            <th>Type Console</th>
                            <th>Console Room</th>
                            <th>Availability</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($consoles as $console)
                            <tr>
                                <td>{{ ($consoles->currentPage() - 1) * $consoles->perPage() + $loop->iteration }}</td>
                                <td>{{ $console->typeConsole }}</td>
                                <td>{{ $console->consoleRoom }}</td>
                                <td>{{ $console->availability }}</td>
                                <td>Rp {{ number_format($console->price, 0, ',', '.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editConsoleModal{{ $console->id_console }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('consoles.destroy', $console->id_console) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Console Modal -->
                            <div class="modal fade" id="editConsoleModal{{ $console->id_console }}" tabindex="-1"
                                aria-labelledby="editConsoleModalLabel{{ $console->id_console }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editConsoleModalLabel{{ $console->id_console }}">
                                                Edit Console
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('consoles.update', $console->id_console) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="typeConsole" class="form-label">Type Console</label>
                                                    <select class="form-select" name="typeConsole" required>
                                                        <option value="PS 5" {{ $console->typeConsole == 'PS 5' ? 'selected' : '' }}>PS 5</option>
                                                        <option value="PS 4" {{ $console->typeConsole == 'PS 4' ? 'selected' : '' }}>PS 4</option>
                                                        <option value="PS 3" {{ $console->typeConsole == 'PS 3' ? 'selected' : '' }}>PS 3</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="consoleRoom" class="form-label">Console Room</label>
                                                    <input type="text" class="form-control" name="consoleRoom"
                                                        value="{{ $console->consoleRoom }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="availability" class="form-label">Availability</label>
                                                    <select class="form-select" name="availability" required>
                                                        <option value="Ready" {{ $console->availability == 'Ready' ? 'selected' : '' }}>Ready</option>
                                                        <option value="Not Yet" {{ $console->availability == 'Not Yet' ? 'selected' : '' }}>Not Yet</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price per Hour</label>
                                                    <select class="form-select" name="price" required>
                                                        <option value="25000" {{ $console->price == 25000 ? 'selected' : '' }}>Rp 25.000</option>
                                                        <option value="20000" {{ $console->price == 20000 ? 'selected' : '' }}>Rp 20.000</option>
                                                        <option value="15000" {{ $console->price == 15000 ? 'selected' : '' }}>Rp 15.000</option>
                                                    </select>
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
                    <li class="page-item {{ $consoles->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $consoles->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Tombol Halaman -->
                    @php
                        $currentPage = $consoles->currentPage();
                        $lastPage = $consoles->lastPage();
                        $start = max($currentPage - 2, 1);
                        $end = min($currentPage + 2, $lastPage);
                    @endphp

                    @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $consoles->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $consoles->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $consoles->url($lastPage) }}">{{ $lastPage }}</a>
                        </li>
                    @endif

                    <!-- Tombol Next -->
                    <li class="page-item {{ !$consoles->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $consoles->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Add Console Modal -->
        <div class="modal fade" id="addConsoleModal" tabindex="-1" aria-labelledby="addConsoleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addConsoleModalLabel">Add Console</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('consoles.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="typeConsole" class="form-label">Type Console</label>
                                <select class="form-select" id="typeConsole" name="typeConsole" required>
                                    <option value="PS 5">PS 5</option>
                                    <option value="PS 4">PS 4</option>
                                    <option value="PS 3">PS 3</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="consoleRoom" class="form-label">Console Room</label>
                                <input type="text" class="form-control" id="consoleRoom" name="consoleRoom" required>
                            </div>
                            <div class="mb-3">
                                <label for="availability" class="form-label">Availability</label>
                                <select class="form-select" id="availability" name="availability" required>
                                    <option value="Ready">Ready</option>
                                    <option value="Not Yet">Not Yet</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price per Hour</label>
                                <select class="form-select" id="price" name="price" required>
                                    <option value="25000">Rp 25.000</option>
                                    <option value="20000">Rp 20.000</option>
                                    <option value="15000">Rp 15.000</option>
                                </select>
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
