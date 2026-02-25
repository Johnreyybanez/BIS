@extends('layouts.app')

@section('title', 'Households')

@section('content')

{{-- ── Page header ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);margin:0;line-height:1.2">
            Households
        </h2>
        <p style="font-size:13px;color:var(--muted);margin:3px 0 0">
            Manage all registered barangay households
        </p>
    </div>
    <button type="button"
            class="btn-add-household"
            data-bs-toggle="modal"
            data-bs-target="#addHouseholdModal">
        <i class="fas fa-plus"></i>
        Add Household
    </button>
</div>

{{-- ── Stats strip ── --}}
<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fd;color:#4f63d2"><i class="fas fa-house"></i></div>
        <div>
            <div class="stat-val">{{ number_format($totalHouseholds) }}</div>
            <div class="stat-lbl">Total Households</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e6faf3;color:#1cc88a"><i class="fas fa-user-tie"></i></div>
        <div>
            <div class="stat-val">{{ number_format($withHead) }}</div>
            <div class="stat-lbl">With Head</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-map-pin"></i></div>
        <div>
            <div class="stat-val">{{ number_format($totalPuroks) }}</div>
            <div class="stat-lbl">Puroks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f3;color:#ff4d6d"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-val">{{ number_format($noHead) }}</div>
            <div class="stat-lbl">No Head Assigned</div>
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
                    <select id="showEntries" class="hh-filter" style="width:70px">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span style="font-size:13px;color:var(--muted)">entries</span>
                </div>

                {{-- Filters --}}
                <select id="filterPurok" class="hh-filter">
                    <option value="">All Puroks</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok }}">{{ $purok }}</option>
                    @endforeach
                </select>
                <select id="filterHead" class="hh-filter">
                    <option value="">All Households</option>
                    <option value="with_head">With Head</option>
                    <option value="no_head">No Head</option>
                </select>
            </div>

            {{-- Right side: Search bar --}}
            <div class="hh-search-wrap">
                <i class="fas fa-magnifying-glass hh-search-ico"></i>
                <input type="text" id="householdSearch" class="hh-search" placeholder="Search households…">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="householdsTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="number">Household # <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="purok">Purok <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="address">Address <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="head">Household Head <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="members">Members <i class="fas fa-sort sort-icon"></i></th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($households as $household)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="hh-avatar" style="background:#4f63d2">
                                    <i class="fas fa-house"></i>
                                </div>
                                <span class="hh-code">{{ $household->household_number }}</span>
                            </div>
                        </td>
                        <td>
                            @if($household->purok)
                                <span class="purok-pill">{{ $household->purok }}</span>
                            @else
                                <span style="color:var(--border)">—</span>
                            @endif
                        </td>
                        <td style="max-width:200px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis; color:var(--muted); font-size:13px"
                                 title="{{ $household->address }}">
                                {{ $household->address ?? '—' }}
                            </div>
                        </td>
                        <td>
                            @if($household->headResident)
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="resident-mini-avatar" style="background:{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($household->headResident->full_name) % 5] }}">
                                        {{ strtoupper(substr($household->headResident->full_name, 0, 1)) }}
                                    </div>
                                    <span style="font-weight:500; font-size:13.5px">{{ $household->headResident->full_name }}</span>
                                </div>
                            @else
                                <span class="badge-unassigned">
                                    <i class="fas fa-circle-exclamation" style="font-size:10px"></i>
                                    Unassigned
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="member-count">
                                <i class="fas fa-users"></i>
                                {{ $household->residents_count }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-household"
                                        data-id="{{ $household->id }}"
                                        title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit action-btn-edit edit-household"
                                        data-id="{{ $household->id }}"
                                        title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn action-btn-delete delete-household"
                                        data-id="{{ $household->id }}"
                                        title="Delete">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-house-circle-exclamation" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                            No households found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination: Info on left, Links on right --}}
        @if($households instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $households->firstItem() ?? 0 }} to {{ $households->lastItem() ?? 0 }} of {{ $households->total() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;">
                {{ $households->links() }}
            </div>
        </div>
        @else
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $households->count() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;"></div>
        </div>
        @endif

    </div>
</div>

{{-- ════════════════════════════════════════
     ADD HOUSEHOLD MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="addHouseholdModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content hh-modal">

            <div class="hh-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-house"></i></div>
                    <div>
                        <h5 class="hh-modal-title">Add New Household</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Fill in the household information below</p>
                    </div>
                </div>
                <button type="button" class="hh-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="addHouseholdForm">
                @csrf
                <div class="hh-modal-body">

                    <div class="form-section-label">Household Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="hh-field">
                                <label class="hh-label">Household Number <span class="req">*</span></label>
                                <input type="text" class="hh-input" name="household_number"
                                       placeholder="e.g. HH-2024-001" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="hh-field">
                                <label class="hh-label">Purok</label>
                                <select class="hh-input" name="purok">
                                    <option value="">— Select Purok —</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="Purok {{ $i }}">Purok {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="hh-field">
                                <label class="hh-label">Full Address</label>
                                <textarea class="hh-input" name="address" rows="2"
                                          placeholder="Street, Barangay, Municipality…"
                                          style="resize:none;min-height:70px"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="hh-field">
                                <label class="hh-label">Household Head</label>
                                <select class="hh-input" name="head_resident_id">
                                    <option value="">— Select Resident (optional) —</option>
                                    @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}">
                                            {{ $resident->full_name }}
                                            @if($resident->resident_code)({{ $resident->resident_code }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                <p style="font-size:11.5px;color:var(--muted);margin-top:5px;margin-bottom:0">
                                    <i class="fas fa-circle-info"></i> You can assign the head later after adding members.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="hh-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Save Household
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     EDIT HOUSEHOLD MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="editHouseholdModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content hh-modal">

            <div class="hh-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-house"></i></div>
                    <div>
                        <h5 class="hh-modal-title">Edit Household</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Update household details</p>
                    </div>
                </div>
                <button type="button" class="hh-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form id="editHouseholdForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editHouseholdId">
                <div class="hh-modal-body">

                    <div class="form-section-label">Household Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="hh-field">
                                <label class="hh-label">Household Number <span class="req">*</span></label>
                                <input type="text" class="hh-input" id="editHouseholdNumber"
                                       name="household_number" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="hh-field">
                                <label class="hh-label">Purok</label>
                                <select class="hh-input" id="editPurok" name="purok">
                                    <option value="">— Select Purok —</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="Purok {{ $i }}">Purok {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="hh-field">
                                <label class="hh-label">Full Address</label>
                                <textarea class="hh-input" id="editAddress" name="address"
                                          rows="2" style="resize:none;min-height:70px"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="hh-field">
                                <label class="hh-label">Household Head</label>
                                <select class="hh-input" id="editHeadResidentId" name="head_resident_id">
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
                    </div>

                </div>

                <div class="hh-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Update Household
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     VIEW HOUSEHOLD MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="viewHouseholdModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content hh-modal">

            <div class="hh-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-house"></i></div>
                    <div>
                        <h5 class="hh-modal-title">Household Details</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0" id="viewHouseholdSubtitle">Loading…</p>
                    </div>
                </div>
                <button type="button" class="hh-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <div class="hh-modal-body" id="viewHouseholdBody">
                <div style="text-align:center;padding:40px 0;color:var(--muted)">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                    <div style="margin-top:10px;font-size:13px">Loading details…</div>
                </div>
            </div>

            <div class="hh-modal-footer">
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
.btn-add-household{
    display:inline-flex;align-items:center;gap:8px;
    padding:9px 20px;
    border-radius:10px;
    border:none;cursor:pointer;
    background:var(--primary);color:#fff;
    font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;
    transition:all var(--dur) var(--ease);
    box-shadow:0 4px 14px rgba(79,99,210,.35);
}
.btn-add-household:hover{
    background:#3d4fc0;
    box-shadow:0 6px 20px rgba(79,99,210,.45);
    transform:translateY(-1px);
}

/* ─── Search & filter ─── */
.hh-search-wrap{
    position:relative;display:flex;align-items:center;
}
.hh-search-ico{
    position:absolute;left:12px;color:var(--muted);font-size:13px;pointer-events:none;
}
.hh-search{
    padding:8px 14px 8px 36px;
    border:1px solid var(--border);
    border-radius:9px;background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);
    width:260px;outline:none;
    transition:border-color var(--dur),box-shadow var(--dur);
}
.hh-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.hh-filter{
    padding:8px 14px;
    border:1px solid var(--border);
    border-radius:9px;background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);
    outline:none;cursor:pointer;
    transition:border-color var(--dur),box-shadow var(--dur);
}
.hh-filter:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}

/* ─── Table overrides ─── */
#householdsTable thead th{
    font-size:10.5px;font-weight:700;letter-spacing:.08em;
    text-transform:uppercase;color:var(--muted);
    background:#f8f9fd;padding:13px 16px;
    border-bottom:1px solid var(--border);
    position:relative;
}
#householdsTable thead th.sortable{
    cursor:pointer;
    user-select:none;
}
#householdsTable thead th.sortable:hover{
    background:#eef0fd;
    color:var(--primary);
}
#householdsTable thead th.sortable:hover .sort-icon{
    opacity:1;
}
.sort-icon{
    margin-left:5px;
    font-size:10px;
    opacity:0.3;
    transition:opacity var(--dur);
}
#householdsTable tbody td{
    padding:14px 16px;font-size:13.5px;
    border-bottom:1px solid var(--border);
    vertical-align:middle;
}
#householdsTable tbody tr:last-child td{border-bottom:none}
#householdsTable tbody tr{transition:background var(--dur)}
#householdsTable tbody tr:hover{background:#f8f9fd}

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

