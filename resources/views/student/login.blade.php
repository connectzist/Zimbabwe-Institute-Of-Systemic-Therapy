<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Sign In | CONNECT Portal</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/student-login.css') }}">

</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('images/Connect.logo.jpg') }}" alt="CONNECT Logo">
            <h1 class="h4 fw-bold mb-1">CONNECT PORTAL</h1>
            <p class="opacity-90 mb-0" style="font-size: 0.95rem;">Secure access to your account</p>
        </div>

        <div class="login-form">
            <!-- Messages-->
            @if ($errors->any())
                <div class="alert alert-danger text-start">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success text-start">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required autocomplete="email"
                               placeholder="Enter your email address" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label">Password</label>
                        <a href="{{ route('verify.email.received') }}" class="forgot-link">Forgot Password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-login w-100">SIGN IN</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-VkoKb2GzMJySAcDaR6NimjKOSLgGbDz1sMTAXL6ZSwr8ZGzIEUe6U5GZQxkXWl6I"
        crossorigin="anonymous"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = event.currentTarget.querySelector("i");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
    <script src="{{ asset('js/hideError.js') }}"></script>
</body>
</html>
