@extends('layouts.front-end.app')
@section('title','Recuperar Contraseña')
@push('css_or_js')
    <style>
        .text-primary{
            color: {{$web_config['primary_color']}} !important;
        }
    </style>
@endpush
@section('content')
    <!-- Page Content-->
    <div class="container py-4 py-lg-5 my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <h2 class="h3 mb-4">¿Olvidaste tu contraseña?</h2>
                <p class="font-size-md">Cambie su contraseña en tres sencillos pasos. Esto ayuda a mantener su nueva contraseña
                     seguro.</p>
                <ol class="list-unstyled font-size-md">
                    <li><span class="text-primary mr-2">1.</span>Complete su dirección de correo electrónico abajo.</li>
                    <li><span class="text-primary mr-2">2.</span>Le enviaremos un código temporal por correo electrónico.</li>
                    <li><span class="text-primary mr-2">3.</span>Utilice el código para cambiar su contraseña en nuestro sitio web seguro.
                    </li>
                </ol>
                <div class="card py-2 mt-4">
                    <form class="card-body needs-validation" action="{{route('customer.auth.forgot-password')}}"
                          method="post">
                        @csrf
                        <div class="form-group">
                            <label for="recover-email">Ingrese su dirección de correo electrónico</label>
                            <input class="form-control" type="email" name="email" id="recover-email" required>
                            <div class="invalid-feedback">Proporcione una dirección de correo electrónico válida.</div>
                        </div>
                        <button class="btn btn-primary" type="submit">Obtener nueva contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