/* ─── Household code/avatar ─── */
.hh-avatar{
    width:34px;height:34px;border-radius:9px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:13px;
}
.hh-code{
    font-family:'Syne',sans-serif;font-size:11.5px;font-weight:700;
    color:var(--primary);background:var(--plt);
    padding:3px 9px;border-radius:6px;letter-spacing:.04em;
}

/* ─── Purok pill ─── */
.purok-pill{
    display:inline-flex;align-items:center;
    background:#f0f2f8;color:var(--muted);
    padding:4px 11px;border-radius:100px;
    font-size:12px;font-weight:600;
}

/* ─── Resident mini avatar ─── */
.resident-mini-avatar{
    width:28px;height:28px;border-radius:50%;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:11px;
}

/* ─── Badge unassigned ─── */
.badge-unassigned{
    display:inline-flex;align-items:center;gap:4px;
    background:#fff8e6;color:#f5a623;
    padding:4px 10px;border-radius:100px;
    font-size:11px;font-weight:600;
}

/* ─── Member count ─── */
.member-count{
    display:inline-flex;align-items:center;gap:6px;
    background:var(--plt);padding:4px 10px;border-radius:100px;
    font-size:12px;font-weight:600;color:var(--primary);
}
.member-count i{font-size:10px;color:var(--primary)}

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
.hh-modal{border:none;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(15,22,35,.22)}
.hh-modal-header{
    display:flex;align-items:center;justify-content:space-between;
    padding:22px 28px;border-bottom:1px solid var(--border);
    background:#fff;
}
.modal-icon-wrap{
    width:42px;height:42px;border-radius:12px;
    background:var(--plt);color:var(--primary);
    display:flex;align-items:center;justify-content:center;font-size:17px;
}
.hh-modal-title{
    font-family:'Syne',sans-serif;font-weight:800;font-size:17px;
    color:var(--text);margin:0;
}
.hh-modal-close{
    width:34px;height:34px;border-radius:9px;
    border:1px solid var(--border);background:none;
    color:var(--muted);cursor:pointer;font-size:14px;
    display:flex;align-items:center;justify-content:center;
    transition:all var(--dur);
}
.hh-modal-close:hover{background:var(--bg);color:var(--text)}
.hh-modal-body{padding:24px 28px}
.hh-modal-footer{
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
.hh-field{display:flex;flex-direction:column;gap:6px}
.hh-label{font-size:12.5px;font-weight:600;color:var(--text)}
.req{color:var(--danger)}
.hh-input{
    padding:9px 13px;
    border:1px solid var(--border);border-radius:9px;
    background:var(--bg);
    font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text);
    outline:none;width:100%;
    transition:border-color var(--dur),box-shadow var(--dur),background var(--dur);
}
.hh-input:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(79,99,210,.12);
    background:#fff;
}
.hh-input option{background:#fff;color:var(--text)}

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
       color:var(--muted);min-width:130px;flex-shrink:0;padding-top:2px}
