<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>404</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
</head>

<body>
    <div class="container text-center mt-5">
        <h1 class="display-1 text-danger">404</h1>
        <p class="lead">Oops! Halaman yang kamu cari tidak ditemukan.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
    </div>

</body>

</html>
