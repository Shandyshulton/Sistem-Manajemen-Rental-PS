<!DOCTYPE html>
<html lang="id">
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(#4facfe, #4facfe);
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .navbar {
            width: 100%;
            background: #004085;
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: absolute;
            top: 0;
        }
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo {
            width: 45px;
            height: auto;
            margin-top: -5px;
        }
        .logo2 {
            margin-bottom: 15px;
            width: 70px;
        }
        .content {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 90%;
            margin-top: 80px;
            gap: 50px;
        }
        .image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .image-container {
            display: flex;
            align-items: flex-end;
            gap: 0px;
        }
        .ps5-image {
            width: 350px;
            height: 400px;
        }
        .ps4-image {
            width: 240px;
            height: 200px;
            margin-left: -70px;
        }
        .image-section h5 {
            font-weight: bold;
            color: white;
            text-align: center;
        }
        .image-section h5 span {
            color: black;
        }
        .forgot-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .forgot-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .input-group-text {
            border-left: 0;
            cursor: pointer;
        }
        .form-control {
            border-right: 0;
        }
        .back-to-login {
            text-align: left;
            font-size: 14px;
            color: black;
        }
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            .forgot-container {
                width: 100%;
                max-width: 380px;
            }
            .image-container {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
            .ps5-image {
                width: 260px;
                height: 280px;
            }
            .ps4-image {
                width: 180px;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <h5>RENTAL PS 5 SEJIWA</h5>
        </div>
        <span id="current-date"></span>
    </div>

    <div class="content">
        <div class="image-section">
            <div class="image-container">
                <img src="{{ asset('images/PS5.png') }}" alt="PS5" class="ps5-image">
                <img src="{{ asset('images/PS4.png') }}" alt="PS4" class="ps4-image">
            </div>
            <h5>SISTEM MANAJEMEN RENTAL <span>PLAYSTATION</span></h5>
        </div>

        <div class="forgot-container">
            <div class="text-center">
                <img src="{{ asset('images/logo2.png') }}" width="60" alt="Logo" class="logo2">
            </div>
            <h3>FORGOT PASSWORD</h3>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('forgot.password.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold">Email</label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control" placeholder="Email*" required>
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Reset Password</button>
            </form>

            <div class="back-to-login mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">Kembali ke Login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let today = new Date();
            let formattedDate = today.getDate().toString().padStart(2, '0') + '/' + 
                                (today.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                                today.getFullYear();
            document.getElementById("current-date").innerText = formattedDate;
        });
    </script>
</body>
</html>