.v-val{font-size:13.5px;color:var(--text);font-weight:500}

/* ─── Member list in view modal ─── */
.member-item{
    display:flex;align-items:center;justify-content:space-between;
    padding:9px 0;border-bottom:1px solid var(--border);
}
.member-item:last-child{border-bottom:none}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let householdsData = [];
    let currentSort = { column: 'number', direction: 'asc' };
    let currentPage = 1;
    let perPage = 10;
    let filteredData = [];

    // Store all households data for client-side sorting and filtering
    @foreach($households as $household)
    householdsData.push({
        id: {{ $household->id }},
        number: '{{ $household->household_number }}',
        purok: '{{ $household->purok ?? '' }}',
        address: '{{ $household->address ?? '' }}',
        head_id: {{ $household->head_resident_id ?? 'null' }},
        head_name: '{{ $household->headResident->full_name ?? '' }}',
        head_initial: '{{ $household->headResident ? strtoupper(substr($household->headResident->full_name, 0, 1)) : '' }}',
        head_color: '{{ $household->headResident ? ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($household->headResident->full_name) % 5] : '#b0b7cc' }}',
        members: {{ $household->residents_count ?? 0 }}
    });
    @endforeach

    filteredData = [...householdsData];

    // Initialize table
    renderTable();

    // Show entries change
    $('#showEntries').on('change', function() {
        perPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
    });

    // Search functionality
    $('#householdSearch').on('input', function() {
        let searchTerm = $(this).val().toLowerCase();
        filterData(searchTerm);
    });

    // Purok and head filters
    $('#filterPurok, #filterHead').on('change', function() {
        filterData($('#householdSearch').val().toLowerCase());
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
        let purok = $('#filterPurok').val();
        let head = $('#filterHead').val();

        filteredData = householdsData.filter(item => {
            let matchesSearch = searchTerm === '' || 
                item.number.toLowerCase().includes(searchTerm) ||
                item.purok.toLowerCase().includes(searchTerm) ||
                item.address.toLowerCase().includes(searchTerm) ||
                item.head_name.toLowerCase().includes(searchTerm);

            let matchesPurok = purok === '' || item.purok === purok;
            
            let matchesHead = true;
            if (head === 'with_head') matchesHead = item.head_id !== null;
            if (head === 'no_head') matchesHead = item.head_id === null;

            return matchesSearch && matchesPurok && matchesHead;
        });

        sortData();
        currentPage = 1;
        renderTable();
    }

    function sortData() {
        filteredData.sort((a, b) => {
            let valA = a[currentSort.column];
            let valB = b[currentSort.column];

            if (currentSort.column === 'members') {
                valA = parseInt(valA) || 0;
                valB = parseInt(valB) || 0;
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

        let tbody = $('#householdsTable tbody');
        tbody.empty();

        if (pageData.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="6" class="empty-state">
                        <i class="fas fa-house-circle-exclamation" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                        No households found.
                    </td>
                </tr>
            `);
        } else {
            pageData.forEach(item => {
                let headDisplay = item.head_id ? 
                    `<div style="display:flex;align-items:center;gap:8px">
                        <div class="resident-mini-avatar" style="background:${item.head_color}">${item.head_initial}</div>
                        <span style="font-weight:500; font-size:13.5px">${item.head_name}</span>
                    </div>` :
                    `<span class="badge-unassigned">
                        <i class="fas fa-circle-exclamation" style="font-size:10px"></i>
                        Unassigned
                    </span>`;

                tbody.append(`
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="hh-avatar" style="background:#4f63d2">
                                    <i class="fas fa-house"></i>
                                </div>
                                <span class="hh-code">${item.number}</span>
                            </div>
                        </td>
                        <td>
                            ${item.purok ? 
                                `<span class="purok-pill">${item.purok}</span>` : 
                                '<span style="color:var(--border)">—</span>'}
                        </td>
                        <td style="max-width:200px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis; color:var(--muted); font-size:13px"
                                 title="${item.address}">
                                ${item.address || '—'}
                            </div>
                        </td>
                        <td>${headDisplay}</td>
                        <td>
                            <span class="member-count">
                                <i class="fas fa-users"></i>
                                ${item.members}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-household" data-id="${item.id}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit action-btn-edit edit-household" data-id="${item.id}" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn action-btn-delete delete-household" data-id="${item.id}" title="Delete">
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
       ADD HOUSEHOLD
    ══════════════════════════════════════════════════════ */
    $('#addHouseholdForm').on('submit', function(e){
        e.preventDefault();
        const $btn = $(this).find('.btn-save').prop('disabled', true)
                         .html('<i class="fas fa-spinner fa-spin me-2"></i>Saving…');

        $.ajax({
            url:  '{{ route("households.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    Swal.fire({
                        icon:'success', title:'Household Added!',
                        text: res.message,
                        timer:2500, showConfirmButton:false,
                        toast:true, position:'top-end'
                    });
                    $('#addHouseholdModal').modal('hide');
                    $('#addHouseholdForm')[0].reset();
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
                    .html('<i class="fas fa-floppy-disk"></i> Save Household');
            }
        });
    });

    $('#addHouseholdModal').on('hidden.bs.modal', function(){
        $('#addHouseholdForm')[0].reset();
    });

    /* ══════════════════════════════════════════════════════
       VIEW HOUSEHOLD
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.view-household', function(){
        const id = $(this).data('id');

        $('#viewHouseholdSubtitle').text('Loading…');
        $('#viewHouseholdBody').html(`
            <div style="text-align:center;padding:40px 0;color:var(--muted)">
                <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                <div style="margin-top:10px;font-size:13px">Loading details…</div>
            </div>`);
        $('#viewHouseholdModal').modal('show');

        $.ajax({
            url:  '{{ url("households") }}/' + id,
            type: 'GET',
            success: function(res){
                if(!res.success) return;
                const h = res.data;

                /* Head resident HTML */
                const head = h.head_resident
                    ? `<div style="display:flex;align-items:center;gap:9px">
                           <div class="resident-mini-avatar" style="background:${['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][h.head_resident.full_name.length % 5]}">
                               ${h.head_resident.full_name.charAt(0).toUpperCase()}
                           </div>
                           <span style="font-weight:500">${h.head_resident.full_name}</span>
                       </div>`
                    : `<span class="badge-unassigned">
                           <i class="fas fa-circle-exclamation"></i> Unassigned
                       </span>`;

                /* Members list HTML */
                const membersHtml = (h.residents && h.residents.length)
                    ? h.residents.map(r => `
                        <div class="member-item">
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="resident-mini-avatar" style="background:${['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][r.full_name.length % 5]}">
                                    ${r.full_name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <div style="font-weight:500;font-size:13.5px">${r.full_name}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">${r.resident_code || ''}</div>
                                </div>
                            </div>
                            <span class="status-badge status-${r.status || 'inactive'}">
                                <span class="status-dot"></span>
                                ${r.status ? r.status.charAt(0).toUpperCase() + r.status.slice(1) : 'Inactive'}
                            </span>
                        </div>`).join('')
                    : `<div style="text-align:center;padding:20px 0;color:var(--muted);font-size:13px">
                           <i class="fas fa-users" style="font-size:22px;opacity:.25;display:block;margin-bottom:8px"></i>
                           No members registered yet.
                       </div>`;

                $('#viewHouseholdSubtitle').text(h.household_number);
                $('#viewHouseholdBody').html(`
                    <div class="v-row"><span class="v-lbl">Household #</span>
                        <span class="v-val" style="font-weight:700;color:var(--primary)">${h.household_number}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Purok</span>
                        <span class="v-val">${h.purok || '—'}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Address</span>
                        <span class="v-val">${h.address || '—'}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Household Head</span>
                        <span class="v-val">${head}</span>
                    </div>
                    <div class="v-row"><span class="v-lbl">Total Members</span>
                        <span class="v-val">${h.residents ? h.residents.length : 0}</span>
                    </div>
                    <div style="margin-top:20px">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;margin-bottom:10px">
                            <i class="fas fa-users me-2" style="color:var(--primary)"></i>Members
                        </div>
                        ${membersHtml}
                    </div>`);
            },
            error: function(){
                $('#viewHouseholdBody').html(`
                    <div style="text-align:center;padding:30px;color:var(--danger)">
                        <i class="fas fa-circle-exclamation" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        Failed to load household details.
                    </div>`);
            }
        });
    });

    /* ══════════════════════════════════════════════════════
       EDIT HOUSEHOLD
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.edit-household', function(){
        const id = $(this).data('id');

        $.ajax({
            url:  '{{ url("households") }}/' + id + '/edit',
            type: 'GET',
            success: function(res){
                if(!res.success) return;
                const h = res.data;
                $('#editHouseholdId').val(h.id);
                $('#editHouseholdNumber').val(h.household_number);
                $('#editPurok').val(h.purok ?? '');
                $('#editAddress').val(h.address ?? '');
                $('#editHeadResidentId').val(h.head_resident_id ?? '');
                $('#editHouseholdModal').modal('show');
            },
            error: function(){
                Swal.fire('Error', 'Could not load household data.', 'error');
            }
        });
    });

    $('#editHouseholdForm').on('submit', function(e){
        e.preventDefault();
        const id   = $('#editHouseholdId').val();
        const $btn = $(this).find('.btn-save').prop('disabled', true)
                         .html('<i class="fas fa-spinner fa-spin me-2"></i>Updating…');

        $.ajax({
            url:  '{{ url("households") }}/' + id,
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
                    $('#editHouseholdModal').modal('hide');
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
                    .html('<i class="fas fa-floppy-disk"></i> Update Household');
            }
        });
    });

    /* ══════════════════════════════════════════════════════
       DELETE HOUSEHOLD
    ══════════════════════════════════════════════════════ */
    $(document).on('click', '.delete-household', function(){
        const id  = $(this).data('id');
        const url = '{{ url("households") }}/' + id;
        confirmDelete(url, 'This household and all member associations will be permanently removed.');
    });

});
</script>
@endpush