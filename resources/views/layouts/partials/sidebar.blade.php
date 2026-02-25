<aside class="sidebar" id="sidebar">

  <div class="sb-brand">
    <div class="sb-logo">B</div>
    <div class="sb-brand-info">
      <div class="sb-brand-name">Barangay IS</div>
      <div class="sb-brand-sub">Information System</div>
    </div>
  </div>

  <div class="sb-body">
    {{-- Main Section --}}
    <div class="sb-section">Main</div>

    <a href="{{ route('dashboard') }}"
       class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-gauge-high"></i></span>
      <span class="lbl">Dashboard</span>
      <span class="sb-tip">Dashboard</span>
    </a>

    <a href="{{ route('residents.index') }}"
       class="sb-link {{ request()->routeIs('residents.*') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-users"></i></span>
      <span class="lbl">Residents</span>
      <span class="sb-tip">Residents</span>
    </a>

    <a href="{{ route('households.index') }}"
       class="sb-link {{ request()->routeIs('households.*') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-house"></i></span>
      <span class="lbl">Households</span>
      <span class="sb-tip">Households</span>
    </a>

    {{-- Services Section --}}
    <div class="sb-section">Services</div>

    <a href="{{ route('officials.index') }}"
       class="sb-link {{ request()->routeIs('officials.*') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-user-tie"></i></span>
      <span class="lbl">Officials</span>
      <span class="sb-tip">Officials</span>
    </a>

    <a href="{{ route('certificate-types.index') }}"
       class="sb-link {{ request()->routeIs('certificate-types.*') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-tags"></i></span>
      <span class="lbl">Certificate Types</span>
      <span class="sb-tip">Certificate Types</span>
    </a>

    <a href="{{ route('certificates.index') }}"
       class="sb-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-file"></i></span>
      <span class="lbl">Certificates</span>
      <span class="sb-tip">Certificates</span>
    </a>

    <a href="{{ route('blotters.index') }}"
       class="sb-link {{ request()->routeIs('blotters.*') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-book-open"></i></span>
      <span class="lbl">Blotter Records</span>
      <span class="sb-tip">Blotter Records</span>
    </a>

    {{-- Reports Link --}}
    <a href="/reports"
       class="sb-link {{ request()->is('reports*') ? 'active' : '' }}">
      <span class="ico"><i class="fas fa-chart-bar"></i></span>
      <span class="lbl">Reports</span>
      <span class="sb-tip">Reports</span>
    </a>

   @if(Auth::check() && Auth::user()->role->role_name == 'Admin')
<div class="sb-section">Admin</div>
<a href="{{ route('users.index') }}"
   class="sb-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
  <span class="ico"><i class="fas fa-user-gear"></i></span>
  <span class="lbl">User Management</span>
  <span class="sb-tip">Users</span>
</a>
@endif

</aside>