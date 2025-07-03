<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Login</title>
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="color-scheme" content="light dark">
        <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
        <meta name="title" content="AdminLTE 4 | Login Page">
        <meta name="author" content="ColorlibHQ">
        <meta name="description"
            content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance.">
        <meta name="keywords"
            content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant">
        <meta name="supported-color-schemes" content="light dark"> --}}
        <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style">
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
            integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
            onload="this.media='all'">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
            crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
            crossorigin="anonymous"> --}}
        <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
    </head>

    <body class="login-page bg-body-secondary">
        <div class="login-box">
            <div class="login-logo"> Login </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <form action="{{ route('login') }}" method="post">@csrf

                        <div class="input-group mb-3">
                            {{-- <input type="email" name="email" class="form-control" placeholder="Email"> --}}
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email"
                                autofocus>
                            <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="input-group mb-3">
                            {{-- <input type="password" name="password" class="form-control" placeholder="Password"> --}}
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" value="{{ old('password') }}" placeholder="Password"
                                autofocus>
                            <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <div class="form-check"> <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid gap-2"> <button type="submit" class="btn btn-primary">Sign
                                        In</button> </div>
                            </div>
                        </div>
                    </form>
                    <p class="mb-0"> <a href="{{ url('register') }}" class="text-center">
                            Don't have account
                        </a> </p>
                </div>
            </div>
        </div>

    </body>

</html>
