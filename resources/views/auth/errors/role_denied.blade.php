<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Access Denied</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>

<body>
  <div class="card">
    <img
      src="{{ asset('images/Connect.logo.jpg') }}"
      alt="Logo"
      class="error-logo"
    />

    <h2 class="text-danger fw-bold">Access Denied</h2>

    <p class="mt-3" style="font-size: 16px;">
      {{ $message }}
    </p>

    <a href="{{ route('admin_login') }}" class="btn btn-custom mt-3">
      Return to Login
    </a>

    <p class="tracking-note">
      Kindly note that this information is being sent to
      <strong>CONNECT ZIST</strong> for tracking purposes.
    </p>
  </div>
</body>
</html>
