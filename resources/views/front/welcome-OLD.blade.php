<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="./css/base.css">

        <style>
            * { 
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: 'Nunito', sans-serif;
                /* min-height: 100vh; */
            }

            .main-menu {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .base-link {
                background-color: #ff4600;
                border: none;
            }

            .base-link:hover,
            .base-link:active,
            .base-link:focus {
                background-color: #e53e00;
            }

            .container{
                display: flex;
                height: 100vh;
                align-items: center;
                justify-content: center;
            }

            .logo {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
        </style>
    </head>
    <body>
        <div class="main-menu">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary base-link">
                            Administrador
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary base-link">
                            Ingresar
                        </a>
    
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary base-link">
                                Registrar
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <div class="container">

            <div class="logo">
                <img src="/images/logo-mh.svg" alt="" style="width: 200px;" />
                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
    </body>
</html>
