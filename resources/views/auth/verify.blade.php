@extends('frontend.layouts.app')

@section('content')
<div class="py-6">
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-xl-6 col-md-8 mx-auto">
                <div class="bg-white rounded shadow-sm p-4 text-left">
                    <h1 class="h3 fw-600 mb-3">Email adresinizi doğrulayın</h1>
                    <p class="opacity-60">
                        Devam etmeden önce, lütfen bir doğrulama bağlantısı için e-postanızı kontrol edin.<br>
                        E-postayı doğrulaması almadıysanız.
                    </p>
                    <a href="{{ route('verification.resend') }}" class="btn btn-primary btn-block">Başka  bir doğrulama isteği için burayı tıklayın</a>
                    @if (session('resent'))
                        <div class="alert alert-success mt-2 mb-0" role="alert">E-posta adresinize yeni bir doğrulama bağlantısı gönderildi.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
