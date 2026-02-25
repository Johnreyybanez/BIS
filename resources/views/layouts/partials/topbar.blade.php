<header class="topbar">
  <div class="topbar-l">
    <button class="btn-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars" id="toggleIcon"></i>
    </button>
    <h1 class="page-title">@yield('title')</h1>
  </div>

  <div class="topbar-r">

    <!-- Bell -->
    <div class="dropdown">
      <div class="tb-btn" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if(isset($unreadNotifications) && $unreadNotifications > 0)
          <span class="notif-dot"></span>
        @endif
      </div>
      <div class="dropdown-menu dropdown-menu-end dd-menu" style="width:305px">
        <div style="padding:4px 8px 10px;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;display:flex;align-items:center;justify-content:space-between">
          Notifications
          @if(isset($unreadNotifications) && $unreadNotifications > 0)
            <span style="font-size:10.5px;background:var(--danger);color:#fff;padding:2px 9px;border-radius:100px;font-weight:600">{{ $unreadNotifications }} new</span>
          @endif
        </div>
        <div style="max-height:260px;overflow-y:auto">
          @forelse($notifications ?? [] as $notification)
            <a class="dropdown-item" href="{{ $notification->link ?? '#' }}">
              <div style="font-size:13px;line-height:1.4;color:var(--text)">{{ $notification->message }}</div>
              <div style="font-size:11px;color:var(--muted);margin-top:3px">{{ $notification->created_at->diffForHumans() }}</div>
            </a>
          @empty
            <div style="text-align:center;padding:22px 12px;color:var(--muted);font-size:13px">
              <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;opacity:.3"></i>
              No new notifications
            </div>
          @endforelse
        </div>
        @if(isset($notifications) && $notifications->count() > 0)
        <div class="dropdown-divider my-1"></div>
        <a class="dropdown-item" href="{{ route('notifications.index') }}" style="text-align:center;color:var(--primary)">
          View All Notifications
        </a>
        @endif
      </div>
    </div>

    <!-- User chip -->
    <div class="dropdown">
      <div class="user-chip" data-bs-toggle="dropdown" aria-expanded="false">

        {{-- Avatar: photo if exists, otherwise initial --}}
        @if(Auth::check() && Auth::user()->profile_photo)
          <img
            src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
            alt="{{ Auth::user()->full_name }}"
            class="user-av user-av-img"
          >
        @else
          <div class="user-av">
            {{ Auth::check() ? strtoupper(substr(Auth::user()->full_name, 0, 1)) : 'U' }}
          </div>
        @endif

        <span class="chip-name">{{ Auth::user()->full_name ?? 'User' }}</span>
        <i class="fas fa-chevron-down chip-caret"></i>
      </div>

      <div class="dropdown-menu dropdown-menu-end dd-menu" style="min-width:220px">

        {{-- User info header with photo --}}
        <div class="dropdown-header" style="display:flex;align-items:center;gap:10px;padding:10px 14px">
          @if(Auth::check() && Auth::user()->profile_photo)
            <img
              src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
              alt="{{ Auth::user()->full_name }}"
              style="width:38px;height:38px;border-radius:10px;object-fit:cover;flex-shrink:0;border:1px solid var(--border)"
            >
          @else
            <div style="width:38px;height:38px;border-radius:10px;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:15px;flex-shrink:0">
              {{ Auth::check() ? strtoupper(substr(Auth::user()->full_name, 0, 1)) : 'U' }}
            </div>
          @endif
          <div>
            <div style="font-weight:600;color:var(--text);font-size:13.5px">{{ Auth::user()->full_name ?? 'User' }}</div>
            <div style="font-size:11px;color:var(--muted)">{{ Auth::user()->email ?? Auth::user()->username ?? '' }}</div>
          </div>
        </div>

        <div class="dropdown-divider"></div>

        @if(Route::has('profile.show'))
        <a class="dropdown-item" href="{{ route('users.index') }}">
          <i class="fas fa-circle-user me-2" style="color:var(--primary);width:18px"></i>Profile
        </a>
        @endif

        @if(Route::has('settings'))
        <a class="dropdown-item" href="{{ route('settings') }}">
          <i class="fas fa-gear me-2" style="color:var(--muted);width:18px"></i>Settings
        </a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="dropdown-item" style="color:var(--danger)">
            <i class="fas fa-arrow-right-from-bracket me-2" style="width:18px"></i>Sign Out
          </button>
        </form>

      </div>
    </div>

  </div>
</header>

{{-- ── Extra styles for photo avatar ── --}}
<style>
.user-av-img {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    object-fit: cover;
    border: 1.5px solid var(--border);
    flex-shrink: 0;
}
</style>