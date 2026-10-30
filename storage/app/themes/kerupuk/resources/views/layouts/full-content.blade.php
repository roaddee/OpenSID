@extends('theme::template')

@section('layout')
    <div class="kr-wrap kr-page">
        <main class="kr-main kr-main-full" id="konten">
            @yield('content')
        </main>
    </div>
@endsection
