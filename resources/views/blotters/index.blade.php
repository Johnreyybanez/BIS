@extends('layouts.app')

@section('title', 'Blotter Records')

@section('content')

{{-- ── Page header ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);margin:0;line-height:1.2">
            Blotter Records
        </h2>
        <p style="font-size:13px;color:var(--muted);margin:3px 0 0">
            Manage and track barangay blotter cases
        </p>
    </div>
    <button type="button"
            class="btn-add-blotter"
            data-bs-toggle="modal"
            data-bs-target="#addBlotterModal">
        <i class="fas fa-plus"></i>
        New Blotter Case
    </button>
</div>

{{-- ── Stats strip ── --}}
<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fd;color:#4f63d2"><i class="fas fa-scale-balanced"></i></div>
        <div>
            <div class="stat-val">{{ $totalCases ?? 0 }}</div>
            <div class="stat-lbl">Total Cases</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val">{{ $ongoingCases ?? 0 }}</div>
            <div class="stat-lbl">Ongoing</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e6faf3;color:#1cc88a"><i class="fas fa-handshake"></i></div>
        <div>
            <div class="stat-val">{{ $settledCases ?? 0 }}</div>
            <div class="stat-lbl">Settled</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e3f2fd;color:#0d6efd"><i class="fas fa-gavel"></i></div>
        <div>
            <div class="stat-val">{{ $filedCases ?? 0 }}</div>
            <div class="stat-lbl">Filed in Court</div>
        </div>
    </div>
</div>

{{-- ── Table card ── --}}
<div class="card">
    <div class="card-body" style="padding:0">

        {{-- Table controls with search at right end --}}
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            {{-- Left side: Show entries and filters --}}
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="font-size:13px;color:var(--muted)">Show</span>
                    <select id="showEntries" class="blotter-filter" style="width:70px">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span style="font-size:13px;color:var(--muted)">entries</span>
                </div>

                {{-- Status filter --}}
                <select id="filterStatus" class="blotter-filter">
                    <option value="">All Status</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="settled">Settled</option>
                    <option value="filed">Filed in Court</option>
                    <option value="dismissed">Dismissed</option>
                </select>
            </div>

            {{-- Right side: Search bar --}}
            <div class="blotter-search-wrap">
                <i class="fas fa-magnifying-glass blotter-search-ico"></i>
                <input type="text" id="blotterSearch" class="blotter-search" placeholder="Search cases…">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="blottersTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="case_number">Case # <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="complainant">Complainant <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="respondent">Respondent <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="incident_date">Incident Date <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="incident_location">Location <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="status">Status <i class="fas fa-sort sort-icon"></i></th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blotters ?? [] as $blotter)
                    <tr>
                        <td>
                            <span class="case-number">{{ $blotter->case_number }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="blotter-avatar" style="background:{{ $blotter->complainant ? ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($blotter->complainant->full_name) % 5] : '#b0b7cc' }}">
                                    {{ $blotter->complainant ? strtoupper(substr($blotter->complainant->full_name, 0, 1)) : '?' }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">{{ $blotter->complainant->full_name ?? 'Unknown' }}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">{{ $blotter->complainant->resident_code ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="blotter-avatar" style="background:{{ $blotter->respondent ? ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($blotter->respondent->full_name) % 5] : '#b0b7cc' }}">
                                    {{ $blotter->respondent ? strtoupper(substr($blotter->respondent->full_name, 0, 1)) : '?' }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">{{ $blotter->respondent->full_name ?? 'Unknown' }}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">{{ $blotter->respondent->resident_code ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--muted)">
                            {{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('M d, Y') : '—' }}
                        </td>
                        <td style="max-width:150px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis; color:var(--muted); font-size:13px"
                                 title="{{ $blotter->incident_location }}">
                                {{ $blotter->incident_location ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $blotter->status }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($blotter->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-blotter"
                                        data-id="{{ $blotter->id }}"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit action-btn-edit edit-blotter"
                                        data-id="{{ $blotter->id }}"
                                        title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn action-btn-status change-status"
                                        data-id="{{ $blotter->id }}"
                                        data-status="{{ $blotter->status }}"
                                        title="Change Status">
                                    <i class="fas fa-rotate" style="color:#f4a20a"></i>
                                </button>
                                <button class="action-btn action-btn-delete delete-blotter"
                                        data-id="{{ $blotter->id }}"
                                        title="Delete">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-scale-balanced" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                            No blotter cases found. Click "New Blotter Case" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination: Info on left, Links on right --}}
        @if(isset($blotters) && $blotters instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $blotters->firstItem() ?? 0 }} to {{ $blotters->lastItem() ?? 0 }} of {{ $blotters->total() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;">
                {{ $blotters->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ════════════════════════════════════════
     ADD BLOTTER MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="addBlotterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content blotter-modal">

            <div class="blotter-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-scale-balanced"></i></div>
                    <div>
                        <h5 class="blotter-modal-title">New Blotter Case</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">File a new blotter case</p>
                    </div>
                </div>
                <button type="button" class="blotter-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="addBlotterForm">
                @csrf
                <div class="blotter-modal-body">

                    <div class="form-section-label">Case Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Complainant <span class="req">*</span></label>
                                <select class="blotter-input" name="complainant_id" required>
                                    <option value="">— Select Complainant —</option>
                                    @if(isset($residents) && $residents->count() > 0)
                                        @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}">
                                            {{ $resident->full_name }} 
                                            @if($resident->resident_code)({{ $resident->resident_code }})@endif
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Respondent <span class="req">*</span></label>
                                <select class="blotter-input" name="respondent_id" required>
                                    <option value="">— Select Respondent —</option>
                                    @if(isset($residents) && $residents->count() > 0)
                                        @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}">
                                            {{ $resident->full_name }} 
                                            @if($resident->resident_code)({{ $resident->resident_code }})@endif
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Incident Date <span class="req">*</span></label>
                                <input type="datetime-local" class="blotter-input" name="incident_date" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Incident Location <span class="req">*</span></label>
                                <input type="text" class="blotter-input" name="incident_location" 
                                       placeholder="e.g., Purok 3, Barangay San Jose" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="blotter-field">
                                <label class="blotter-label">Case Description <span class="req">*</span></label>
                                <textarea class="blotter-input" name="description" rows="4"
                                          placeholder="Describe the incident in detail..."
                                          style="resize:none;min-height:100px" required></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Initial Status</label>
                                <select class="blotter-input" name="status">
                                    <option value="ongoing" selected>Ongoing</option>
                                    <option value="filed">Filed in Court</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="blotter-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> File Case
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     EDIT BLOTTER MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="editBlotterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content blotter-modal">

            <div class="blotter-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-file-pen"></i></div>
                    <div>
                        <h5 class="blotter-modal-title">Edit Blotter Case</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Update case details</p>
                    </div>
                </div>
                <button type="button" class="blotter-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="editBlotterForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editBlotterId">
                <div class="blotter-modal-body">

                    <div class="form-section-label">Case Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Complainant <span class="req">*</span></label>
                                <select class="blotter-input" id="editComplainantId" name="complainant_id" required>
                                    <option value="">— Select Complainant —</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Respondent <span class="req">*</span></label>
                                <select class="blotter-input" id="editRespondentId" name="respondent_id" required>
                                    <option value="">— Select Respondent —</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Incident Date <span class="req">*</span></label>
                                <input type="datetime-local" class="blotter-input" id="editIncidentDate" name="incident_date" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Incident Location <span class="req">*</span></label>
                                <input type="text" class="blotter-input" id="editIncidentLocation" 
                                       name="incident_location" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="blotter-field">
                                <label class="blotter-label">Case Description <span class="req">*</span></label>
                                <textarea class="blotter-input" id="editDescription" name="description" 
                                          rows="4" style="resize:none;min-height:100px" required></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="blotter-field">
                                <label class="blotter-label">Status</label>
                                <select class="blotter-input" id="editStatus" name="status">
                                    <option value="ongoing">Ongoing</option>
                                    <option value="settled">Settled</option>
                                    <option value="filed">Filed in Court</option>
                                    <option value="dismissed">Dismissed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="blotter-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Update Case
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     VIEW BLOTTER MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="viewBlotterModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content blotter-modal">

            <div class="blotter-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-file-lines"></i></div>
                    <div>
                        <h5 class="blotter-modal-title">Case Details</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0" id="viewBlotterSubtitle">Loading…</p>
                    </div>
                </div>
                <button type="button" class="blotter-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <div class="blotter-modal-body" id="viewBlotterBody">
                <div style="text-align:center;padding:40px 0;color:var(--muted)">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                    <div style="margin-top:10px;font-size:13px">Loading details…</div>
                </div>
            </div>

            <div class="blotter-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     CHANGE STATUS MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content blotter-modal">

            <div class="blotter-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-rotate" style="color:#f4a20a"></i></div>
                    <div>
                        <h5 class="blotter-modal-title">Change Case Status</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Update the status of this case</p>
                    </div>
                </div>
                <button type="button" class="blotter-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="statusForm">
                @csrf
                <input type="hidden" id="statusBlotterId">
                <div class="blotter-modal-body">
                    <div class="blotter-field">
                        <label class="blotter-label">New Status <span class="req">*</span></label>
                        <select class="blotter-input" id="newStatus" name="status" required>
                            <option value="ongoing">Ongoing</option>
                            <option value="settled">Settled</option>
                            <option value="filed">Filed in Court</option>
                            <option value="dismissed">Dismissed</option>
                        </select>
                    </div>
                    <div class="blotter-field" style="margin-top:16px">
                        <label class="blotter-label">Notes (Optional)</label>
                        <textarea class="blotter-input" id="statusNotes" name="notes" rows="3"
                                  placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </div>

                <div class="blotter-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-check"></i> Update Status
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ════ Component Styles ════ --}}
<style>
:root {
    --dur: 0.2s;
    --ease: ease;
    --radius: 12px;
    --text: #1e293b;
    --muted: #64748b;
    --border: #e2e8f0;
    --surface: #ffffff;
    --bg: #f8fafc;
    --primary: #4f63d2;
    --danger: #ff4d6d;
    --plt: #eef0fd;
}

/* ─── Stat strip ─── */
.stat-strip{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:14px;
    margin-bottom:22px;
}
.stat-card{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:16px 18px;
    display:flex;align-items:center;gap:14px;
    box-shadow:0 2px 10px rgba(15,22,35,.05);
    transition:box-shadow var(--dur) var(--ease),transform var(--dur) var(--ease);
}
.stat-card:hover{box-shadow:0 6px 22px rgba(15,22,35,.1);transform:translateY(-2px)}
.stat-icon{
    width:42px;height:42px;border-radius:11px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    font-size:16px;
}
.stat-val{font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);line-height:1}
.stat-lbl{font-size:11.5px;color:var(--muted);margin-top:3px;font-weight:500}

/* ─── Add button ─── */
.btn-add-blotter{
    display:inline-flex;align-items:center;gap:8px;
    padding:9px 20px;
    border-radius:10px;
    border:none;cursor:pointer;
    background:var(--primary);color:#fff;
    font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;
    transition:all var(--dur) var(--ease);
    box-shadow:0 4px 14px rgba(79,99,210,.35);
}
.btn-add-blotter:hover{
    background:#3d4fc0;
    box-shadow:0 6px 20px rgba(79,99,210,.45);
    transform:translateY(-1px);
}

/* ─── Search & filter ─── */
.blotter-search-wrap{
    position:relative;display:flex;align-items:center;
}
.blotter-search-ico{
    position:absolute;left:12px;color:var(--muted);font-size:13px;pointer-events:none;
}
.blotter-search{
    padding:8px 14px 8px 36px;
    border:1px solid var(--border);
    border-radius:9px;background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);
    width:260px;outline:none;
    transition:border-color var(--dur),box-shadow var(--dur);
}
.blotter-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.blotter-filter{
    padding:8px 14px;
    border:1px solid var(--border);
    border-radius:9px;background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);
    outline:none;cursor:pointer;
    transition:border-color var(--dur),box-shadow var(--dur);
}
.blotter-filter:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}

/* ─── Table overrides ─── */
#blottersTable thead th{
    font-size:10.5px;font-weight:700;letter-spacing:.08em;
    text-transform:uppercase;color:var(--muted);
    background:#f8f9fd;padding:13px 16px;
    border-bottom:1px solid var(--border);
    position:relative;
}
#blottersTable thead th.sortable{
    cursor:pointer;
    user-select:none;
}
#blottersTable thead th.sortable:hover{
    background:#eef0fd;
    color:var(--primary);
}
#blottersTable thead th.sortable:hover .sort-icon{
    opacity:1;
}
.sort-icon{
    margin-left:5px;
    font-size:10px;
    opacity:0.3;
    transition:opacity var(--dur);
}
#blottersTable tbody td{
    padding:14px 16px;font-size:13.5px;
    border-bottom:1px solid var(--border);
    vertical-align:middle;
}
#blottersTable tbody tr:last-child td{border-bottom:none}
#blottersTable tbody tr{transition:background var(--dur)}
#blottersTable tbody tr:hover{background:#f8f9fd}

