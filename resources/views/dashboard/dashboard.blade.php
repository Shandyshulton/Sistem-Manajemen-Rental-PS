@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mt-4">Selamat Datang di Dashboard</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <p>Anda berhasil login sebagai <strong>{{ $staff->name ?? 'Pengguna' }}</strong></p>


    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Transaction Success</div>
                <div class="card-body">
                    <h5 class="card-title" id="successCount">0</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Transaction Pending</div>
                <div class="card-body">
                    <h5 class="card-title" id="pendingCount">0</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Transaction Failed</div>
                <div class="card-body">
                    <h5 class="card-title" id="failedCount">0</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer Credit -->
<footer class="mt-5 text-center">
    <p>&copy; {{ date('Y') }} Rental PS 5 Sejiwa. Dibuat dengan ❤ oleh Tim Developer.</p>
</footer>

<script>
    fetch('/transaction-stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('successCount').innerText = data.success;
            document.getElementById('pendingCount').innerText = data.pending;
            document.getElementById('failedCount').innerText = data.failed;
        })
        .catch(error => console.error('Error fetching transaction stats:', error));
</script>
@endsection
