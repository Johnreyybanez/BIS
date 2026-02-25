@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="card">
    <div class="card-body" style="padding:40px;text-align:center">
        <i class="fas fa-chart-bar" style="font-size:64px;color:var(--primary);margin-bottom:20px"></i>
        <h3 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:10px">Reports Dashboard</h3>
        <p style="color:var(--muted);margin-bottom:30px">Select a report type to generate</p>
        
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;max-width:800px;margin:0 auto">
            <a href="{{ route('reports.residents') }}" class="card" style="padding:30px;text-decoration:none;color:var(--text);transition:all 0.2s">
                <i class="fas fa-users" style="font-size:32px;color:var(--primary);margin-bottom:10px"></i>
                <h4 style="font-weight:600">Residents Report</h4>
            </a>
            <a href="{{ route('reports.certificates') }}" class="card" style="padding:30px;text-decoration:none;color:var(--text);transition:all 0.2s">
                <i class="fas fa-file" style="font-size:32px;color:var(--primary);margin-bottom:10px"></i>
                <h4 style="font-weight:600">Certificates Report</h4>
            </a>
            <a href="{{ route('reports.blotters') }}" class="card" style="padding:30px;text-decoration:none;color:var(--text);transition:all 0.2s">
                <i class="fas fa-book-open" style="font-size:32px;color:var(--primary);margin-bottom:10px"></i>
                <h4 style="font-weight:600">Blotters Report</h4>
            </a>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(79,99,210,0.15) !important;
    border-color: var(--primary) !important;
}
</style>
@endsection