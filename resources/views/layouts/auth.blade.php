<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .auth-card {
            max-width: 420px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        .brand {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 30px;
            color: #0d6efd;
        }

        .auth-bg {
            min-height: 100vh;
            background: linear-gradient(-45deg,
                    #147ca8,
                    #358eac,
                    #152a33,
                    #1e3c72);
            background-size: 400% 400%;
            animation: gradientMove 10s ease infinite;
        }

        /* animasi gradient */
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .card {
            animation: fadeUp 1s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="auth-bg">
    <div class="container">
        @yield('content')
    </div>
</body>

</html>
