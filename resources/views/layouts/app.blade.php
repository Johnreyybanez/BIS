<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Barangay IS — @yield('title')</title>
<link rel="icon" type="image/png" href="{{ asset('images/end.jpg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
/* ─────────────────────────────────────────
   TOKENS
───────────────────────────────────────── */
:root{
  --sw:260px;
  --sc:68px;
  --th:60px;
  --sidebar:#0d1321;
  --primary:#4f63d2;
  --plt:#eef0fd;
  --bg:#f0f2f8;
  --text:#1a1f36;
  --muted:#6b7399;
  --surface:#fff;
  --border:#e4e7f0;
  --danger:#ff4d6d;
  --radius:14px;
  --rsm:9px;
  --ease:cubic-bezier(.4,0,.2,1);
  --dur:.25s;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden}

/* ─────────────────────────────────────────
   SHELL
───────────────────────────────────────── */
.app-shell{display:flex;min-height:100vh}

/* ─────────────────────────────────────────
   OVERLAY
───────────────────────────────────────── */
.sb-overlay{
  position:fixed;inset:0;background:rgba(0,0,0,.55);
  z-index:1040;backdrop-filter:blur(3px);
  opacity:0;pointer-events:none;
  transition:opacity var(--dur) var(--ease);
}
.sb-overlay.show{opacity:1;pointer-events:auto}

/* ─────────────────────────────────────────
   SIDEBAR
───────────────────────────────────────── */
.sidebar{
  position:fixed;top:0;left:0;bottom:0;
  width:var(--sw);
  background:var(--sidebar);
  display:flex;flex-direction:column;
  z-index:1050;overflow:hidden;
  transition:width var(--dur) var(--ease), transform var(--dur) var(--ease);
}
.sidebar.collapsed{width:var(--sc)}

