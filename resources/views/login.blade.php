<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('templateLogin/images/icons/favicon.ico') }}" />

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('templateLogin/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('templateLogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('templateLogin/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('templateLogin/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('templateLogin/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('templateLogin/vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('templateLogin/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('templateLogin/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('templateLogin/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('templateLogin/css/main.css') }}">
</head>

<body style="background-color: #666666;">

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <span class="login100-form-title p-b-43">
                        Login to continue
                    </span>
                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-bottom: 20px;">
                            <ul style="margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="email" name="email" required>
                        <span class="focus-input100"></span>
                        <span class="label-input100">Email</span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" required>
                        <span class="focus-input100"></span>
                        <span class="label-input100">Password</span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>

                </form>

                <div class="login100-more"
                    style="background-image: url('{{ asset('templateLogin/images/bg-01.jpg') }}');">
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('templateLogin/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('templateLogin/vendor/animsition/js/animsition.min.js') }}"></script>
    <script src="{{ asset('templateLogin/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('templateLogin/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('templateLogin/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('templateLogin/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('templateLogin/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('templateLogin/vendor/countdowntime/countdowntime.js') }}"></script>
    <script src="{{ asset('templateLogin/js/main.js') }}"></script>

</body>

</html>
