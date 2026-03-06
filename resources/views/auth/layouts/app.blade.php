<!doctype html>
<html lang="es">
    <head>
        @include('auth.partials.auth-head')
    </head>
    <body>
        <main>
            @yield('content')
        </main>

        @include('auth.partials.auth-scripts')
    </body>
</html>
