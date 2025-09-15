<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Noticed</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
            text-align: center;
            padding: 20px;
            margin-top: -200px;
        }

        h1 {
            font-size: 36px;
            color: #f44336; /* error message */
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

        .error-box {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
        }

        .error-box h1 {
            margin-top: 0;
        }

        /* Small screen adjustments */
        @media (max-width: 600px) {
            h1 {
                font-size: 28px;
            }

            p {
                font-size: 16px;
            }

            a {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-box">
            <img src="{{ asset('images/Connect.logo.jpg') }}" alt="Logo" class="error-logo">
            <h1>Access Denied</h1>
            <p>{{ $message }}</p>
            <a href="{{ route('student.login') }}">Back to Login</a>
        </div>
    </div>
</body>
</html>
