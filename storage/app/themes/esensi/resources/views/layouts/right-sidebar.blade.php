@extends('theme::template')

@section('layout')
    <div class="es-wrap es-page es-page-split">
        <main class="es-main" id="konten">
            @yield('content')
        </main>
        <div class="es-side">
            @include('theme::partials.sidebar')
        </div>
    </div>
@endsection
