@extends('mobile.layout')

@section('content')
<div style="padding:16px;">
  <h2>Dashboard</h2>
  <p>Halo, {{ $user->name }}.</p>
  <p>Login terakhir: {{ $user->last_login_at ? $user->last_login_at->format('d-m-Y H:i:s') : 'Belum ada' }}</p>
  <ul>
    <li><a href="{{ route('m.absensi.index') }}">Absensi</a></li>
    <li><a href="{{ route('m.kasbon.index') }}">Kasbon</a></li>
    <li><a href="{{ route('m.absensi.history') }}">Riwayat Absensi</a></li>
    <li><a href="{{ route('m.password.edit') }}">Ganti Password</a></li>
    {{-- Tambahkan menu lain sesuai kebutuhan --}}
  </ul>

  <!-- Tombol logout -->
  <form method="POST" action="{{ route('m.logout') }}">
    @csrf
    <button type="submit"
      class="mt-4 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-medium">
      Logout
    </button>
  </form>
</div>
@endsection