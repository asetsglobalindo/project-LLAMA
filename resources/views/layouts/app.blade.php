<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="Asets, properti, investasi, pengelolaan properti, solusi properti, Indonesia" />
    <meta name='dmca-site-verification' content='VElpOFl2M096NW1EWEp0Z1JoT3AwUT090' />

    <title>Asets - @yield('title')</title>

    <!-- Ganti link Google Fonts yang ada -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.6/dist/cdn.min.js"></script>

    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-057LB4FWDX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-057LB4FWDX');
    </script>
</head>

<body class="poppins-regular">
    <main>
        <div>
            @if (!isset($hideNavbar) || !$hideNavbar)
                <header>
                    <x-navbar />
                </header>
            @endif
            @yield('content')
            <x-footer />
        </div>
        <x-cookie-consent />
    </main>

    @if (session('successLogin'))
        <script>
            Swal.fire({
                imageUrl: '/assets/img/logo-asets.png',
                imageWidth: 180,
                imageAlt: 'Logo asets',
                title: 'Welcome to asets',
                text: 'Start exploring the best features we have crafted to support your digital needs.',
                showCloseButton: true,
                showConfirmButton: false,
                timer: 6000,
            });
        </script>
    @endif

    @if (session('successLogout'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Logout success',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 3000,
            });
        </script>
    @endif

    @stack('scripts')
    <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"></script>
</body>

</html>
