@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ── Stat Cards ─────────────────────────────────────── --}}
<div class="row g-3 mb-4">

  <div class="col-6 col-lg-3">
    <div style="background:linear-gradient(135deg,#4f63d2,#7c87e8);border-radius:14px;padding:22px 20px;position:relative;overflow:hidden;min-height:110px">
      <div style="position:absolute;right:-14px;top:-14px;width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.1)"></div>
      <div style="position:absolute;right:14px;top:16px;width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:19px;color:rgba(255,255,255,.9)">
        <i class="fas fa-users"></i>
      </div>
      <div style="font-size:10.5px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:rgba(255,255,255,.72);margin-bottom:8px">Total Residents</div>
      <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:#fff;line-height:1">{{ number_format($totalResidents) }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.6);margin-top:8px"><i class="fas fa-arrow-trend-up me-1"></i>All registered</div>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div style="background:linear-gradient(135deg,#f5a623,#f7c04a);border-radius:14px;padding:22px 20px;position:relative;overflow:hidden;min-height:110px">
      <div style="position:absolute;right:-14px;top:-14px;width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.1)"></div>
      <div style="position:absolute;right:14px;top:16px;width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:19px;color:rgba(255,255,255,.9)">
        <i class="fas fa-user-clock"></i>
      </div>
      <div style="font-size:10.5px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:rgba(255,255,255,.75);margin-bottom:8px">Senior Citizens</div>
      <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:#fff;line-height:1">{{ number_format($totalSenior) }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.7);margin-top:8px"><i class="fas fa-cake-candles me-1"></i>Age 60+</div>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div style="background:linear-gradient(135deg,#12b886,#20c997);border-radius:14px;padding:22px 20px;position:relative;overflow:hidden;min-height:110px">
      <div style="position:absolute;right:-14px;top:-14px;width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.1)"></div>
      <div style="position:absolute;right:14px;top:16px;width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:19px;color:rgba(255,255,255,.9)">
        <i class="fas fa-wheelchair"></i>
      </div>
      <div style="font-size:10.5px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:rgba(255,255,255,.75);margin-bottom:8px">PWD</div>
      <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:#fff;line-height:1">{{ number_format($totalPWD) }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.7);margin-top:8px"><i class="fas fa-heart-pulse me-1"></i>Persons w/ disability</div>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div style="background:linear-gradient(135deg,#ff4d6d,#ff7c91);border-radius:14px;padding:22px 20px;position:relative;overflow:hidden;min-height:110px">
      <div style="position:absolute;right:-14px;top:-14px;width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.1)"></div>
      <div style="position:absolute;right:14px;top:16px;width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:19px;color:rgba(255,255,255,.9)">
        <i class="fas fa-clock-rotate-left"></i>
      </div>
      <div style="font-size:10.5px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:rgba(255,255,255,.75);margin-bottom:8px">Pending Requests</div>
      <div style="font-family:'Syne',sans-serif;font-size:30px;font-weight:800;color:#fff;line-height:1">{{ number_format($pendingCertificates) }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.7);margin-top:8px"><i class="fas fa-file-circle-question me-1"></i>Awaiting approval</div>
    </div>
  </div>

</div>

