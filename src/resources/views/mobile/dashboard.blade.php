@extends('mobile.layout')

@section('content')
<div style="padding:16px;">
  <h2>Dashboard</h2>
  <p>Halo, {{ $user->name }}.</p>
  <p>Login terakhir: {{ $user->last_login_at ? $user->last_login_at->format('d-m-Y H:i:s') : 'Belum ada' }}</p>
  <ul>
    <li><a href="{{ route('m.absensi.index') }}">Absensi</a></li>
    <li><a href="{{ route('m.kasbon.index') }}">Kasbon</a></li>
    {{-- Tambahkan menu lain sesuai kebutuhan --}}
  </ul>
</div>
@endsection