/* ─── Sorting active state ─── */
th.sorting-asc .sort-icon::before{
    content:"\f0de";
    opacity:1;
    color:var(--primary);
}
th.sorting-desc .sort-icon::before{
    content:"\f0dd";
    opacity:1;
    color:var(--primary);
}

/* ─── Case number ─── */
.case-number{
    font-family:'Syne',sans-serif;font-size:11.5px;font-weight:700;
    color:var(--primary);background:var(--plt);
    padding:3px 9px;border-radius:6px;letter-spacing:.04em;
}

/* ─── Avatar ─── */
.blotter-avatar{
    width:36px;height:36px;border-radius:10px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:14px;
}

/* ─── Status badge ─── */
.status-badge{
    display:inline-flex;align-items:center;gap:6px;
    font-size:11.5px;font-weight:700;padding:4px 11px;border-radius:100px;
}
.status-ongoing{background:#fff8e6;color:#f4a20a}
.status-settled{background:#e6faf3;color:#1cc88a}
.status-filed{background:#e3f2fd;color:#0d6efd}
.status-dismissed{background:#fff0f3;color:#ff4d6d}
.status-dot{
    width:6px;height:6px;border-radius:50%;
    background:currentColor;flex-shrink:0;
}

/* ─── Action buttons ─── */
.action-group{display:flex;align-items:center;justify-content:flex-end;gap:6px}
.action-btn{
    width:32px;height:32px;border-radius:8px;border:1px solid var(--border);
    background:var(--surface);color:var(--muted);
    display:flex;align-items:center;justify-content:center;
    font-size:13px;cursor:pointer;
    transition:all var(--dur) var(--ease);
}
.action-btn:hover{color:#4f63d2;border-color:#4f63d2;background:var(--plt)}
.action-btn-edit:hover{color:#f4a20a;border-color:#f4a20a;background:#fff8e6}
.action-btn-delete:hover{color:var(--danger);border-color:var(--danger);background:#fff0f3}
.action-btn-status:hover{color:#f4a20a;border-color:#f4a20a;background:#fff8e6}

/* ─── Empty state ─── */
.empty-state{text-align:center;padding:48px 16px;color:var(--muted);font-size:14px}

/* ─── Modal ─── */
.blotter-modal{border:none;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(15,22,35,.22)}
.blotter-modal-header{
    display:flex;align-items:center;justify-content:space-between;
    padding:22px 28px;border-bottom:1px solid var(--border);
    background:#fff;
}
.modal-icon-wrap{
    width:42px;height:42px;border-radius:12px;
    background:var(--plt);color:var(--primary);
    display:flex;align-items:center;justify-content:center;font-size:17px;
}
.blotter-modal-title{
    font-family:'Syne',sans-serif;font-weight:800;font-size:17px;
    color:var(--text);margin:0;
}
.blotter-modal-close{
    width:34px;height:34px;border-radius:9px;
    border:1px solid var(--border);background:none;
    color:var(--muted);cursor:pointer;font-size:14px;
    display:flex;align-items:center;justify-content:center;
    transition:all var(--dur);
}
.blotter-modal-close:hover{background:var(--bg);color:var(--text)}
.blotter-modal-body{padding:24px 28px}
.blotter-modal-footer{
    padding:18px 28px;border-top:1px solid var(--border);
    display:flex;align-items:center;justify-content:flex-end;gap:10px;
    background:#fafbff;
}

/* ─── Form section label ─── */
.form-section-label{
    font-size:10.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
    color:var(--muted);margin-bottom:12px;
    display:flex;align-items:center;gap:8px;
}
.form-section-label::after{
    content:'';flex:1;height:1px;background:var(--border);
}

/* ─── Form inputs ─── */
.blotter-field{display:flex;flex-direction:column;gap:6px}
.blotter-label{font-size:12.5px;font-weight:600;color:var(--text)}
.req{color:var(--danger)}
.blotter-input{
    padding:9px 13px;
    border:1px solid var(--border);border-radius:9px;
    background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text);
    outline:none;width:100%;
    transition:border-color var(--dur),box-shadow var(--dur),background var(--dur);
}
.blotter-input:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(79,99,210,.12);
    background:#fff;
}

/* ─── Modal action buttons ─── */
.btn-cancel{
    padding:9px 20px;border-radius:9px;
    border:1px solid var(--border);background:#fff;
    font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;
    color:var(--muted);cursor:pointer;
    transition:all var(--dur);
}
.btn-cancel:hover{border-color:var(--text);color:var(--text)}
.btn-save{
    display:inline-flex;align-items:center;gap:8px;
    padding:9px 22px;border-radius:9px;
    border:none;background:var(--primary);
    font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;
    color:#fff;cursor:pointer;
    box-shadow:0 4px 14px rgba(79,99,210,.35);
    transition:all var(--dur);
}
.btn-save:hover{background:#3d4fc0;box-shadow:0 6px 20px rgba(79,99,210,.45);transform:translateY(-1px)}

/* ─── Pagination override ─── */
.pagination .page-item .page-link{
    border-radius:8px!important;
    font-size:13px;font-weight:500;
    border:1px solid var(--border);
    color:var(--muted);margin:0 2px;
    transition:all var(--dur);
}
.pagination .page-item.active .page-link{
    background:var(--primary)!important;
    border-color:var(--primary)!important;color:#fff;
}
.pagination .page-item .page-link:hover{
    border-color:var(--primary);color:var(--primary);background:var(--plt);
}

/* ─── Show entries dropdown ─── */
#showEntries{
    width:70px;
    padding:8px 10px;
}

/* ─── Pagination container ─── */
#paginationLinks nav {
    display:flex;
    justify-content:flex-end;
}

/* ─── View modal detail rows ─── */
.v-row{display:flex;gap:12px;padding:11px 0;border-bottom:1px solid var(--border)}
.v-row:last-child{border-bottom:none}
.v-lbl{font-size:11.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;
       color:var(--muted);min-width:120px;flex-shrink:0;padding-top:2px}
.v-val{font-size:13.5px;color:var(--text);font-weight:500}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let blottersData = [];
    let currentSort = { column: 'case_number', direction: 'asc' };
    let currentPage = 1;
    let perPage = 10;
    let filteredData = [];

    // Store all blotters data for client-side sorting and filtering
    @if(isset($blotters) && $blotters->count() > 0)
        @foreach($blotters as $blotter)
        blottersData.push({
            id: {{ $blotter->id }},
            case_number: '{{ $blotter->case_number }}',
            complainant_id: {{ $blotter->complainant_id }},
            complainant_name: '{{ addslashes($blotter->complainant->full_name ?? '') }}',
            complainant_code: '{{ $blotter->complainant->resident_code ?? '' }}',
            complainant_avatar: '{{ $blotter->complainant ? ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($blotter->complainant->full_name) % 5] : '#b0b7cc' }}',
            complainant_initial: '{{ $blotter->complainant ? strtoupper(substr($blotter->complainant->full_name, 0, 1)) : '?' }}',
            respondent_id: {{ $blotter->respondent_id }},
            respondent_name: '{{ addslashes($blotter->respondent->full_name ?? '') }}',
            respondent_code: '{{ $blotter->respondent->resident_code ?? '' }}',
            respondent_avatar: '{{ $blotter->respondent ? ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($blotter->respondent->full_name) % 5] : '#b0b7cc' }}',
            respondent_initial: '{{ $blotter->respondent ? strtoupper(substr($blotter->respondent->full_name, 0, 1)) : '?' }}',
            incident_date: '{{ $blotter->incident_date }}',
            incident_location: '{{ addslashes($blotter->incident_location) }}',
            description: '{{ addslashes($blotter->description) }}',
            status: '{{ $blotter->status }}'
        });
        @endforeach
    @endif

    filteredData = [...blottersData];

    // Initialize table
    renderTable();

    // Show entries change
    $('#showEntries').on('change', function() {
        perPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
    });

    // Search functionality
    $('#blotterSearch').on('input', function() {
        let searchTerm = $(this).val().toLowerCase();
        filterData(searchTerm);
    });

    // Status filter
    $('#filterStatus').on('change', function() {
        filterData($('#blotterSearch').val().toLowerCase());
    });

    // Sorting
    $('.sortable').on('click', function() {
        let column = $(this).data('sort');
        
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        // Update sort icons
        $('.sortable').removeClass('sorting-asc sorting-desc');
        $(this).addClass(`sorting-${currentSort.direction}`);
        
        sortData();
        renderTable();
    });

    function filterData(searchTerm) {
        let status = $('#filterStatus').val().toLowerCase();

        filteredData = blottersData.filter(item => {
            let matchesSearch = searchTerm === '' || 
                item.case_number.toLowerCase().includes(searchTerm) ||
                (item.complainant_name && item.complainant_name.toLowerCase().includes(searchTerm)) ||
                (item.respondent_name && item.respondent_name.toLowerCase().includes(searchTerm)) ||
                (item.incident_location && item.incident_location.toLowerCase().includes(searchTerm));

            let matchesStatus = status === '' || item.status.toLowerCase() === status;

            return matchesSearch && matchesStatus;
        });

        sortData();
        currentPage = 1;
        renderTable();
    }

    function sortData() {
        filteredData.sort((a, b) => {
            let valA = a[currentSort.column];
            let valB = b[currentSort.column];

            if (currentSort.column === 'incident_date') {
                valA = valA ? new Date(valA).getTime() : 0;
                valB = valB ? new Date(valB).getTime() : 0;
            } else {
                valA = String(valA || '').toLowerCase();
                valB = String(valB || '').toLowerCase();
            }

            if (valA < valB) return currentSort.direction === 'asc' ? -1 : 1;
            if (valA > valB) return currentSort.direction === 'asc' ? 1 : -1;
            return 0;
        });
    }

    function renderTable() {
        let start = (currentPage - 1) * perPage;
        let end = start + perPage;
        let pageData = filteredData.slice(start, end);

        let tbody = $('#blottersTable tbody');
        tbody.empty();

        if (pageData.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-scale-balanced" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                        No blotter cases found.
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(item => {
                let incidentDate = item.incident_date ? new Date(item.incident_date).toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: '2-digit', 
                    year: 'numeric' 
                }) : '—';

                tbody.append(`
                    <tr>
                        <td>
                            <span class="case-number">${item.case_number}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="blotter-avatar" style="background:${item.complainant_avatar}">${item.complainant_initial}</div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">${item.complainant_name || 'Unknown'}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">${item.complainant_code || ''}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="blotter-avatar" style="background:${item.respondent_avatar}">${item.respondent_initial}</div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">${item.respondent_name || 'Unknown'}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">${item.respondent_code || ''}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--muted)">${incidentDate}</td>
                        <td style="max-width:150px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis; color:var(--muted); font-size:13px"
                                 title="${item.incident_location || ''}">
                                ${item.incident_location || '—'}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-${item.status}">
                                <span class="status-dot"></span>
                                ${item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : 'Pending'}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-blotter" data-id="${item.id}" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit action-btn-edit edit-blotter" data-id="${item.id}" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn action-btn-status change-status" data-id="${item.id}" data-status="${item.status}" title="Change Status">
                                    <i class="fas fa-rotate" style="color:#f4a20a"></i>
                                </button>
                                <button class="action-btn action-btn-delete delete-blotter" data-id="${item.id}" title="Delete">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
            });
        }

        // Update pagination
        updatePagination();
        
        // Update table info
        let total = filteredData.length;
        let first = total ? start + 1 : 0;
        let last = Math.min(end, total);
        $('#tableInfo').text(`Showing ${first} to ${last} of ${total} entries`);
    }

    function updatePagination() {
        let total = filteredData.length;
        let totalPages = Math.ceil(total / perPage);
        let pagination = $('#paginationLinks');

        if (totalPages <= 1) {
            pagination.empty();
            return;
        }

        let html = '<nav><ul class="pagination">';
        
        // Previous button
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
        </li>`;

        // Page numbers
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }

        // Next button
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
        </li>`;

        html += '</ul></nav>';
        pagination.html(html);

        // Add click handlers
        $('.page-link').on('click', function(e) {
            e.preventDefault();
            let page = $(this).data('page');
            if (page && page !== currentPage) {
                currentPage = page;
                renderTable();
            }
        });
    }

    /* ══════════════════════════════════════════════════════
       ADD BLOTTER
    ══════════════════════════════════════════════════════ */
    $('#addBlotterForm').on('submit', function(e){
        e.preventDefault();
        const $btn = $(this).find('.btn-save').prop('disabled', true)
                         .html('<i class="fas fa-spinner fa-spin me-2"></i>Filing...');

        $.ajax({
            url: '{{ route("blotters.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon:'success', 
                        title:'Case Filed!',
                        text: res.message,
                        timer:2000, 
                        showConfirmButton:false,
                        toast:true, 
                        position:'top-end'
                    });
                    $('#addBlotterModal').modal('hide');
                    $('#addBlotterForm')[0].reset();
                    location.reload();
                }
            },
            error: function(xhr){
                $btn.prop('disabled', false).html('<i class="fas fa-floppy-disk"></i> File Case');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors;
                    let errorMessage = '';
                    
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            const fieldName = key.replace('_id', '').replace('_', ' ');
                            errorMessage += `• ${fieldName}: ${errors[key].join(', ')}\n`;
                        });
                    } else {
                        errorMessage = 'Please check all required fields.';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessage,
                        confirmButtonColor: 'var(--primary)'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                        confirmButtonColor: 'var(--primary)'
                    });
                }
            }
        });
    });

    $('#addBlotterModal').on('hidden.bs.modal', function(){
        $('#addBlotterForm')[0].reset();
    });

    /* ══════════════════════════════════════════════════════
       VIEW BLOTTER
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.view-blotter', function(){
        const id = $(this).data('id');

        $('#viewBlotterSubtitle').text('Loading…');
        $('#viewBlotterBody').html(`
            <div style="text-align:center;padding:40px 0;color:var(--muted)">
                <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                <div style="margin-top:10px;font-size:13px">Loading details…</div>
            </div>`);
        $('#viewBlotterModal').modal('show');

        $.ajax({
            url: '{{ url("blotters") }}/' + id,
            type: 'GET',
            success: function(res){
                if(!res.success) return;
                const b = res.data;
                
                const statusHtml = `<span class="status-badge status-${b.status}">
                    <span class="status-dot"></span>
                    ${b.status.charAt(0).toUpperCase() + b.status.slice(1)}
                </span>`;

                const incidentDate = b.incident_date ? new Date(b.incident_date).toLocaleString() : '—';
                const createdDate = b.created_at ? new Date(b.created_at).toLocaleString() : '—';

                $('#viewBlotterSubtitle').text('Case ' + (b.case_number || '#' + b.id.toString().padStart(6, '0')));
                $('#viewBlotterBody').html(`
                    <div class="v-row"><span class="v-lbl">Case Number</span>
                        <span class="v-val" style="font-weight:700;color:var(--primary)">${b.case_number || '#' + b.id.toString().padStart(6, '0')}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Complainant</span>
                        <span class="v-val">${b.complainant?.full_name || 'Unknown'} ${b.complainant?.resident_code ? '(' + b.complainant.resident_code + ')' : ''}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Respondent</span>
                        <span class="v-val">${b.respondent?.full_name || 'Unknown'} ${b.respondent?.resident_code ? '(' + b.respondent.resident_code + ')' : ''}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Incident Date</span>
                        <span class="v-val">${incidentDate}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Location</span>
                        <span class="v-val">${b.incident_location || '—'}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Description</span>
                        <span class="v-val" style="white-space:pre-wrap">${b.description || '—'}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Status</span>
                        <span class="v-val">${statusHtml}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Filed By</span>
                        <span class="v-val">${b.created_by?.name || 'System'}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Filed On</span>
                        <span class="v-val">${createdDate}</span>
                    </div>
                `);
            },
            error: function(){
                $('#viewBlotterBody').html(`
                    <div style="text-align:center;padding:30px;color:var(--danger)">
                        <i class="fas fa-circle-exclamation" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        Failed to load case details.
                    </div>`);
            }
        });
    });

    /* ══════════════════════════════════════════════════════
       EDIT BLOTTER
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.edit-blotter', function(){
        const id = $(this).data('id');

        $.ajax({
            url: '{{ url("blotters") }}/' + id + '/edit',
            type: 'GET',
            success: function(res){
                if(!res.success) return;
                const b = res.data;
                
                // Clear and populate complainant dropdown
                const complainantSelect = $('#editComplainantId');
                complainantSelect.empty().append('<option value="">— Select Complainant —</option>');
                if (res.residents && res.residents.length > 0) {
                    res.residents.forEach(resident => {
                        const selected = resident.id === b.complainant_id ? 'selected' : '';
                        complainantSelect.append(`<option value="${resident.id}" ${selected}>${resident.full_name} ${resident.resident_code ? '(' + resident.resident_code + ')' : ''}</option>`);
                    });
                }
                
                // Clear and populate respondent dropdown
                const respondentSelect = $('#editRespondentId');
                respondentSelect.empty().append('<option value="">— Select Respondent —</option>');
                if (res.residents && res.residents.length > 0) {
                    res.residents.forEach(resident => {
                        const selected = resident.id === b.respondent_id ? 'selected' : '';
                        respondentSelect.append(`<option value="${resident.id}" ${selected}>${resident.full_name} ${resident.resident_code ? '(' + resident.resident_code + ')' : ''}</option>`);
                    });
                }
                
                $('#editBlotterId').val(b.id);
                $('#editIncidentDate').val(b.incident_date ? b.incident_date.slice(0,16) : '');
                $('#editIncidentLocation').val(b.incident_location || '');
                $('#editDescription').val(b.description || '');
                $('#editStatus').val(b.status);
                $('#editBlotterModal').modal('show');
            },
            error: function(){
                Swal.fire('Error', 'Could not load case data.', 'error');
            }
        });
    });

    $('#editBlotterForm').on('submit', function(e){
        e.preventDefault();
        const id = $('#editBlotterId').val();
        const $btn = $(this).find('.btn-save').prop('disabled', true)
                         .html('<i class="fas fa-spinner fa-spin me-2"></i>Updating…');

        $.ajax({
            url: '{{ url("blotters") }}/' + id,
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon:'success', 
                        title:'Updated!',
                        text: res.message,
                        timer:2000, 
                        showConfirmButton:false,
                        toast:true, 
                        position:'top-end'
                    });
                    $('#editBlotterModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr){
                $btn.prop('disabled', false).html('<i class="fas fa-floppy-disk"></i> Update Case');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors;
                    let errorMessage = '';
                    
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            const fieldName = key.replace('_id', '').replace('_', ' ');
                            errorMessage += `• ${fieldName}: ${errors[key].join(', ')}\n`;
                        });
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessage,
                        confirmButtonColor: 'var(--primary)'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong.',
                        confirmButtonColor: 'var(--primary)'
                    });
                }
            }
        });
    });

    /* ══════════════════════════════════════════════════════
       CHANGE STATUS
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.change-status', function(){
        const id = $(this).data('id');
        const currentStatus = $(this).data('status');
        $('#statusBlotterId').val(id);
        $('#newStatus').val(currentStatus);
        $('#statusNotes').val('');
        $('#statusModal').modal('show');
    });

    $('#statusForm').on('submit', function(e){
        e.preventDefault();
        const id = $('#statusBlotterId').val();
        const $btn = $(this).find('.btn-save').prop('disabled', true)
                         .html('<i class="fas fa-spinner fa-spin"></i> Updating…');

        $.ajax({
            url: '{{ url("blotters") }}/' + id + '/status',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon:'success', 
                        title:'Status Updated!',
                        text: res.message,
                        timer:2000, 
                        showConfirmButton:false,
                        toast:true, 
                        position:'top-end'
                    });
                    $('#statusModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr){
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Update Status');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong.',
                    confirmButtonColor: 'var(--primary)'
                });
            }
        });
    });

    /* ══════════════════════════════════════════════════════
       DELETE BLOTTER
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.delete-blotter', function(){
        const id = $(this).data('id');
        const url = '{{ url("blotters") }}/' + id;
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This blotter case will be permanently removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4d6d',
            cancelButtonColor: '#b0b7cc',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong.',
                            confirmButtonColor: 'var(--primary)'
                        });
                    }
                });
            }
        });
    });

});
</script>
@endpush