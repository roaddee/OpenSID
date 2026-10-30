@extends('theme::template')

@section('layout')
    <div class="kr-wrap kr-page kr-page-split">
        <main class="kr-main" id="konten">
            @yield('content')
        </main>
        <div class="kr-side">
            @include('theme::partials.sidebar')
        </div>
    </div>
@endsection