/* ── Brand ── */
.sb-brand{
  display:flex;align-items:center;gap:12px;
  padding:0 16px;height:var(--th);
  border-bottom:1px solid rgba(255,255,255,.07);
  flex-shrink:0;overflow:hidden;white-space:nowrap;
}
.sb-logo{
  width:38px;height:38px;border-radius:10px;
  background:var(--primary);flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-family:'Syne',sans-serif;font-weight:800;font-size:18px;color:#fff;
}
.sb-brand-info{overflow:hidden;transition:opacity var(--dur) var(--ease),width var(--dur) var(--ease)}
.sb-brand-name{font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:#fff;line-height:1.2}
.sb-brand-sub{font-size:10px;color:rgba(255,255,255,.35)}
.sidebar.collapsed .sb-brand-info{opacity:0;width:0}

/* ── Nav body ── */
.sb-body{flex:1;overflow-y:auto;overflow-x:hidden;padding:8px 10px;scrollbar-width:none}
.sb-body::-webkit-scrollbar{display:none}

.sb-section{
  font-size:9.5px;font-weight:700;letter-spacing:.13em;text-transform:uppercase;
  color:rgba(255,255,255,.25);padding:16px 8px 5px;
  white-space:nowrap;overflow:hidden;
  transition:opacity var(--dur),padding var(--dur);
}
.sidebar.collapsed .sb-section{opacity:0;padding-top:8px;padding-bottom:2px}

.sb-link{
  display:flex;align-items:center;gap:10px;
  padding:9px 10px;border-radius:var(--rsm);
  color:rgba(255,255,255,.5);text-decoration:none;
  font-size:13.5px;font-weight:500;
  transition:all var(--dur) var(--ease);
  margin-bottom:2px;white-space:nowrap;overflow:hidden;position:relative;
}
.sb-link .ico{
  width:34px;height:34px;border-radius:var(--rsm);flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;background:rgba(255,255,255,.07);
  transition:background var(--dur) var(--ease);
}
.sb-link .lbl{transition:opacity var(--dur) var(--ease)}
.sidebar.collapsed .sb-link .lbl{opacity:0}
.sb-link:hover{color:#fff;background:rgba(255,255,255,.08)}
.sb-link:hover .ico{background:rgba(255,255,255,.14)}
.sb-link.active{color:#fff;background:var(--primary)}
.sb-link.active .ico{background:rgba(255,255,255,.22)}

/* Collapsed tooltip */
.sb-tip{
  display:none;position:absolute;
  left:calc(var(--sc) + 8px);top:50%;transform:translateY(-50%);
  background:#1e2b42;color:#fff;font-size:12px;font-weight:500;
  padding:5px 12px;border-radius:7px;
  white-space:nowrap;pointer-events:none;z-index:9999;
  box-shadow:0 4px 16px rgba(0,0,0,.3);
}
.sb-tip::before{
  content:'';position:absolute;right:100%;top:50%;transform:translateY(-50%);
  border:5px solid transparent;border-right-color:#1e2b42;
}
.sidebar.collapsed .sb-link:hover .sb-tip{display:block}

/* ── Footer ── */
.sb-footer{padding:10px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0}
.sb-logout{
  display:flex;align-items:center;gap:10px;
  padding:9px 10px;border-radius:var(--rsm);
  color:rgba(255,255,255,.4);font-size:13.5px;font-weight:500;
  background:none;border:none;width:100%;cursor:pointer;
  transition:all var(--dur) var(--ease);text-align:left;
  white-space:nowrap;overflow:hidden;
}
.sb-logout .ico{
  width:34px;height:34px;border-radius:var(--rsm);flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;background:rgba(255,255,255,.07);
  transition:background var(--dur) var(--ease);
}
.sb-lout-lbl{transition:opacity var(--dur) var(--ease)}
.sidebar.collapsed .sb-lout-lbl{opacity:0}
.sb-logout:hover{color:var(--danger);background:rgba(255,77,109,.1)}
.sb-logout:hover .ico{background:rgba(255,77,109,.18)}

/* ─────────────────────────────────────────
   MAIN WRAP
───────────────────────────────────────── */
.main-wrap{
  margin-left:var(--sw);flex:1;min-height:100vh;
  display:flex;flex-direction:column;min-width:0;
  transition:margin-left var(--dur) var(--ease);
}
.main-wrap.collapsed{margin-left:var(--sc)}

/* ─────────────────────────────────────────
   TOPBAR
───────────────────────────────────────── */
.topbar{
  height:var(--th);background:var(--surface);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;
  padding:0 20px;position:sticky;top:0;z-index:900;gap:10px;
}
.topbar-l{display:flex;align-items:center;gap:12px;flex:1;min-width:0}
.topbar-r{display:flex;align-items:center;gap:8px;flex-shrink:0}

.btn-toggle{
  width:36px;height:36px;border-radius:var(--rsm);
  border:1px solid var(--border);background:var(--surface);
  display:flex;align-items:center;justify-content:center;
  color:var(--muted);cursor:pointer;font-size:15px;flex-shrink:0;
  transition:all var(--dur) var(--ease);
}
.btn-toggle:hover{background:var(--plt);border-color:var(--primary);color:var(--primary)}

.page-title{
  font-family:'Syne',sans-serif;font-weight:700;font-size:17px;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}

.tb-btn{
  width:36px;height:36px;border-radius:var(--rsm);
  border:1px solid var(--border);background:var(--surface);
  display:flex;align-items:center;justify-content:center;
  color:var(--muted);cursor:pointer;font-size:14px;position:relative;
  transition:all var(--dur);
}
.tb-btn:hover{border-color:var(--primary);color:var(--primary)}
.notif-dot{
  position:absolute;top:6px;right:6px;width:7px;height:7px;
  border-radius:50%;background:var(--danger);border:2px solid var(--surface);
}

.user-chip{
  display:flex;align-items:center;gap:8px;
  padding:4px 10px 4px 4px;border-radius:100px;
  border:1px solid var(--border);background:var(--surface);
  cursor:pointer;transition:all var(--dur);white-space:nowrap;
}
.user-chip:hover{border-color:var(--primary)}
.user-av{
  width:28px;height:28px;border-radius:50%;background:var(--primary);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:11px;font-weight:700;flex-shrink:0;
}
.chip-name{font-size:13px;font-weight:500}
.chip-caret{font-size:9px;color:var(--muted)}

.dd-menu{
  border-radius:12px!important;border:1px solid var(--border)!important;
  box-shadow:0 8px 28px rgba(15,22,35,.13)!important;padding:8px!important;
}
.dd-menu .dropdown-item{border-radius:8px;font-size:13px;padding:9px 12px}
.dd-menu .dropdown-item:hover{background:var(--plt);color:var(--primary)}

/* ─────────────────────────────────────────
   PAGE BODY
───────────────────────────────────────── */
.page-body{padding:24px;flex:1}

/* ─────────────────────────────────────────
   CARDS / TABLES (global)
───────────────────────────────────────── */
.card{border:1px solid var(--border)!important;border-radius:var(--radius)!important;background:var(--surface);box-shadow:0 2px 16px rgba(15,22,35,.07)}
.table thead th{font-size:10.5px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--muted);background:#f8f9fd;border-bottom:1px solid var(--border);padding:11px 16px}
.table tbody td{padding:12px 16px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle}
.table tbody tr:last-child td{border-bottom:none}
.table tbody tr{transition:background var(--dur)}
.table tbody tr:hover{background:#f8f9fd}
.badge{font-weight:600;font-size:10.5px;padding:4px 10px;border-radius:100px}

/* ─────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────── */
@media(max-width:991.98px){
  .sidebar,
  .sidebar.collapsed{
    width:var(--sw)!important;
    transform:translateX(-100%);
  }
  .sidebar.open{transform:translateX(0)!important}

  .sidebar .sb-brand-info,
  .sidebar .sb-link .lbl,
  .sidebar .sb-lout-lbl{opacity:1!important;width:auto!important}
  .sidebar .sb-section{opacity:1!important;padding-top:16px!important;padding-bottom:5px!important}
  .sidebar .sb-link:hover .sb-tip{display:none!important}

  .main-wrap,
  .main-wrap.collapsed{margin-left:0!important}

  .page-body{padding:16px}
  .topbar{padding:0 14px}
}

@media(max-width:575.98px){
  .chip-name,.chip-caret{display:none}
  .user-chip{padding:4px}
  .page-body{padding:12px}
  .topbar{padding:0 10px}
}
</style>
</head>
<body>

<div class="sb-overlay" id="sbOverlay"></div>

<div class="app-shell">
  @include('layouts.partials.sidebar')
  
  <!-- ══════════ MAIN ══════════ -->
  <div class="main-wrap" id="mainWrap">
    @include('layouts.partials.topbar')
    
    <!-- Page content -->
    <div class="page-body">
      @yield('content')
    </div>
  </div><!-- /main-wrap -->
</div><!-- /app-shell -->

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

@include('layouts.partials.scripts')

@stack('scripts')
</body>
</html>