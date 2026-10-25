@extends('theme::template')

@section('layout')
    <div class="es-wrap es-page">
        <main class="es-main es-main-full" id="konten">
            @yield('content')
        </main>
    </div>
@endsection
