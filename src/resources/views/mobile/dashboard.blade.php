@extends('mobile.layout')

@section('content')
<style>
  :root {
    --bg: #dfe3f3;
    --header-bg: #d9ddef;
    --sidebar-bg: #f7f7f8;
    --card-bg: #ffffff;
    --text: #111827;
    --muted: #6b7280;
    --primary: #4b7bec;
    --danger: #dc2626;
    --danger-dark: #b91c1c;
    --soft-box: #dfe4f7;
  }

  * {
    box-sizing: border-box;
  }

  .dashboard-mobile {
    min-height: 100vh;
    min-height: 100dvh;
    background: var(--bg);
    color: var(--text);
    font-family: Arial, Helvetica, sans-serif;
  }

  .dashboard-header {
    background: var(--header-bg);
    padding: max(16px, env(safe-area-inset-top)) 14px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .dashboard-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
  }

  .dashboard-brand {
    font-size: 18px;
    font-weight: 500;
    color: #111;
    white-space: nowrap;
  }

  .dashboard-back {
    width: 34px;
    height: 34px;
    border: none;
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    color: #111;
  }

  .dashboard-back svg,
  .dashboard-profile svg,
  .dashboard-search-icon svg {
    width: 22px;
    height: 22px;
    display: block;
  }

  .dashboard-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    justify-content: flex-end;
  }

  .dashboard-search {
    height: 34px;
    background: #fff;
    border-radius: 999px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 12px;
    min-width: 0;
    width: min(200px, 48vw);
    border: 1px solid rgba(0,0,0,0.04);
  }

  .dashboard-search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #111;
  }

  .dashboard-search input::placeholder {
    color: #6b7280;
  }

  .dashboard-profile {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #111;
    flex-shrink: 0;
  }

  .dashboard-body {
    display: grid;
    grid-template-columns: 72px 1fr;
    min-height: calc(100vh - 70px);
    min-height: calc(100dvh - 70px);
  }

  .dashboard-sidebar {
    background: var(--sidebar-bg);
    padding: 18px 10px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 26px;
    border-top-right-radius: 14px;
  }

  .side-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #111827;
    text-decoration: none;
    transition: transform 0.15s ease, background 0.2s ease;
  }

  .side-icon.active {
    background: #eef4ff;
    color: var(--primary);
  }

  .side-icon:hover {
    transform: translateY(-1px);
    background: #f0f4ff;
  }

  .side-icon svg {
    width: 24px;
    height: 24px;
    display: block;
  }

  .dashboard-main {
    padding: 16px 14px 22px;
  }

  .dashboard-card {
    background: var(--card-bg);
    border-radius: 22px;
    padding: 18px 16px 20px;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  }

  .dashboard-title {
    margin: 0 0 18px;
    font-size: 18px;
    font-weight: 500;
    text-transform: uppercase;
    color: #222;
  }

  .user-panel {
    background: var(--soft-box);
    border-radius: 18px;
    padding: 18px 16px;
    margin-bottom: 18px;
  }

  .user-panel-title {
    margin: 0 0 12px;
    font-size: 16px;
    font-weight: 700;
    color: #111827;
  }

  .user-panel p {
    margin: 0 0 10px;
    font-size: 14px;
    line-height: 1.55;
    color: #374151;
  }
  .change-password-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border-radius: 16px;
    background: #4b7bec;
    color: #fff;
    padding: 14px 16px;
    font-size: 14px;
    font-weight: 700;
    margin-top: 12px;
    transition: background 0.2s ease, transform 0.15s ease;
  }

  .change-password-btn:hover {
    background: #2446d8;
  }

  .change-password-btn:active {
    transform: scale(0.99);
  }
  .logout-form {
    margin-top: 10px;
  }

  .logout-btn {
    width: 100%;
    border: none;
    border-radius: 16px;
    background: var(--danger);
    color: #fff;
    padding: 15px 16px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease;
  }

  .logout-btn:hover {
    background: var(--danger-dark);
  }

  .logout-btn:active {
    transform: scale(0.99);
  }

  @media (max-width: 390px) {
    .dashboard-body {
      grid-template-columns: 64px 1fr;
    }

    .dashboard-sidebar {
      padding: 16px 8px 18px;
      gap: 22px;
    }

    .side-icon {
      width: 40px;
      height: 40px;
    }

    .dashboard-main {
      padding: 14px 10px 18px;
    }

    .dashboard-card {
      padding: 16px 12px 18px;
    }

    .dashboard-search {
      width: min(150px, 42vw);
    }
  }

  @media (min-width: 768px) {
    .dashboard-mobile {
      max-width: 430px;
      margin: 0 auto;
    }
  }
</style>

<div class="dashboard-mobile">
  <div class="dashboard-header">
    <div class="dashboard-header-left">
      <div class="dashboard-brand">RKU</div>

      <button type="button" class="dashboard-back" aria-label="Kembali">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </button>
    </div>

    <div class="dashboard-header-right">
      <div class="dashboard-search">
        <span class="dashboard-search-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="text" placeholder="Search">
      </div>

      <div class="dashboard-profile" aria-label="Profile">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v13A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-13Z"/>
          <circle cx="12" cy="10" r="3"/>
          <path d="M7.5 17c1.2-2 3-3 4.5-3s3.3 1 4.5 3"/>
        </svg>
      </div>
    </div>
  </div>

  <div class="dashboard-body">
    <aside class="dashboard-sidebar">
      <a href="{{ route('m.dashboard') }}" class="side-icon active" aria-label="Dashboard">
        <svg viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 3l8 7v10a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1V10l8-7z"/>
        </svg>
      </a>

      <a href="{{ route('m.absensi.index') }}" class="side-icon" aria-label="Absensi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="7" r="3.2"></circle>
          <path d="M5 20c.8-3.6 3.3-5.4 7-5.4S18.2 16.4 19 20"></path>
        </svg>
      </a>

      <a href="{{ route('m.absensi.history') }}" class="side-icon" aria-label="Riwayat Absensi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 19V5"></path>
          <path d="M10 19V10"></path>
          <path d="M16 19V7"></path>
          <path d="M22 19V12"></path>
        </svg>
      </a>

      <a href="{{ route('m.kasbon.index') }}" class="side-icon" aria-label="Kasbon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 1v22"></path>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6"></path>
        </svg>
      </a>

      <a href="{{ route('m.perizinan.index') }}" class="side-icon" aria-label="Perizinan">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
          <rect x="5" y="4" width="14" height="16" rx="2"></rect>
          <path d="M9 8h6"></path>
          <path d="M9 12h6"></path>
          <path d="M9 16h4"></path>
        </svg>
      </a>
    </aside>

    <main class="dashboard-main">
      <div class="dashboard-card">
        <h2 class="dashboard-title">Dashboard</h2>

        <div class="user-panel">
          <h3 class="user-panel-title">Users</h3>
          <p>Halo, {{ $user->name }}.</p>
          <p>
            Login terakhir:
            {{ $user->last_login_at ? $user->last_login_at->format('d-m-Y H:i:s') : 'Belum ada' }}
          </p>
        </div>
        <a href="{{ route('m.password.edit') }}" class="change-password-btn">
          Ganti Password
        </a>
        <form method="POST" action="{{ route('m.logout') }}" class="logout-form">
          @csrf
          <button type="submit" class="logout-btn">
            Logout
          </button>
        </form>
      </div>
    </main>
  </div>
</div>
@endsection