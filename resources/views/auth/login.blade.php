@extends('backend.layouts.blank')

@section('content')

<div class="h-100 bg-cover bg-center py-5 d-flex align-items-center" style="background-image: url({{ uploaded_asset(get_setting('admin_login_background')) }})">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-4 mx-auto">
                <div class="card text-left">
                    <div class="card-body">
                        <div class="mb-5 text-center">
                            <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" class="mw-100 mb-4" height="40">
                            <h1 class="h3 text-primary mb-0">{{ env('APP_NAME') }} Hoşgeldiniz.</h1>
                            <p>Hesabınıza giriş yapın.</p>
                        </div>
                        <form class="pad-hor" method="POST" role="form" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Eposta">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Şifre">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="text-left">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span>Beni Hatırla</span>
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                                @if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null)
                                    <div class="col-sm-6">
                                        <div class="text-right">
                                            <a href="{{ route('password.request') }}" class="text-reset fs-14">Şifrenizi mi unuttunuz?</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Giriş Yap</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
@endsection
