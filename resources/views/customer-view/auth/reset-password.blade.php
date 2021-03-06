@extends('layouts.front-end.app')

@section('title','Formatear Contraseña')

@push('css_or_js')
    <style>
        .text-primary{
            color: {{$web_config['primary_color']}} !important;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5 my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <h2 class="h3 mb-4">¿Olvidaste tu contraseña?</h2>
                <p class="font-size-md">Cambie su contraseña en dos sencillos pasos. Esto ayuda a mantener su nueva contraseña
                     seguro.</p>
                <ol class="list-unstyled font-size-md">
                    <li><span class="text-primary mr-2">1.</span>Nueva contraseña.</li>
                    <li><span class="text-primary mr-2">2.</span>Confirmar Contraseña.</li>
                </ol>
                <div class="card py-2 mt-4">
                    <form class="card-body needs-validation" novalidate method="POST"
                          action="{{request('customer.auth.password-recovery')}}">
                        @csrf
                        <div class="form-group" style="display: none">
                            <input type="text" name="reset_token" value="{{$token}}" required>
                        </div>

                        <div class="form-group">
                                <label for="si-password">{{trans('messages.New')}}{{trans('messages.password')}}</label>
                                <div class="password-toggle">
                                    <input class="form-control" name="password" type="password" id="si-password"
                                           required>
                                    <label class="password-toggle-btn">
                                        <input class="custom-control-input" type="checkbox"><i
                                            class="czi-eye password-toggle-indicator"></i><span
                                            class="sr-only">{{trans('messages.Show')}} {{trans('messages.password')}} </span>
                                    </label>
                                    <div class="invalid-feedback">Proporcione una contraseña válida.</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="si-password">{{trans('messages.confirm_password')}}</label>
                                <div class="password-toggle">
                                    <input class="form-control" name="confirm_password" type="password" id="si-password"
                                           required>
                                    <label class="password-toggle-btn">
                                        <input class="custom-control-input" type="checkbox"><i
                                            class="czi-eye password-toggle-indicator"></i><span
                                            class="sr-only">{{trans('messages.Show')}} {{trans('messages.password')}} </span>
                                    </label>
                                    <div class="invalid-feedback">Proporcione una contraseña válida.</div>
                                </div>
                            </div>

                        <button class="btn btn-primary" type="submit">Restablecer la contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
