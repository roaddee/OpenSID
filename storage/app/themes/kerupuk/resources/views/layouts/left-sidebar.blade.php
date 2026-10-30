@extends('theme::template')

@section('layout')
    <div class="kr-wrap kr-page kr-page-split kr-page-split-left">
        <div class="kr-side">
            @include('theme::partials.sidebar')
        </div>
        <main class="kr-main" id="konten">
            @yield('content')
        </main>
    </div>
@endsection
