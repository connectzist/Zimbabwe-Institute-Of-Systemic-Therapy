<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Password Reset | CONNECT Portal</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap" />
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/student-forget-email.css') }}">

</head>

<body>
    <div class="reset-container">
        <div class="reset-header">
            <img src="{{ asset('images/Connect.logo.jpg') }}" alt="CONNECT Logo" class="logo-img" />
            <h2>Reset Email Password</h2>
            <p>Enter verification code you received from your email &amp; set a new password</p>
        </div>

        <div class="reset-form">
            <!-- Success -->
            @if(session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            <!-- Error -->
            @if($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('forgot.email.password.post') }}" method="POST">
                @csrf

                <!-- Email Verification Code -->
                <div class="mb-3">
                    <label for="token-verify" class="form-label">Email Verification Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-pen-to-square"></i></span>
                        <input type="text" class="form-control" id="token-verify" name="token-verify" required placeholder="ABCD09" />
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required />
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required />
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-reset w-100">Reset Password</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/hideError.js') }}"></script>
</body>
</html>
