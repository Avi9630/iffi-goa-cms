<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="color-scheme" content="light dark">
        <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
        <meta name="title" content="AdminLTE 4 | Register Page">
        <meta name="author" content="ColorlibHQ">
        <meta name="description"
            content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance.">
        <meta name="keywords"
            content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant">
        <meta name="supported-color-schemes" content="light dark">
        <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
            integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
            onload="this.media='all'">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
            crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
            crossorigin="anonymous">

        <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
    </head>

    <body class="register-page bg-body-secondary">
        <div class="register-box">
            <div class="register-logo"> Register </div>
            <div class="card">
                <div class="card-body register-card-body">
                    <p class="register-box-msg">Register a new membership</p>
                    <span>
                        <h4 class="alert-danger"></h4>
                    </span>
                    @foreach (['success', 'info', 'danger', 'warning'] as $msg)
                        @if (Session::has($msg))
                            <div id="flash-message" class="alert alert-{{ $msg }}" role="alert">
                                {{ Session::get($msg) }}
                            </div>
                        @endif
                    @endforeach
                    <form action="{{ route('register') }}" method="POST">
                        @csrf @method('post')

                        <div class="input-group mb-3">
                            <input type="text" name="name"
                                class="form-control form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Enter your full name" autofocus>
                            <div class="input-group-text"> <span class="bi bi-person"></span> </div>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="email" name="email"
                                class="form-control form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Enter your email">
                            <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="text" name="mobile"
                                class="form-control form-control @error('mobile') is-invalid @enderror"
                                value="{{ old('mobile') }}" placeholder="Enter your mobile">
                            <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password"
                                class="form-control form-control @error('password') is-invalid @enderror"
                                placeholder="password">
                            <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password_confirmation"
                                class="form-control form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="Confirm password">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-8">
                            </div>
                            <div class="col-4">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    {{-- <div class="social-auth-links text-center mb-3 d-grid gap-2">
                        <p>- OR -</p> <a href="#" class="btn btn-primary"> <i class="bi bi-facebook me-2"></i>
                            Sign in using Facebook
                        </a> <a href="#" class="btn btn-danger"> <i class="bi bi-google me-2"></i> Sign in using
                            Google+
                        </a>
                    </div> --}}
                    <p class="mb-0"> <a href="{{ url('login') }}" class="text-center">
                            I already have a membership
                        </a> </p>
                </div>
            </div>
        </div>
    </body>

</html>
