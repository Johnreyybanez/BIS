@extends('layouts.app')

@section('title', 'Certificate Types')

@section('content')

{{-- ── Page header ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);margin:0;line-height:1.2">
            Certificate Types
        </h2>
        <p style="font-size:13px;color:var(--muted);margin:3px 0 0">
            Manage certificate types and their corresponding fees
        </p>
    </div>
    <button type="button" class="btn-add-type"
            data-bs-toggle="modal" data-bs-target="#addTypeModal">
        <i class="fas fa-plus"></i> New Certificate Type
    </button>
</div>

{{-- ── Stats strip ── --}}
<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fd;color:#4f63d2"><i class="fas fa-file-signature"></i></div>
        <div>
            <div class="stat-val">{{ $totalTypes ?? 0 }}</div>
            <div class="stat-lbl">Certificate Types</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e6faf3;color:#1cc88a"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-val">₱{{ number_format($totalFee ?? 0, 2) }}</div>
            <div class="stat-lbl">Total Potential Fee</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-calculator"></i></div>
        <div>
            <div class="stat-val">₱{{ number_format($avgFee ?? 0, 2) }}</div>
            <div class="stat-lbl">Average Fee</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e3f2fd;color:#0d6efd"><i class="fas fa-star"></i></div>
        <div>
            <div class="stat-val" style="font-size:14px;font-weight:700">{{ $mostUsed->certificate_name ?? 'N/A' }}</div>
            <div class="stat-lbl">Most Requested</div>
        </div>
    </div>
</div>

{{-- ── Table card ── --}}
<div class="card">
    <div class="card-body" style="padding:0">

        {{-- Table controls --}}
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:6px">
                <span style="font-size:13px;color:var(--muted)">Show</span>
                <select id="showEntries" class="type-filter" style="width:70px">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span style="font-size:13px;color:var(--muted)">entries</span>
            </div>
            <div class="type-search-wrap">
                <i class="fas fa-magnifying-glass type-search-ico"></i>
                <input type="text" id="typeSearch" class="type-search" placeholder="Search certificate types…">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="typesTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="name">Certificate Type <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="description">Description <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="fee">Fee <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="requests">Total Requests <i class="fas fa-sort sort-icon"></i></th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificateTypes ?? [] as $type)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="type-avatar" style="background:{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($type->certificate_name) % 5] }}">
                                    {{ strtoupper(substr($type->certificate_name, 0, 1)) }}
                                </div>
                                <span class="type-name">{{ $type->certificate_name }}</span>
                            </div>
                        </td>
                        <td style="max-width:300px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px"
                                 title="{{ $type->description }}">
                                {{ $type->description ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <span class="fee-badge">₱{{ number_format($type->fee, 2) }}</span>
                        </td>
                        <td>
                            <span class="request-count">
                                <i class="fas fa-file-lines"></i>
                                {{ $type->certificate_requests_count ?? 0 }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-type" data-id="{{ $type->id }}" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn action-btn-edit edit-type" data-id="{{ $type->id }}" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                <button class="action-btn action-btn-delete delete-type" data-id="{{ $type->id }}" title="Delete">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-file-circle-exclamation" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                            No certificate types found. Click "New Certificate Type" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($certificateTypes) && $certificateTypes instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $certificateTypes->firstItem() ?? 0 }} to {{ $certificateTypes->lastItem() ?? 0 }} of {{ $certificateTypes->total() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;">
                {{ $certificateTypes->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ════ ADD MODAL ════ --}}
<div class="modal fade" id="addTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content type-modal">
            <div class="type-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-file-circle-plus"></i></div>
                    <div>
                        <h5 class="type-modal-title">New Certificate Type</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Add a new certificate type</p>
                    </div>
                </div>
                <button type="button" class="type-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form id="addTypeForm">
                @csrf
                <div class="type-modal-body">
                    <div class="form-section-label">Certificate Type Information</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="type-field">
                                <label class="type-label">Certificate Name <span class="req">*</span></label>
                                <input type="text" class="type-input" name="certificate_name"
                                       placeholder="e.g. Barangay Clearance, Certificate of Indigency" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="type-field">
                                <label class="type-label">Description</label>
                                <textarea class="type-input" name="description" rows="3"
                                          placeholder="Describe what this certificate is used for..."
                                          style="resize:none;min-height:80px"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="type-field">
                                <label class="type-label">Fee Amount <span class="req">*</span></label>
                                <div class="fee-input-group">
                                    <span class="fee-prefix">₱</span>
                                    <input type="number" class="type-input fee-input" name="fee"
                                           step="0.01" min="0" value="0.00" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="type-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Save Certificate Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════ EDIT MODAL ════ --}}
<div class="modal fade" id="editTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content type-modal">
            <div class="type-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-file-pen"></i></div>
                    <div>
                        <h5 class="type-modal-title">Edit Certificate Type</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Update certificate type details</p>
                    </div>
                </div>
                <button type="button" class="type-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form id="editTypeForm">
                @csrf
                {{-- _method PUT sent via hidden field in JS --}}
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editTypeId">
                <div class="type-modal-body">
                    <div class="form-section-label">Certificate Type Information</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="type-field">
                                <label class="type-label">Certificate Name <span class="req">*</span></label>
                                <input type="text" class="type-input" id="editCertificateName"
                                       name="certificate_name" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="type-field">
                                <label class="type-label">Description</label>
                                <textarea class="type-input" id="editDescription" name="description"
                                          rows="3" style="resize:none;min-height:80px"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="type-field">
                                <label class="type-label">Fee Amount <span class="req">*</span></label>
                                <div class="fee-input-group">
                                    <span class="fee-prefix">₱</span>
                                    <input type="number" class="type-input fee-input" id="editFee"
                                           name="fee" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="type-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Update Certificate Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════ VIEW MODAL ════ --}}
<div class="modal fade" id="viewTypeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content type-modal">
            <div class="type-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-file-lines"></i></div>
                    <div>
                        <h5 class="type-modal-title">Certificate Type Details</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0" id="viewTypeSubtitle">Loading…</p>
                    </div>
                </div>
                <button type="button" class="type-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="type-modal-body" id="viewTypeBody">
                <div style="text-align:center;padding:40px 0;color:var(--muted)">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                    <div style="margin-top:10px;font-size:13px">Loading details…</div>
                </div>
            </div>
            <div class="type-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ════ STYLES ════ --}}
<style>
.stat-strip{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:22px}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 10px rgba(15,22,35,.05);transition:box-shadow var(--dur) var(--ease),transform var(--dur) var(--ease)}
.stat-card:hover{box-shadow:0 6px 22px rgba(15,22,35,.1);transform:translateY(-2px)}
.stat-icon{width:42px;height:42px;border-radius:11px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px}
.stat-val{font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);line-height:1}
.stat-lbl{font-size:11.5px;color:var(--muted);margin-top:3px;font-weight:500}

.btn-add-type{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border-radius:10px;border:none;cursor:pointer;background:var(--primary);color:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;transition:all var(--dur) var(--ease);box-shadow:0 4px 14px rgba(79,99,210,.35)}
.btn-add-type:hover{background:#3d4fc0;box-shadow:0 6px 20px rgba(79,99,210,.45);transform:translateY(-1px)}

.type-search-wrap{position:relative;display:flex;align-items:center}
.type-search-ico{position:absolute;left:12px;color:var(--muted);font-size:13px;pointer-events:none}
.type-search{padding:8px 14px 8px 36px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);width:260px;outline:none;transition:border-color var(--dur),box-shadow var(--dur)}
.type-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.type-filter{padding:8px 14px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);outline:none;cursor:pointer}

