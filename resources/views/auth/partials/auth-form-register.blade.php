<div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
    <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9 px-4">
        <div class="app-logo"></div>
        <h4 class="mb-0">
            <span class="d-block">Bienvenid@,</span>
            <span>Solo toma unos segundos crear tu cuenta.</span>
        </h4>
        <h6 class="mt-3">¿Ya tienes una cuenta?
            <a href="{{ route('login') }}" class="text-primary">Ingresa ahora</a>
        </h6>
        <div class="divider row"></div>
        <div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <input id="company_id" name="company_id" type="hidden" value="2">
                        <div class="position-relative mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input  name="name" 
                                    id="name" 
                                    placeholder="Nombre..." 
                                    type="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" 
                                    required autocomplete="name" autofocus>
                            
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

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

                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                            <input name="password_confirmation" id="password-confirm" placeholder="Confirmar contraseña..." type="password" class="form-control" required autocomplete="new-password">
                        </div>
                    </div>
                    
                </div>

                <div class="divider row"></div>
                <div class="d-flex align-items-center">
                    <div class="ms-auto">
                        <a href="{{ route('start') }}" class="btn btn-dark btn-lg">Inicio</a>
                        <button type="submit" class="btn btn-primary btn-lg">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>