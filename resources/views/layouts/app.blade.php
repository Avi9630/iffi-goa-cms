@include('layouts.main')

@include('layouts.navbar')

@include('layouts.sidebar')

<main class="app-main">
    @yield('content')
</main>

@include('layouts.footer')
