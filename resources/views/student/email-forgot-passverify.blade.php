<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | CONNECT Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/student-email-verify.css') }}">
</head>

<body>
    <div class="forgot-container">
        <!-- Logo & Header -->
        <div class="forgot-header">
            <img src="{{ asset('images/Connect.logo.jpg') }}" alt="CONNECT Logo" class="mb-3 logo-img">
            <h2>Forgot Password?</h2>
            <p class="text-muted mb-3">Enter your CONNECT ZIST email to receive a verification code.</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <!--  Form -->
        <form method="POST" action="{{ route('email.send.code') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="regNum@connect.org.zw">
                </div>
            </div>

            <div class="d-grid justify-content-center">
                <button type="submit" class="btn btn-primary">Send Verification Code</button>
            </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-2">Remember your password?</p>
            <a href="{{ route('student.login') }}" >Back to Login</a>
        </div>
        
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/hideError.js') }}"></script>
</body>

</html>
