@extends('mobile.layout')

@section('header', 'Ganti Password')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-700 via-purple-800 to-pink-700 text-slate-100 flex items-center justify-center p-4">
  <div class="w-full max-w-sm rounded-2xl bg-white/5 backdrop-filter backdrop-blur-lg border border-white/10 p-6 shadow-xl">
    <h1 class="text-2xl font-bold mb-1 text-pink-200">Ganti Password</h1>
    <p class="text-sm text-slate-300 mb-6">Isi form berikut untuk mengganti password Anda.</p>

    {{-- Status message for success --}}
    @if (session('status'))
      <div class="bg-green-500/15 text-green-200 border border-green-500/30 text-sm p-3 rounded-xl mb-4">
        {{ session('status') }}
      </div>
    @endif

    {{-- Display validation errors --}}
    @if ($errors->any())
      <div class="bg-red-500/15 text-red-200 border border-red-500/30 text-sm p-3 rounded-xl mb-4">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('m.password.update') }}" class="space-y-4">
      @csrf

      {{-- Current Password --}}
      <div class="relative">
        <label class="block text-sm font-medium text-slate-200 mb-1">Password Lama</label>
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <!-- Icon -->
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 12.75V6a3.75 3.75 0 00-7.5 0v6.75m0 0H5.625A1.875 1.875 0 003.75 14.625v6.75A1.875 1.875 0 005.625 23.25h12.75a1.875 1.875 0 001.875-1.875v-6.75a1.875 1.875 0 00-1.875-1.875H8.25z" />
          </svg>
        </span>
        <input type="password" name="current_password" required
               class="w-full pl-10 rounded-xl bg-slate-900/40 border border-white/20 text-slate-100 placeholder:text-slate-500 p-3 focus:outline-none focus:ring-2 focus:ring-pink-500/40"
               placeholder="Password lama">
      </div>

      {{-- New Password --}}
      <div class="relative">
        <label class="block text-sm font-medium text-slate-200 mb-1">Password Baru</label>
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 12.75V6a3.75 3.75 0 00-7.5 0v6.75m0 0H5.625A1.875 1.875 0 003.75 14.625v6.75A1.875 1.875 0 005.625 23.25h12.75a1.875 1.875 0 001.875-1.875v-6.75a1.875 1.875 0 00-1.875-1.875H8.25z" />
          </svg>
        </span>
        <input type="password" name="password" required
               class="w-full pl-10 rounded-xl bg-slate-900/40 border border-white/20 text-slate-100 placeholder:text-slate-500 p-3 focus:outline-none focus:ring-2 focus:ring-pink-500/40"
               placeholder="Password baru">
      </div>

      {{-- Confirm New Password --}}
      <div class="relative">
        <label class="block text-sm font-medium text-slate-200 mb-1">Konfirmasi Password Baru</label>
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 12.75V6a3.75 3.75 0 00-7.5 0v6.75m0 0H5.625A1.875 1.875 0 003.75 14.625v6.75A1.875 1.875 0 005.625 23.25h12.75a1.875 1.875 0 001.875-1.875v-6.75a1.875 1.875 0 00-1.875-1.875H8.25z" />
          </svg>
        </span>
        <input type="password" name="password_confirmation" required
               class="w-full pl-10 rounded-xl bg-slate-900/40 border border-white/20 text-slate-100 placeholder:text-slate-500 p-3 focus:outline-none focus:ring-2 focus:ring-pink-500/40"
               placeholder="Ulangi password baru">
      </div>

      <button type="submit"
              class="w-full bg-gradient-to-r from-pink-500 to-indigo-600 hover:from-pink-600 hover:to-indigo-700 text-white p-3 rounded-xl font-semibold shadow-md transition-colors duration-200">
        Ubah Password
      </button>
    </form>
  </div>
</div>
@endsection