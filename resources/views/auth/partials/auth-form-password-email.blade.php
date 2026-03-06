<div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9 px-4">
        <div class="app-logo"></div>
        <h4 class="mb-0">
            <span class="d-block">¿Olvidaste tu contraseña?,</span>
            <span>Utiliza el siguiente formulario para recuperarla.</span>
        </h4>
        <h6 class="mt-3">
            <a href="{{ route('login') }}" class="text-primary">Ingresa con una cuenta existente</a>
        </h6>
        <div class="divider row"></div>
        <div>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="">
                    <div class="col-md-12">
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
                </div>
                <div class="divider row"></div>
                <div class="d-flex align-items-center">
                    <div class="ms-auto">
                        <a href="{{ route('start') }}" class="btn btn-dark btn-lg">Inicio</a>
                        <button type="submit" class="btn btn-primary btn-lg">Recuperar contraseña</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>