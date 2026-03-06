<div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
        <div class="app-logo"></div>
        <h4 class="mb-0">
            <span class="d-block">Bienvenid@,</span>
            <span>Inicia sesión en tu cuenta.</span>
        </h4>
        <h6 class="mt-3">¿Sin cuenta?
            <a href="{{ route('register') }}" class="text-primary">Regístrate ahora</a>
        </h6>
        <div class="divider row"></div>
        <div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input  name="email" 
                                    id="email" 
                                    placeholder="Correo electronico..." 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" 
                                    required autocomplete="email" autofocus>
                            
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input name="password" id="password" placeholder="Contraseña..." type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="position-relative form-check mb-3">
                    <input name="check" id="remember" name="remember" type="checkbox" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="form-label form-check-label">Recuérdame</label>
                </div>
                <div class="divider row"></div>
                <div class="d-flex align-items-center">
                    <div class="ms-auto">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Recuperar contraseña
                            </a>
                        @endif
                        <a href="{{ route('start') }}" class="btn btn-dark btn-lg">Inicio</a>
                        <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>