@extends('layouts.app')

@section('title', 'Akun Saya - SMK NEGERI 4 KOTA BOGOR')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Account.css') }}">
@endpush

@section('content')
<div class="account-page">
    <div class="account-container">
        <h1>Akun Saya</h1>
        <div class="account-info">
            <div class="account-avatar">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                </svg>
            </div>
            <div class="account-details">
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                <p class="account-meta">Bergabung: {{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('user.logout') }}" style="margin-top: 2rem;">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>
@endsection