#typesTable thead th{font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);background:#f8f9fd;padding:13px 16px;border-bottom:1px solid var(--border)}
#typesTable thead th.sortable{cursor:pointer;user-select:none}
#typesTable thead th.sortable:hover{background:#eef0fd;color:var(--primary)}
.sort-icon{margin-left:5px;font-size:10px;opacity:0.3;transition:opacity var(--dur)}
#typesTable tbody td{padding:14px 16px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle}
#typesTable tbody tr:last-child td{border-bottom:none}
#typesTable tbody tr{transition:background var(--dur)}
#typesTable tbody tr:hover{background:#f8f9fd}
th.sorting-asc .sort-icon,th.sorting-desc .sort-icon{opacity:1;color:var(--primary)}

.type-avatar{width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:14px}
.type-name{font-weight:600;font-size:13.5px;color:var(--text)}
.fee-badge{display:inline-flex;align-items:center;background:var(--plt);color:var(--primary);padding:4px 11px;border-radius:100px;font-size:12px;font-weight:600}
.request-count{display:inline-flex;align-items:center;gap:6px;background:#f0f2f8;color:var(--muted);padding:4px 11px;border-radius:100px;font-size:12px;font-weight:600}
.request-count i{font-size:10px;color:var(--primary)}

.action-group{display:flex;align-items:center;justify-content:flex-end;gap:6px}
.action-btn{width:32px;height:32px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--muted);display:flex;align-items:center;justify-content:center;font-size:13px;cursor:pointer;transition:all var(--dur) var(--ease)}
.action-btn:hover{color:#4f63d2;border-color:#4f63d2;background:var(--plt)}
.action-btn-edit:hover{color:#f4a20a;border-color:#f4a20a;background:#fff8e6}
.action-btn-delete:hover{color:var(--danger);border-color:var(--danger);background:#fff0f3}

.empty-state{text-align:center;padding:48px 16px;color:var(--muted);font-size:14px}

.type-modal{border:none;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(15,22,35,.22)}
.type-modal-header{display:flex;align-items:center;justify-content:space-between;padding:22px 28px;border-bottom:1px solid var(--border);background:#fff}
.modal-icon-wrap{width:42px;height:42px;border-radius:12px;background:var(--plt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:17px}
.type-modal-title{font-family:'Syne',sans-serif;font-weight:800;font-size:17px;color:var(--text);margin:0}
.type-modal-close{width:34px;height:34px;border-radius:9px;border:1px solid var(--border);background:none;color:var(--muted);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:all var(--dur)}
.type-modal-close:hover{background:var(--bg);color:var(--text)}
.type-modal-body{padding:24px 28px}
.type-modal-footer{padding:18px 28px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:flex-end;gap:10px;background:#fafbff}

.form-section-label{font-size:10.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;display:flex;align-items:center;gap:8px}
.form-section-label::after{content:'';flex:1;height:1px;background:var(--border)}

.type-field{display:flex;flex-direction:column;gap:6px}
.type-label{font-size:12.5px;font-weight:600;color:var(--text)}
.req{color:var(--danger)}
.type-input{padding:9px 13px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text);outline:none;width:100%;transition:border-color var(--dur),box-shadow var(--dur),background var(--dur)}
.type-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.fee-input-group{display:flex;align-items:center;position:relative}
.fee-prefix{position:absolute;left:13px;font-size:13.5px;color:var(--muted);font-weight:500;z-index:1}
.fee-input{padding-left:28px}

.btn-cancel{padding:9px 20px;border-radius:9px;border:1px solid var(--border);background:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:var(--muted);cursor:pointer;transition:all var(--dur)}
.btn-cancel:hover{border-color:var(--text);color:var(--text)}
.btn-save{display:inline-flex;align-items:center;gap:8px;padding:9px 22px;border-radius:9px;border:none;background:var(--primary);font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:#fff;cursor:pointer;box-shadow:0 4px 14px rgba(79,99,210,.35);transition:all var(--dur)}
.btn-save:hover{background:#3d4fc0;box-shadow:0 6px 20px rgba(79,99,210,.45);transform:translateY(-1px)}

.pagination .page-item .page-link{border-radius:8px!important;font-size:13px;font-weight:500;border:1px solid var(--border);color:var(--muted);margin:0 2px;transition:all var(--dur)}
.pagination .page-item.active .page-link{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff}
.pagination .page-item .page-link:hover{border-color:var(--primary);color:var(--primary);background:var(--plt)}
#paginationLinks nav{display:flex;justify-content:flex-end}

.v-row{display:flex;gap:12px;padding:11px 0;border-bottom:1px solid var(--border)}
.v-row:last-child{border-bottom:none}
.v-lbl{font-size:11.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--muted);min-width:130px;flex-shrink:0;padding-top:2px}
.v-val{font-size:13.5px;color:var(--text);font-weight:500}

.stats-mini-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin:16px 0}
.stats-mini-card{background:#f8f9fd;border-radius:10px;padding:12px;text-align:center}
.stats-mini-label{font-size:10px;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);margin-bottom:4px}
.stats-mini-value{font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--text)}
.stats-mini-value small{font-size:11px;font-weight:400;color:var(--muted)}

.recent-request-item{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px dashed var(--border)}
.recent-request-item:last-child{border-bottom:none}
.recent-request-name{font-size:13px;font-weight:500;color:var(--text)}
.recent-request-date{font-size:11px;color:var(--muted)}
.recent-request-status{font-size:11px;font-weight:600;padding:2px 8px;border-radius:100px}
.status-pending{background:#fff8e6;color:#f4a20a}
.status-approved{background:#e6faf3;color:#1cc88a}
.status-rejected{background:#fff0f3;color:#ff4d6d}
.status-released{background:#e3f2fd;color:#0d6efd}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    /* ─────────────────────────────────────────
       DATA STORE
    ───────────────────────────────────────── */
    let typesData    = [];
    let currentSort  = { column: 'name', direction: 'asc' };
    let currentPage  = 1;
    let perPage      = 10;
    let filteredData = [];

    @if(isset($certificateTypes) && $certificateTypes->count() > 0)
        @foreach($certificateTypes as $type)
        typesData.push({
            id:             {{ $type->id }},
            name:           '{{ addslashes($type->certificate_name) }}',
            description:    '{{ addslashes($type->description ?? '') }}',
            fee:            {{ $type->fee }},
            requests:       {{ $type->certificate_requests_count ?? 0 }},
            avatar_color:   '{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($type->certificate_name) % 5] }}',
            avatar_initial: '{{ strtoupper(substr($type->certificate_name, 0, 1)) }}'
        });
        @endforeach
    @endif

    filteredData = [...typesData];
    renderTable();

    /* ─────────────────────────────────────────
       CONTROLS
    ───────────────────────────────────────── */
    $('#showEntries').on('change', function () {
        perPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
    });

    $('#typeSearch').on('input', function () {
        filterData($(this).val().toLowerCase());
    });

    $(document).on('click', '.sortable', function () {
        let col = $(this).data('sort');
        if (currentSort.column === col) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column    = col;
            currentSort.direction = 'asc';
        }
        $('.sortable').removeClass('sorting-asc sorting-desc');
        $(this).addClass('sorting-' + currentSort.direction);
        sortData();
        renderTable();
    });

    /* ─────────────────────────────────────────
       FILTER / SORT
    ───────────────────────────────────────── */
    function filterData(searchTerm) {
        filteredData = typesData.filter(function (item) {
            return !searchTerm ||
                (item.name        && item.name.toLowerCase().includes(searchTerm)) ||
                (item.description && item.description.toLowerCase().includes(searchTerm));
        });
        sortData();
        currentPage = 1;
        renderTable();
    }

    function sortData() {
        filteredData.sort(function (a, b) {
            let valA = a[currentSort.column];
            let valB = b[currentSort.column];
            if (currentSort.column === 'fee' || currentSort.column === 'requests') {
                valA = parseFloat(valA) || 0;
                valB = parseFloat(valB) || 0;
            } else {
                valA = String(valA || '').toLowerCase();
                valB = String(valB || '').toLowerCase();
            }
            if (valA < valB) return currentSort.direction === 'asc' ? -1 : 1;
            if (valA > valB) return currentSort.direction === 'asc' ?  1 : -1;
            return 0;
        });
    }

    /* ─────────────────────────────────────────
       RENDER TABLE
    ───────────────────────────────────────── */
    function renderTable() {
        let start    = (currentPage - 1) * perPage;
        let end      = start + perPage;
        let pageData = filteredData.slice(start, end);
        let tbody    = $('#typesTable tbody');
        tbody.empty();

        if (pageData.length === 0) {
            tbody.html(`<tr><td colspan="5" class="empty-state">
                <i class="fas fa-file-circle-exclamation" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                No certificate types found.</td></tr>`);
        } else {
            pageData.forEach(function (item) {
                tbody.append(`
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="type-avatar" style="background:${item.avatar_color}">${item.avatar_initial}</div>
                                <span class="type-name">${item.name}</span>
                            </div>
                        </td>
                        <td style="max-width:300px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px"
                                 title="${item.description || ''}">
                                ${item.description || '—'}
                            </div>
                        </td>
                        <td><span class="fee-badge">₱${parseFloat(item.fee).toFixed(2)}</span></td>
                        <td><span class="request-count"><i class="fas fa-file-lines"></i> ${item.requests}</span></td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-type" data-id="${item.id}" title="View Details"><i class="fas fa-eye"></i></button>
                                <button class="action-btn action-btn-edit edit-type" data-id="${item.id}" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                                <button class="action-btn action-btn-delete delete-type" data-id="${item.id}" title="Delete"><i class="fas fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>`);
            });
        }

        updatePagination();
        let total = filteredData.length;
        let first = total ? start + 1 : 0;
        let last  = Math.min(end, total);
        $('#tableInfo').text(`Showing ${first} to ${last} of ${total} entries`);
    }

    function updatePagination() {
        let total      = filteredData.length;
        let totalPages = Math.ceil(total / perPage);
        let pag        = $('#paginationLinks');

        if (totalPages <= 1) { pag.empty(); return; }

        let html = '<nav><ul class="pagination">';
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>`;

        let startPage = Math.max(1, currentPage - 2);
        let endPage   = Math.min(totalPages, startPage + 4);

        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>`;
        html += '</ul></nav>';
        pag.html(html);

        pag.find('.page-link').on('click', function (e) {
            e.preventDefault();
            let page = parseInt($(this).data('page'));
            if (page && page !== currentPage && page >= 1 && page <= totalPages) {
                currentPage = page;
                renderTable();
            }
        });
    }

    /* ═══════════════════════════════════════════════════
       ADD — POST /certificate-types
    ═══════════════════════════════════════════════════ */
    $('#addTypeForm').on('submit', function (e) {
        e.preventDefault();
        let $btn = $(this).find('.btn-save')
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>Saving…');

        $.ajax({
            url:  '{{ route("certificate-types.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#addTypeModal').modal('hide');
                    $('#addTypeForm')[0].reset();
                    // ── Wait for toast before reload ──
                    Swal.fire({
                        icon: 'success', title: 'Certificate Type Added!',
                        text: res.message, timer: 2000,
                        showConfirmButton: false, toast: true, position: 'top-end'
                    }).then(function () { location.reload(); });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let msg    = errors ? Object.values(errors).flat().join('\n') : (xhr.responseJSON?.message || 'Something went wrong.');
                Swal.fire('Validation Error', msg, 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-floppy-disk"></i> Save Certificate Type');
            }
        });
    });

    $('#addTypeModal').on('hidden.bs.modal', function () { $('#addTypeForm')[0].reset(); });

    /* ═══════════════════════════════════════════════════
       VIEW — GET /certificate-types/{id}
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '.view-type', function () {
        let id = $(this).data('id');
        $('#viewTypeSubtitle').text('Loading…');
        $('#viewTypeBody').html(`<div style="text-align:center;padding:40px 0;color:var(--muted)">
            <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
            <div style="margin-top:10px;font-size:13px">Loading details…</div></div>`);
        $('#viewTypeModal').modal('show');

        $.ajax({
            url:  '{{ url("certificate-types") }}/' + id,
            type: 'GET',
            success: function (res) {
                if (!res.success) return;
                let t     = res.data;
                let stats = res.stats;

                let recentHtml = '';
                if (t.certificate_requests && t.certificate_requests.length > 0) {
                    t.certificate_requests.forEach(function (req) {
                        recentHtml += `
                            <div class="recent-request-item">
                                <div>
                                    <div class="recent-request-name">${req.resident?.full_name || 'Unknown'}</div>
                                    <div class="recent-request-date">${new Date(req.requested_at).toLocaleDateString()}</div>
                                </div>
                                <span class="recent-request-status status-${req.status}">${req.status}</span>
                            </div>`;
                    });
                } else {
                    recentHtml = '<div style="text-align:center;padding:20px;color:var(--muted)">No recent requests</div>';
                }

                $('#viewTypeSubtitle').text(t.certificate_name);
                $('#viewTypeBody').html(`
                    <div class="v-row"><span class="v-lbl">Certificate Name</span>
                        <span class="v-val" style="font-weight:700;color:var(--primary)">${t.certificate_name}</span></div>
                    <div class="v-row"><span class="v-lbl">Description</span>
                        <span class="v-val">${t.description || '—'}</span></div>
                    <div class="v-row"><span class="v-lbl">Fee</span>
                        <span class="v-val"><span class="fee-badge">₱${parseFloat(t.fee).toFixed(2)}</span></span></div>

                    <div class="stats-mini-grid">
                        <div class="stats-mini-card">
                            <div class="stats-mini-label">Total Requests</div>
                            <div class="stats-mini-value">${stats.total_requests}</div>
                        </div>
                        <div class="stats-mini-card">
                            <div class="stats-mini-label">Pending</div>
                            <div class="stats-mini-value">${stats.pending}</div>
                        </div>
                        <div class="stats-mini-card">
                            <div class="stats-mini-label">Approved</div>
                            <div class="stats-mini-value">${stats.approved}</div>
                        </div>
                        <div class="stats-mini-card">
                            <div class="stats-mini-label">Released</div>
                            <div class="stats-mini-value">${stats.released}</div>
                        </div>
                        <div class="stats-mini-card" style="grid-column:span 2">
                            <div class="stats-mini-label">Total Revenue</div>
                            <div class="stats-mini-value">₱${parseFloat(stats.total_revenue).toFixed(2)} <small>potential</small></div>
                        </div>
                    </div>

                    <div style="margin-top:16px">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;margin-bottom:10px">
                            <i class="fas fa-history me-2" style="color:var(--primary)"></i>Recent Requests
                        </div>
                        ${recentHtml}
                    </div>`);
            },
            error: function () {
                $('#viewTypeBody').html(`<div style="text-align:center;padding:30px;color:var(--danger)">
                    <i class="fas fa-circle-exclamation" style="font-size:24px;display:block;margin-bottom:8px"></i>
                    Failed to load certificate type details.</div>`);
            }
        });
    });

    /* ═══════════════════════════════════════════════════
       EDIT — GET /certificate-types/{id}/edit  →  populate modal
              POST /certificate-types/{id}  with _method=PUT
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '.edit-type', function () {
        let id = $(this).data('id');

        $.ajax({
            url:  '{{ url("certificate-types") }}/' + id + '/edit',
            type: 'GET',
            success: function (res) {
                if (!res.success) return;
                let t = res.data;
                $('#editTypeId').val(t.id);
                $('#editCertificateName').val(t.certificate_name);
                $('#editDescription').val(t.description || '');
                $('#editFee').val(t.fee);
                $('#editTypeModal').modal('show');
            },
            error: function () {
                Swal.fire('Error', 'Could not load certificate type data.', 'error');
            }
        });
    });

    $('#editTypeForm').on('submit', function (e) {
        e.preventDefault();
        let id   = $('#editTypeId').val();
        let $btn = $(this).find('.btn-save')
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>Updating…');

        $.ajax({
            url:  '{{ url("certificate-types") }}/' + id,
            type: 'POST',   // ← POST + _method=PUT (spoofing)
            data: $(this).serialize(),   // includes _method=PUT from hidden field
            success: function (res) {
                if (res.success) {
                    $('#editTypeModal').modal('hide');
                    // ── Wait for toast before reload ──
                    Swal.fire({
                        icon: 'success', title: 'Updated!',
                        text: res.message, timer: 2000,
                        showConfirmButton: false, toast: true, position: 'top-end'
                    }).then(function () { location.reload(); });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let msg    = errors ? Object.values(errors).flat().join('\n') : (xhr.responseJSON?.message || 'Something went wrong.');
                Swal.fire('Validation Error', msg, 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-floppy-disk"></i> Update Certificate Type');
            }
        });
    });

    /* ═══════════════════════════════════════════════════
       DELETE — POST /certificate-types/{id} with _method=DELETE
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '.delete-type', function () {
        let id  = $(this).data('id');
        let url = '{{ url("certificate-types") }}/' + id;

        Swal.fire({
            title: 'Are you sure?',
            text:  'This certificate type will be permanently removed. Make sure no certificate requests are using this type.',
            icon:  'warning',
            showCancelButton:   true,
            confirmButtonColor: '#ff4d6d',
            cancelButtonColor:  '#b0b7cc',
            confirmButtonText:  'Yes, delete it!',
            cancelButtonText:   'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url:  url,
                    type: 'POST',   // ← POST + _method=DELETE (spoofing)
                    data: {
                        _token:  '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (res) {
                        if (res.success) {
                            // ── Wait for toast before reload ──
                            Swal.fire({
                                icon: 'success', title: 'Deleted!',
                                text: res.message, timer: 2000,
                                showConfirmButton: false, toast: true, position: 'top-end'
                            }).then(function () { location.reload(); });
                        } else {
                            Swal.fire('Cannot Delete', res.message, 'warning');
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON?.message || 'Something went wrong.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });

});
</script>
@endpush