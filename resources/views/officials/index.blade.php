@extends('layouts.app')

@section('title', 'Officials')

@section('content')

{{-- ── Page header ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);margin:0;line-height:1.2">
            Barangay Officials
        </h2>
        <p style="font-size:13px;color:var(--muted);margin:3px 0 0">
            Manage elected and appointed barangay officials
        </p>
    </div>
    <button type="button"
            class="btn-add-official"
            data-bs-toggle="modal"
            data-bs-target="#addOfficialModal">
        <i class="fas fa-plus"></i>
        Add Official
    </button>
</div>

{{-- ── Stats strip ── --}}
<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fd;color:#4f63d2"><i class="fas fa-user-tie"></i></div>
        <div>
            <div class="stat-val">{{ $totalOfficials ?? ($officials instanceof \Illuminate\Pagination\LengthAwarePaginator ? $officials->total() : $officials->count()) }}</div>
            <div class="stat-lbl">Total Officials</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e6faf3;color:#1cc88a"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-val">{{ $totalActive ?? $officials->where('status','active')->count() }}</div>
            <div class="stat-lbl">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f3;color:#ff4d6d"><i class="fas fa-circle-xmark"></i></div>
        <div>
            <div class="stat-val">{{ $totalInactive ?? $officials->where('status','inactive')->count() }}</div>
            <div class="stat-lbl">Inactive</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-briefcase"></i></div>
        <div>
            <div class="stat-val">{{ $totalPositions ?? ($positionsCount ?? $officials->pluck('position')->unique()->count()) }}</div>
            <div class="stat-lbl">Positions Filled</div>
        </div>
    </div>
</div>

{{-- ── Table card ── --}}
<div class="card">
    <div class="card-body" style="padding:0">

        {{-- Table controls with search at right end --}}
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
            {{-- Left side: Show entries and filters --}}
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                {{-- Show entries --}}
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="font-size:13px;color:var(--muted)">Show</span>
                    <select id="showEntries" class="off-filter" style="width:70px">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span style="font-size:13px;color:var(--muted)">entries</span>
                </div>

                {{-- Filters --}}
                <select id="filterStatus" class="off-filter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="filterPosition" class="off-filter">
                    <option value="">All Positions</option>
                    @foreach($positions ?? $officials->pluck('position')->unique() as $position)
                    <option value="{{ $position }}">{{ $position }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Right side: Search bar --}}
            <div class="off-search-wrap">
                <i class="fas fa-magnifying-glass off-search-ico"></i>
                <input type="text" id="officialSearch" class="off-search" placeholder="Search officials…">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="officialsTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="official">Official <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="position">Position <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="term_start">Term Start <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="term_end">Term End <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="status">Status <i class="fas fa-sort sort-icon"></i></th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($officials as $official)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="off-avatar" style="background:{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($official->resident->full_name ?? 'O') % 5] }}">
                                    {{ strtoupper(substr($official->resident->full_name ?? 'O', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">{{ $official->resident->full_name ?? '' }}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">{{ $official->resident->resident_code ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="position-pill" style="background:var(--plt);color:var(--primary);">
                                {{ $official->position }}
                            </span>
                        </td>
                        <td style="font-size:13px;color:var(--muted)">
                            {{ \Carbon\Carbon::parse($official->term_start)->format('M d, Y') }}
                        </td>
                        <td style="font-size:13px;color:var(--muted)">
                            @if($official->term_end)
                                {{ \Carbon\Carbon::parse($official->term_end)->format('M d, Y') }}
                            @else
                                <span style="color:var(--border)">Present</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ ($official->status ?? 'inactive') }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($official->status ?? 'inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-official"
                                        data-id="{{ $official->id ?? '' }}"
                                        title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit action-btn-edit edit-official"
                                        data-id="{{ $official->id ?? '' }}"
                                        title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn action-btn-delete delete-official"
                                        data-id="{{ $official->id ?? '' }}"
                                        title="Delete">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-user-tie-slash" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                            No officials found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination: Info on left, Links on right --}}
        @if($officials instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $officials->firstItem() ?? 0 }} to {{ $officials->lastItem() ?? 0 }} of {{ $officials->total() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;">
                {{ $officials->links() }}
            </div>
        </div>
        @else
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $officials->count() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;">
                {{-- No pagination links for non-paginated data --}}
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ════════════════════════════════════════
     ADD OFFICIAL MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="addOfficialModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content off-modal">

            <div class="off-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <h5 class="off-modal-title">Add New Official</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Assign a resident to a barangay position</p>
                    </div>
                </div>
                <button type="button" class="off-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="addOfficialForm">
                @csrf
                <div class="off-modal-body">

                    {{-- Resident Selection --}}
                    <div class="form-section-label">Official Information</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="off-field">
                                <label class="off-label">Resident <span class="req">*</span></label>
                                <select class="off-input" name="resident_id" required>
                                    <option value="">— Select Resident —</option>
                                    @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}">
                                            {{ $resident->full_name }} 
                                            @if($resident->resident_code)({{ $resident->resident_code }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="off-field">
                                <label class="off-label">Position <span class="req">*</span></label>
                                <select class="off-input" name="position" required>
                                    <option value="">— Select Position —</option>
                                    <optgroup label="Executive">
                                        <option value="Barangay Captain">Barangay Captain</option>
                                        <option value="Barangay Secretary">Barangay Secretary</option>
                                        <option value="Barangay Treasurer">Barangay Treasurer</option>
                                    </optgroup>
                                    <optgroup label="Kagawad">
                                        <option value="Kagawad - Peace & Order">Kagawad – Peace &amp; Order</option>
                                        <option value="Kagawad - Health">Kagawad – Health</option>
                                        <option value="Kagawad - Education">Kagawad – Education</option>
                                        <option value="Kagawad - Infrastructure">Kagawad – Infrastructure</option>
                                        <option value="Kagawad - Environment">Kagawad – Environment</option>
                                        <option value="Kagawad - Social Services">Kagawad – Social Services</option>
                                        <option value="Kagawad - Livelihood">Kagawad – Livelihood</option>
                                    </optgroup>
                                    <optgroup label="Youth">
                                        <option value="SK Chairman">SK Chairman</option>
                                        <option value="SK Secretary">SK Secretary</option>
                                        <option value="SK Treasurer">SK Treasurer</option>
                                        <option value="SK Kagawad">SK Kagawad</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Term Details --}}
                    <div class="form-section-label" style="margin-top:24px">Term Details</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="off-field">
                                <label class="off-label">Term Start <span class="req">*</span></label>
                                <input type="date" class="off-input" name="term_start" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="off-field">
                                <label class="off-label">Term End</label>
                                <input type="date" class="off-input" name="term_end">
                                <p style="font-size:11.5px;color:var(--muted);margin-top:5px;margin-bottom:0">
                                    <i class="fas fa-circle-info"></i> Leave blank if currently serving.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-section-label" style="margin-top:24px">Status</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="off-field">
                                <label class="off-label">Status</label>
                                <select class="off-input" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="off-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Save Official
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     EDIT OFFICIAL MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="editOfficialModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content off-modal">

            <div class="off-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <h5 class="off-modal-title">Edit Official</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Update official details</p>
                    </div>
                </div>
                <button type="button" class="off-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="editOfficialForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editOfficialId">
                <div class="off-modal-body">

                    {{-- Resident Selection --}}
                    <div class="form-section-label">Official Information</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="off-field">
                                <label class="off-label">Resident <span class="req">*</span></label>
                                <select class="off-input" id="editResidentId" name="resident_id" required>
                                    <option value="">— Select Resident —</option>
                                    @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}">
                                            {{ $resident->full_name }} 
                                            @if($resident->resident_code)({{ $resident->resident_code }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="off-field">
                                <label class="off-label">Position <span class="req">*</span></label>
                                <select class="off-input" id="editPosition" name="position" required>
                                    <option value="">— Select Position —</option>
                                    <optgroup label="Executive">
                                        <option value="Barangay Captain">Barangay Captain</option>
                                        <option value="Barangay Secretary">Barangay Secretary</option>
                                        <option value="Barangay Treasurer">Barangay Treasurer</option>
                                    </optgroup>
                                    <optgroup label="Kagawad">
                                        <option value="Kagawad - Peace & Order">Kagawad – Peace &amp; Order</option>
                                        <option value="Kagawad - Health">Kagawad – Health</option>
                                        <option value="Kagawad - Education">Kagawad – Education</option>
                                        <option value="Kagawad - Infrastructure">Kagawad – Infrastructure</option>
                                        <option value="Kagawad - Environment">Kagawad – Environment</option>
                                        <option value="Kagawad - Social Services">Kagawad – Social Services</option>
                                        <option value="Kagawad - Livelihood">Kagawad – Livelihood</option>
                                    </optgroup>
                                    <optgroup label="Youth">
                                        <option value="SK Chairman">SK Chairman</option>
                                        <option value="SK Secretary">SK Secretary</option>
                                        <option value="SK Treasurer">SK Treasurer</option>
                                        <option value="SK Kagawad">SK Kagawad</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Term Details --}}
                    <div class="form-section-label" style="margin-top:24px">Term Details</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="off-field">
                                <label class="off-label">Term Start <span class="req">*</span></label>
                                <input type="date" class="off-input" id="editTermStart" name="term_start" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="off-field">
                                <label class="off-label">Term End</label>
                                <input type="date" class="off-input" id="editTermEnd" name="term_end">
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-section-label" style="margin-top:24px">Status</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="off-field">
                                <label class="off-label">Status</label>
                                <select class="off-input" id="editStatus" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="off-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Update Official
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     VIEW OFFICIAL MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="viewOfficialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content off-modal">

            <div class="off-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <h5 class="off-modal-title">Official Details</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0" id="viewOfficialSubtitle">Loading…</p>
                    </div>
                </div>
                <button type="button" class="off-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <div class="off-modal-body" id="viewOfficialBody">
                <div style="text-align:center;padding:40px 0;color:var(--muted)">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                    <div style="margin-top:10px;font-size:13px">Loading details…</div>
                </div>
            </div>

            <div class="off-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ════ Component Styles ════ --}}
<style>
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
.btn-add-official{
    display:inline-flex;align-items:center;gap:8px;
    padding:9px 20px;
    border-radius:10px;
    border:none;cursor:pointer;
    background:var(--primary);color:#fff;
    font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;
    transition:all var(--dur) var(--ease);
    box-shadow:0 4px 14px rgba(79,99,210,.35);
}
.btn-add-official:hover{
    background:#3d4fc0;
    box-shadow:0 6px 20px rgba(79,99,210,.45);
    transform:translateY(-1px);
}

/* ─── Search & filter ─── */
.off-search-wrap{
    position:relative;display:flex;align-items:center;
}
.off-search-ico{
    position:absolute;left:12px;color:var(--muted);font-size:13px;pointer-events:none;
}
.off-search{
    padding:8px 14px 8px 36px;
    border:1px solid var(--border);
    border-radius:9px;background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);
    width:260px;outline:none;
    transition:border-color var(--dur),box-shadow var(--dur);
}
.off-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.off-filter{
    padding:8px 14px;
    border:1px solid var(--border);
    border-radius:9px;background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);
    outline:none;cursor:pointer;
    transition:border-color var(--dur),box-shadow var(--dur);
}
.off-filter:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}

/* ─── Table overrides ─── */
#officialsTable thead th{
    font-size:10.5px;font-weight:700;letter-spacing:.08em;
    text-transform:uppercase;color:var(--muted);
    background:#f8f9fd;padding:13px 16px;
    border-bottom:1px solid var(--border);
    position:relative;
}
#officialsTable thead th.sortable{
    cursor:pointer;
    user-select:none;
}
#officialsTable thead th.sortable:hover{
    background:#eef0fd;
    color:var(--primary);
}
#officialsTable thead th.sortable:hover .sort-icon{
    opacity:1;
}
.sort-icon{
    margin-left:5px;
    font-size:10px;
    opacity:0.3;
    transition:opacity var(--dur);
}
#officialsTable tbody td{
    padding:14px 16px;font-size:13.5px;
    border-bottom:1px solid var(--border);
    vertical-align:middle;
}
#officialsTable tbody tr:last-child td{border-bottom:none}
#officialsTable tbody tr{transition:background var(--dur)}
#officialsTable tbody tr:hover{background:#f8f9fd}

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

