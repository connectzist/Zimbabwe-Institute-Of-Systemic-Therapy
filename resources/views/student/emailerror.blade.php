<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Error</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 100px;
        }
        .error-message {
            padding: 20px;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
            text-align: center;
        }
        .back-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="error-message">
        <h2>Invalid Login Page</h2>
        <p>{{ $message }}</p>
    </div>
</div>

</body>
</html>
