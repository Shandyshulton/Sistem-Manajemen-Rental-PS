<!DOCTYPE html>
<html lang="id">
<head>
    <title>Token Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="d-flex justify-content-center align-items-center" style="height: 100vh; background: #f0f2f5;">
    <div class="card p-4 shadow" style="width: 350px;">
        <h4 class="text-center">Token Anda</h4>
        <div class="alert alert-info text-center">{{ $token }}</div>
        <a href="{{ url('/reset-password/' . $token) }}" class="btn btn-success w-100">Gunakan Token</a>
    </div>
</body>
</html>
