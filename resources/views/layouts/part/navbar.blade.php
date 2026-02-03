<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
</head>
<style>
    body {
        margin-left: 243px;
        padding: 0;
        font-family: sans-serif;
    }

    /* Biar semua konten kecuali sidebar ke kanan dikit */
    .main-wrapper {
        margin-left: 240px;
        /* Sama kayak width sidebar */
    }

    /* Navbar styling */
    .navbar {
        background: white;
        padding: 10px 20px;
        width: 100%;
        position: sticky;
        top: 0;
        left: 0;
        z-index: 998;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
</style>

<body>
    <div class="body-wrapper">
        <nav class="navbar">
            <a href="#" class="nav-link"></a>
            <form action="#">
                <div class="form-input">
                </div>
            </form>
            <a href="#" class="notification">
            </a>
            <a href="#" class="profile">
                @if (Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil" 
                    width="35px" height="35px" style="border-radius:100%;">
                @else
                    <img src="{{ asset('assets\backend\img\profile.png') }}" alt="Default"
                         width="35px" height="35px" style="border-radius:100%">
                @endif
            </a>
        </nav>
    </div>


    @include('sweetalert::alert')

</body>

</html>
