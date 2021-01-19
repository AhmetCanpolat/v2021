@extends('frontend.layouts.app')

@section('content')

<div class="py-6">
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-xl-6 col-md-8 mx-auto">
                <div class="bg-white rounded shadow-sm p-4 text-left">
                    <h1 class="h3 fw-600">Şifrenizi mi unuttunuz?</h1>
                    <p class="mb-4 opacity-60">Şifrenizi kurtarmak için e-posta adresinizi girin.</p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Eposta ya da telefon">
                            @else
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Eposta" name="email">
                            @endif

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group text-right">
                            <button class="btn btn-primary btn-block" type="submit">Şifre Sıfırlama Bağlantısını Gönder</button>
                        </div>
                    </form>
                    <div class="mt-3">
                        <a href="{{route('user.login')}}" class="text-reset opacity-60">Giriş Sayfasına Dön</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