{{-- ── Tables Row ───────────────────────────────────────── --}}
<div class="row g-3 mb-4">

  {{-- Recent Residents --}}
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body p-0">
        <div style="padding:18px 18px 12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
          <div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px">Recent Residents</div>
            <div style="font-size:12px;color:var(--muted);margin-top:2px">Latest registrations</div>
          </div>
          <a href="{{ route('residents.index') }}"
             style="font-size:12px;font-weight:600;color:var(--primary);text-decoration:none;background:var(--plt);padding:6px 14px;border-radius:100px;white-space:nowrap">
            View All <i class="fas fa-arrow-right ms-1" style="font-size:10px"></i>
          </a>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th style="padding-left:18px">Name</th>
                <th>Gender</th>
                <th style="padding-right:18px">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentResidents as $resident)
              <tr>
                <td style="padding-left:18px">
                  <div style="display:flex;align-items:center;gap:9px">
                    <div style="width:30px;height:30px;border-radius:50%;background:var(--plt);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:var(--primary);flex-shrink:0">
                      {{ strtoupper(substr($resident->full_name,0,1)) }}
                    </div>
                    <span style="font-weight:500">{{ $resident->full_name }}</span>
                  </div>
                </td>
                <td style="color:var(--muted)">{{ ucfirst($resident->gender) }}</td>
                <td style="padding-right:18px">
                  @if($resident->status === 'active')
                    <span class="badge" style="background:#e6f9f0;color:#12b886">Active</span>
                  @else
                    <span class="badge" style="background:#ffeef2;color:#ff4d6d">Inactive</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" style="text-align:center;color:var(--muted);padding:30px 0;font-size:13px">
                  <i class="fas fa-users" style="font-size:24px;opacity:.3;display:block;margin-bottom:8px"></i>
                  No residents found
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Certificate Requests --}}
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body p-0">
        <div style="padding:18px 18px 12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
          <div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px">Certificate Requests</div>
            <div style="font-size:12px;color:var(--muted);margin-top:2px">Recent submissions</div>
          </div>
          <a href="{{ route('certificates.index') }}"
             style="font-size:12px;font-weight:600;color:var(--primary);text-decoration:none;background:var(--plt);padding:6px 14px;border-radius:100px;white-space:nowrap">
            View All <i class="fas fa-arrow-right ms-1" style="font-size:10px"></i>
          </a>
        </div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th style="padding-left:18px">Control #</th>
                <th>Resident</th>
                <th style="padding-right:18px">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentCertificates as $cert)
              <tr>
                <td style="padding-left:18px">
                  <code style="font-size:12px;background:#f0f2f8;color:var(--primary);padding:3px 8px;border-radius:6px;font-weight:600">
                    {{ $cert->control_number }}
                  </code>
                </td>
                <td style="font-weight:500">{{ $cert->resident->full_name ?? '—' }}</td>
                <td style="padding-right:18px">
                  @php
                    $bs = match($cert->status){
                      'pending'  => 'background:#fff8e6;color:#f5a623',
                      'approved' => 'background:#e6f0fd;color:#4f63d2',
                      'released' => 'background:#e6f9f0;color:#12b886',
                      'rejected' => 'background:#ffeef2;color:#ff4d6d',
                      default    => 'background:#f0f2f8;color:#6b7399',
                    };
                  @endphp
                  <span class="badge" style="{{ $bs }}">{{ ucfirst($cert->status) }}</span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" style="text-align:center;color:var(--muted);padding:30px 0;font-size:13px">
                  <i class="fas fa-file-circle-question" style="font-size:24px;opacity:.3;display:block;margin-bottom:8px"></i>
                  No certificate requests
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ── Chart ─────────────────────────────────────────────── --}}
<div class="card">
  <div class="card-body" style="padding:22px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:10px">
      <div>
        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px">Resident Growth Overview</div>
        <div style="font-size:12px;color:var(--muted);margin-top:2px">Monthly registration trend — {{ date('Y') }}</div>
      </div>
      <div style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--primary)">
        {{ number_format($totalResidents) }}
        <span style="font-size:13px;color:var(--muted);font-weight:400;font-family:'DM Sans',sans-serif">total</span>
      </div>
    </div>
    <canvas id="dashChart" style="max-height:260px"></canvas>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  const ctx  = document.getElementById('dashChart').getContext('2d');
  const grad = ctx.createLinearGradient(0,0,0,260);
  grad.addColorStop(0,'rgba(79,99,210,.18)');
  grad.addColorStop(1,'rgba(79,99,210,0)');

  new Chart(ctx,{
    type:'line',
    data:{
      labels:{!! json_encode($chartLabels ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']) !!},
      datasets:[{
        label:'Registered Residents',
        data:{!! json_encode($chartData ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!},
        borderColor:'#4f63d2',
        backgroundColor:grad,
        pointBackgroundColor:'#4f63d2',
        pointBorderColor:'#fff',
        pointBorderWidth:2,
        pointRadius:5,
        pointHoverRadius:7,
        tension:0.45,fill:true,borderWidth:2.5
      }]
    },
    options:{
      responsive:true,maintainAspectRatio:true,
      interaction:{intersect:false,mode:'index'},
      plugins:{
        legend:{display:false},
        tooltip:{
          backgroundColor:'#0d1321',
          titleFont:{family:'Syne',weight:'700',size:13},
          bodyFont:{family:'DM Sans',size:12},
          padding:12,cornerRadius:10,
          callbacks:{label:c=>` ${c.parsed.y.toLocaleString()} residents`}
        }
      },
      scales:{
        y:{beginAtZero:true,ticks:{precision:0,color:'#6b7399',font:{size:11}},grid:{color:'rgba(0,0,0,.05)'}},
        x:{ticks:{color:'#6b7399',font:{size:11}},grid:{display:false}}
      }
    }
  });
})();

@if(session('success'))
  Swal.fire({
    icon:'success', title:'Welcome back!',
    text:@json(session('success')),
    confirmButtonText:"Let's go",
    confirmButtonColor:'#4f63d2',
    timer:3000, timerProgressBar:true
  });
@endif
</script>
@endpush