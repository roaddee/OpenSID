@extends('theme::template')

@section('layout')
    <div class="es-wrap es-page es-page-split es-page-split-left">
        <div class="es-side">
            @include('theme::partials.sidebar')
        </div>
        <main class="es-main" id="konten">
            @yield('content')
        </main>
    </div>
@endsection