/* ─── Avatar ─── */
.off-avatar{
    width:36px;height:36px;border-radius:10px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:14px;
}

/* ─── Position pill ─── */
.position-pill{
    display:inline-flex;align-items:center;gap:5px;
    font-size:12px;font-weight:600;
    padding:4px 11px;border-radius:100px;
}

/* ─── Status badge ─── */
.status-badge{
    display:inline-flex;align-items:center;gap:6px;
    font-size:11.5px;font-weight:700;padding:4px 11px;border-radius:100px;
}
.status-active{background:#e6faf3;color:#1cc88a}
.status-inactive{background:#fff0f3;color:#ff4d6d}
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

/* ─── Empty state ─── */
.empty-state{text-align:center;padding:48px 16px;color:var(--muted);font-size:14px}

/* ─── Modal ─── */
.off-modal{border:none;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(15,22,35,.22)}
.off-modal-header{
    display:flex;align-items:center;justify-content:space-between;
    padding:22px 28px;border-bottom:1px solid var(--border);
    background:#fff;
}
.modal-icon-wrap{
    width:42px;height:42px;border-radius:12px;
    background:var(--plt);color:var(--primary);
    display:flex;align-items:center;justify-content:center;font-size:17px;
}
.off-modal-title{
    font-family:'Syne',sans-serif;font-weight:800;font-size:17px;
    color:var(--text);margin:0;
}
.off-modal-close{
    width:34px;height:34px;border-radius:9px;
    border:1px solid var(--border);background:none;
    color:var(--muted);cursor:pointer;font-size:14px;
    display:flex;align-items:center;justify-content:center;
    transition:all var(--dur);
}
.off-modal-close:hover{background:var(--bg);color:var(--text)}
.off-modal-body{padding:24px 28px}
.off-modal-footer{
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
.off-field{display:flex;flex-direction:column;gap:6px}
.off-label{font-size:12.5px;font-weight:600;color:var(--text)}
.req{color:var(--danger)}
.off-input{
    padding:9px 13px;
    border:1px solid var(--border);border-radius:9px;
    background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text);
    outline:none;width:100%;
    transition:border-color var(--dur),box-shadow var(--dur),background var(--dur);
}
.off-input:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(79,99,210,.12);
    background:#fff;
}
.off-input option{background:#fff;color:var(--text)}

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
    let officialsData = [];
    let currentSort = { column: 'official', direction: 'asc' };
    let currentPage = 1;
    let perPage = 10;
    let filteredData = [];

    // Store all officials data for client-side sorting and filtering
    @foreach($officials as $official)
    officialsData.push({
        id: {{ $official->id }},
        official_id: {{ $official->resident->id ?? 0 }},
        official_name: '{{ $official->resident->full_name ?? '' }}',
        official_code: '{{ $official->resident->resident_code ?? '' }}',
        position: '{{ $official->position }}',
        term_start: '{{ $official->term_start }}',
        term_end: '{{ $official->term_end }}',
        status: '{{ $official->status ?? '' }}',
        avatar_color: '{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($official->resident->full_name ?? 'O') % 5] }}',
        avatar_initial: '{{ strtoupper(substr($official->resident->full_name ?? 'O', 0, 1)) }}'
    });
    @endforeach

    filteredData = [...officialsData];

    // Initialize table
    renderTable();

    // Show entries change
    $('#showEntries').on('change', function() {
        perPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
    });

    // Search functionality
    $('#officialSearch').on('input', function() {
        let searchTerm = $(this).val().toLowerCase();
        filterData(searchTerm);
    });

    // Status and position filters
    $('#filterStatus, #filterPosition').on('change', function() {
        filterData($('#officialSearch').val().toLowerCase());
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
        let position = $('#filterPosition').val().toLowerCase();

        filteredData = officialsData.filter(item => {
            let matchesSearch = searchTerm === '' || 
                item.official_name.toLowerCase().includes(searchTerm) ||
                item.official_code.toLowerCase().includes(searchTerm) ||
                item.position.toLowerCase().includes(searchTerm);

            let matchesStatus = status === '' || item.status.toLowerCase() === status;
            let matchesPosition = position === '' || item.position.toLowerCase().includes(position);

            return matchesSearch && matchesStatus && matchesPosition;
        });

        sortData();
        currentPage = 1;
        renderTable();
    }

    function sortData() {
        filteredData.sort((a, b) => {
            let valA = a[currentSort.column];
            let valB = b[currentSort.column];

            if (currentSort.column === 'term_start' || currentSort.column === 'term_end') {
                valA = valA ? new Date(valA).getTime() : 0;
                valB = valB ? new Date(valB).getTime() : 0;
            } else {
                valA = String(valA).toLowerCase();
                valB = String(valB).toLowerCase();
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

        let tbody = $('#officialsTable tbody');
        tbody.empty();

        if (pageData.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="6" class="empty-state">
                        <i class="fas fa-user-tie-slash" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                        No officials found.
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(item => {
                let termEndDisplay = item.term_end ? 
                    new Date(item.term_end).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) :
                    '<span style="color:var(--border)">Present</span>';

                tbody.append(`
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="off-avatar" style="background:${item.avatar_color}">${item.avatar_initial}</div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">${item.official_name}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">${item.official_code}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="position-pill" style="background:var(--plt);color:var(--primary);">
                                ${item.position}
                            </span>
                        </td>
                        <td style="font-size:13px;color:var(--muted)">
                            ${new Date(item.term_start).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}
                        </td>
                        <td style="font-size:13px;color:var(--muted)">${termEndDisplay}</td>
                        <td>
                            <span class="status-badge status-${item.status || 'inactive'}">
                                <span class="status-dot"></span>
                                ${item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-official" data-id="${item.id}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit action-btn-edit edit-official" data-id="${item.id}" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn action-btn-delete delete-official" data-id="${item.id}" title="Delete">
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
       ADD OFFICIAL
    ══════════════════════════════════════════════════════ */
    $('#addOfficialForm').on('submit', function(e){
        e.preventDefault();
        const $btn = $(this).find('.btn-save').prop('disabled', true)
                         .html('<i class="fas fa-spinner fa-spin me-2"></i>Saving…');

        $.ajax({
            url:  '{{ route("officials.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon:'success', title:'Official Added!',
                        text: res.message,
                        timer:2500, showConfirmButton:false,
                        toast:true, position:'top-end'
                    });
                    $('#addOfficialModal').modal('hide');
                    $('#addOfficialForm')[0].reset();
                    location.reload();
                }
            },
            error: function(xhr){
                const errors = xhr.responseJSON?.errors;
                const msg = errors ? Object.values(errors).flat().join('\n') : 'Something went wrong.';
                Swal.fire('Validation Error', msg, 'error');
            },
            complete: function(){
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-floppy-disk"></i> Save Official');
            }
        });
    });

    $('#addOfficialModal').on('hidden.bs.modal', function(){
        $('#addOfficialForm')[0].reset();
    });

    /* ══════════════════════════════════════════════════════
       VIEW OFFICIAL
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.view-official', function(){
        const id = $(this).data('id');

        $('#viewOfficialSubtitle').text('Loading…');
        $('#viewOfficialBody').html(`
            <div style="text-align:center;padding:40px 0;color:var(--muted)">
                <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                <div style="margin-top:10px;font-size:13px">Loading details…</div>
            </div>`);
        $('#viewOfficialModal').modal('show');

        $.ajax({
            url:  '{{ url("officials") }}/' + id,
            type: 'GET',
            success: function(res){
                if(!res.success) return;
                const o = res.data;
                const fullName = o.resident ? o.resident.full_name : '—';
                const code = o.resident?.resident_code ?? '';

                const statusHtml = o.status === 'active'
                    ? `<span class="status-badge status-active"><span class="status-dot"></span>Active</span>`
                    : `<span class="status-badge status-inactive"><span class="status-dot"></span>Inactive</span>`;

                const termEnd = o.term_end
                    ? new Date(o.term_end).toLocaleDateString('en-US',{month:'long',day:'2-digit',year:'numeric'})
                    : '<span style="color:var(--border)">Present</span>';

                const termStart = new Date(o.term_start).toLocaleDateString('en-US',{month:'long',day:'2-digit',year:'numeric'});

                $('#viewOfficialSubtitle').text(fullName);
                $('#viewOfficialBody').html(`
                    <div style="display:flex;align-items:center;gap:14px;padding:0 0 20px;border-bottom:1px solid var(--border);margin-bottom:4px">
                        <div style="width:52px;height:52px;border-radius:50%;background:var(--plt);
                                    display:flex;align-items:center;justify-content:center;
                                    font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--primary);flex-shrink:0">
                            ${fullName.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:var(--text)">${fullName}</div>
                            <div style="font-size:12px;color:var(--muted);margin-top:2px">${code}</div>
                        </div>
                    </div>
                    <div class="v-row">
                        <span class="v-lbl">Position</span>
                        <span class="v-val"><span class="position-pill" style="background:var(--plt);color:var(--primary);padding:3px 11px;">${o.position}</span></span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Term Start</span><span class="v-val">${termStart}</span></div>
                    <div class="v-row"><span class="v-lbl">Term End</span><span class="v-val">${termEnd}</span></div>
                    <div class="v-row"><span class="v-lbl">Status</span><span class="v-val">${statusHtml}</span></div>
                `);
            },
            error: function(){
                $('#viewOfficialBody').html(`
                    <div style="text-align:center;padding:30px;color:var(--danger)">
                        <i class="fas fa-circle-exclamation" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        Failed to load official details.
                    </div>`);
            }
        });
    });

    /* ══════════════════════════════════════════════════════
       EDIT OFFICIAL
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.edit-official', function(){
        const id = $(this).data('id');

        $.ajax({
            url:  '{{ url("officials") }}/' + id + '/edit',
            type: 'GET',
            success: function(res){
                if(!res.success) return;
                const o = res.data;
                $('#editOfficialId').val(o.id);
                $('#editResidentId').val(o.resident_id);
                $('#editPosition').val(o.position);
                $('#editTermStart').val(o.term_start);
                $('#editTermEnd').val(o.term_end ?? '');
                $('#editStatus').val(o.status);
                $('#editOfficialModal').modal('show');
            },
            error: function(){
                Swal.fire('Error', 'Could not load official data.', 'error');
            }
        });
    });

    $('#editOfficialForm').on('submit', function(e){
        e.preventDefault();
        const id   = $('#editOfficialId').val();
        const $btn = $(this).find('.btn-save').prop('disabled', true)
                         .html('<i class="fas fa-spinner fa-spin me-2"></i>Updating…');

        $.ajax({
            url:  '{{ url("officials") }}/' + id,
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon:'success', title:'Updated!',
                        text: res.message,
                        timer:2500, showConfirmButton:false,
                        toast:true, position:'top-end'
                    });
                    $('#editOfficialModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr){
                const errors = xhr.responseJSON?.errors;
                const msg = errors ? Object.values(errors).flat().join('\n') : 'Something went wrong.';
                Swal.fire('Validation Error', msg, 'error');
            },
            complete: function(){
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-floppy-disk"></i> Update Official');
            }
        });
    });

    /* ══════════════════════════════════════════════════════
       DELETE OFFICIAL
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.delete-official', function(){
        const id  = $(this).data('id');
        const url = '{{ url("officials") }}/' + id;
        confirmDelete(url, 'This official record will be permanently removed.');
    });

});
</script>
@endpush