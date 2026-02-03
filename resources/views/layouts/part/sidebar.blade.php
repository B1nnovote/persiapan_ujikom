<aside class="quix-sidebar" id="quixSidebar">
    <div class="brand-section">
        <a href="#" class="logo-wrap">
            <img src="{{ asset('assets/backend/img/logo-parkir.svg') }}" alt="Logo" class="logo">
            <span class="brand-text">Lajur.id</span>
        </a>
    </div>

    <nav class="menu-list">
        <ul>

            <li
                class="{{ request()->routeIs('frontend.index') || request()->routeIs('backend.index') ? 'active' : '' }}">
                <a href="{{ Auth::user()->isAdmin ? route('backend.index') : route('frontend.index') }}">
                    <i class='bx bxs-dashboard'></i> <span>Dashboard</span>
                </a>
            </li>

            {{-- PEMBATAS --}}
            <li class="divider"></li>

            {{-- MENU KHUSUS ADMIN & PETUGAS --}}
            @if (Auth::user()->isAdmin)
                <li class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                    <a href="{{ route('petugas.index') }}"><i class='bx bx-user'></i> <span>Data Petugas</span></a>
                </li>
            @endif

            <li class="{{ request()->routeIs('datakendaraan.*') ? 'active' : '' }}">
                <a href="{{ route('datakendaraan.index') }}"><i class='bx bxs-car'></i> <span>Data Kendaraan</span></a>
            </li>
            <li class="{{ request()->routeIs('kendaraanmasuk.*') ? 'active' : '' }}">
                <a href="{{ route('kendaraanmasuk.index') }}"><i class='bx bx-log-in'></i> <span>Kendaraan
                        Masuk</span></a>
            </li>
            <li class="{{ request()->routeIs('kendaraankeluar.*') ? 'active' : '' }}">
                <a href="{{ route('kendaraankeluar.index') }}"><i class='bx bx-log-out'></i> <span>Kendaraan
                        Keluar</span></a>
            </li>
            @if (Auth::user()->isAdmin)
                <li class="{{ request()->routeIs('kompensasi.*') ? 'active' : '' }}">
                    <a href="{{ route('kompensasi.index') }}"><i class='bx bx-wallet'></i> <span>Kompensasi</span></a>
                </li>
            @endif
            <li class="{{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                <a href="{{ route('pembayaran.index') }}"><i class='bx bx-credit-card'></i>
                    <span>Pembayaran</span></a>
            </li>
            <li class="{{ request()->routeIs('stok.*') ? 'active' : '' }}">
                <a href="{{ route('stok.index') }}"><i class='bx bxs-box'></i> <span>Stok Lahan</span></a>
            </li>
            @if (Auth::user()->isAdmin)
                <li class="{{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
                    <a href="{{ route('keuangan.index') }}"><i class='bx bx-wallet'></i> <span>Keuangan</span></a>
                </li>
            @endif
        </ul>
    </nav>
    <br>
    <form id="logout-form" align="center" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-logout">
            <i class='bx bx-power-off'></i> Logout
        </button>
    </form>

</aside>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
    }

    .quix-sidebar {
        width: 240px;
        background: linear-gradient(180deg, #6a11cb 0%, #2575fc 100%);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        transition: all 0.3s ease-in-out;
        overflow-y: auto;
        z-index: 999;
        color: white;
    }

    .quix-sidebar.collapsed {
        width: 70px;
    }

    .brand-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
    }

    .logo-wrap {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: white;
    }

    .logo {
        height: 34px;
        margin-right: 10px;
    }

    .brand-text {
        font-size: 1.2rem;
        font-weight: 600;
        white-space: nowrap;
        transition: 0.3s ease;
    }

    .toggle-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 22px;
        cursor: pointer;
    }

    .menu-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu-list ul li {
        margin: 0;
    }

    .menu-list ul li a {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: #f0f0f0;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .menu-list ul li a i {
        margin-right: 12px;
        font-size: 18px;
    }

    .menu-list ul li a span {
        transition: 0.2s;
    }

    .quix-sidebar.collapsed .brand-text,
    .quix-sidebar.collapsed a span {
        display: none;
    }

    .menu-list ul li.active a,
    .menu-list ul li a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        font-weight: 600;
        border-left: 4px solid white;
    }

    .quix-sidebar .divider {
        height: 1px;
        background-color: rgba(255, 255, 255, 0.2);
        margin: 0.75rem 1rem;
    }

    .user-footer {
        padding: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
    }

    .user-profile {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin-bottom: 10px;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid white;
        margin-bottom: 5px;
    }

    .user-name {
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .btn-logout {
        background-color: rgba(255, 255, 255, 0.15);
        border: none;
        color: #fff;
        padding: 20px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        width: 90%;
    }

    .btn-logout:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }
</style>

{{-- ===== TOGGLE SIDEBAR SCRIPT ===== --}}
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('quixSidebar').classList.toggle('collapsed');
    });
</script>
@include('sweetalert::alert')
