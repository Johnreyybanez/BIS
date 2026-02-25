@extends('layouts.app')

@section('title', 'Certificate Requests')

@section('content')

{{-- ── Page header ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);margin:0;line-height:1.2">
            Certificate Requests
        </h2>
        <p style="font-size:13px;color:var(--muted);margin:3px 0 0">
            Manage and process barangay certificate requests
        </p>
    </div>
    <button type="button"
            class="btn-add-certificate"
            data-bs-toggle="modal"
            data-bs-target="#addCertificateModal">
        <i class="fas fa-plus"></i>
        New Request
    </button>
</div>

{{-- ── Stats strip ── --}}
<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fd;color:#4f63d2"><i class="fas fa-file-lines"></i></div>
        <div>
            <div class="stat-val">{{ $totalRequests ?? 0 }}</div>
            <div class="stat-lbl">Total Requests</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val">{{ $pendingCount ?? 0 }}</div>
            <div class="stat-lbl">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e6faf3;color:#1cc88a"><i class="fas fa-circle-check"></i></div>
        <div>
            <div class="stat-val">{{ $approvedCount ?? 0 }}</div>
            <div class="stat-lbl">Approved</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e3f2fd;color:#0d6efd"><i class="fas fa-hand-holding-heart"></i></div>
        <div>
            <div class="stat-val">{{ $releasedCount ?? 0 }}</div>
            <div class="stat-lbl">Released</div>
        </div>
    </div>
</div>

{{-- ── Table card ── --}}
<div class="card">
    <div class="card-body" style="padding:0">

        {{-- Table controls --}}
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="font-size:13px;color:var(--muted)">Show</span>
                    <select id="showEntries" class="cert-filter" style="width:70px">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span style="font-size:13px;color:var(--muted)">entries</span>
                </div>
                <select id="filterStatus" class="cert-filter">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="released">Released</option>
                </select>
                <select id="filterType" class="cert-filter">
                    <option value="">All Certificate Types</option>
                    @if(isset($certificateTypes) && $certificateTypes->count() > 0)
                        @foreach($certificateTypes as $type)
                            <option value="{{ $type->certificate_name }}">{{ $type->certificate_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="cert-search-wrap">
                <i class="fas fa-magnifying-glass cert-search-ico"></i>
                <input type="text" id="certificateSearch" class="cert-search" placeholder="Search requests…">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="certificatesTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="control">Control # <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="resident">Resident <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="certificate">Certificate <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="purpose">Purpose <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="requested">Requested <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="status">Status <i class="fas fa-sort sort-icon"></i></th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificateRequests ?? [] as $request)
                    <tr>
                        <td>
                            <span class="cert-control">{{ $request->control_number }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="cert-avatar" style="background:{{ $request->resident ? ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($request->resident->full_name ?? 'R') % 5] : '#b0b7cc' }}">
                                    {{ $request->resident ? strtoupper(substr($request->resident->full_name ?? 'R', 0, 1)) : '?' }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">{{ $request->resident->full_name ?? 'Unknown' }}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">{{ $request->resident->resident_code ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="cert-type-pill">{{ $request->certificateType->certificate_name ?? '' }}</span>
                        </td>
                        <td style="max-width:200px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px"
                                 title="{{ $request->purpose }}">
                                {{ $request->purpose ?? '—' }}
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--muted)">
                            {{ $request->requested_at ? \Carbon\Carbon::parse($request->requested_at)->format('M d, Y') : '—' }}
                        </td>
                        <td>
                            <span class="status-badge status-{{ $request->status ?? 'pending' }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($request->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-certificate"
                                        data-id="{{ $request->id }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($request->status === 'pending')
                                    <button class="action-btn action-btn-edit edit-certificate"
                                            data-id="{{ $request->id }}" title="Edit">
                                        <i class="fas fa-pen-to-square"></i>
                                    </button>
                                    <button class="action-btn action-btn-approve approve-certificate"
                                            data-id="{{ $request->id }}" title="Approve">
                                        <i class="fas fa-circle-check" style="color:#1cc88a"></i>
                                    </button>
                                    <button class="action-btn action-btn-reject reject-certificate"
                                            data-id="{{ $request->id }}" title="Reject">
                                        <i class="fas fa-circle-xmark" style="color:#ff4d6d"></i>
                                    </button>
                                @elseif($request->status === 'approved')
                                    <button class="action-btn action-btn-release release-certificate"
                                            data-id="{{ $request->id }}" title="Mark as Released">
                                        <i class="fas fa-hand-holding-heart" style="color:#0d6efd"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete delete-certificate"
                                            data-id="{{ $request->id }}" title="Delete">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                @else
                                    <button class="action-btn action-btn-delete delete-certificate"
                                            data-id="{{ $request->id }}" title="Delete">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-file-circle-exclamation" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                            No certificate requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($certificateRequests) && $certificateRequests instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $certificateRequests->firstItem() ?? 0 }} to {{ $certificateRequests->lastItem() ?? 0 }} of {{ $certificateRequests->total() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;">
                {{ $certificateRequests->links() }}
            </div>
        </div>
        @elseif(isset($certificateRequests) && $certificateRequests->count() > 0)
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $certificateRequests->count() }} entries
            </div>
            <div id="paginationLinks"></div>
        </div>
        @endif

    </div>
</div>

{{-- ════ ADD CERTIFICATE MODAL ════ --}}
<div class="modal fade" id="addCertificateModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content cert-modal">
            <div class="cert-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-file-circle-plus"></i></div>
                    <div>
                        <h5 class="cert-modal-title">New Certificate Request</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Fill in the certificate request details</p>
                    </div>
                </div>
                <button type="button" class="cert-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form id="addCertificateForm">
                @csrf
                <div class="cert-modal-body">
                    <div class="form-section-label">Request Information</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cert-field">
                                <label class="cert-label">Resident <span class="req">*</span></label>
                                <select class="cert-input" name="resident_id" required>
                                    <option value="">— Select Resident —</option>
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
                            <div class="cert-field">
                                <label class="cert-label">Certificate Type <span class="req">*</span></label>
                                <select class="cert-input" name="certificate_type_id" id="certTypeSelect" required>
                                    <option value="">— Select Certificate —</option>
                                    @if(isset($certificateTypes) && $certificateTypes->count() > 0)
                                        @foreach($certificateTypes as $type)
                                        <option value="{{ $type->id }}" data-fee="{{ $type->fee }}">
                                            {{ $type->certificate_name }} (₱{{ number_format($type->fee, 2) }})
                                        </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cert-field">
                                <label class="cert-label">Purpose</label>
                                <textarea class="cert-input" name="purpose" rows="3"
                                          placeholder="State the purpose of requesting this certificate..."
                                          style="resize:none;min-height:80px"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cert-field">
                                <label class="cert-label">Fee Amount</label>
                                <div class="fee-display">
                                    <span class="fee-amount">₱0.00</span>
                                    <span class="fee-note">(Auto-calculated based on certificate type)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cert-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-floppy-disk"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════ VIEW CERTIFICATE MODAL ════ --}}
<div class="modal fade" id="viewCertificateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cert-modal">
            <div class="cert-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-file-lines"></i></div>
                    <div>
                        <h5 class="cert-modal-title">Certificate Details</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0" id="viewCertificateSubtitle">Loading…</p>
                    </div>
                </div>
                <button type="button" class="cert-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="cert-modal-body" id="viewCertificateBody">
                <div style="text-align:center;padding:40px 0;color:var(--muted)">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                    <div style="margin-top:10px;font-size:13px">Loading details…</div>
                </div>
            </div>
            <div class="cert-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn-print" id="printCertificateBtn" style="display:none">
                    <i class="fas fa-print"></i> Print Certificate
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ════ ACTION NOTES MODAL (Approve / Reject / Release) ════ --}}
<div class="modal fade" id="actionNotesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cert-modal">
            <div class="cert-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap" id="actionIcon"><i class="fas fa-check"></i></div>
                    <div>
                        <h5 class="cert-modal-title" id="actionTitle">Action</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Add optional notes</p>
                    </div>
                </div>
                <button type="button" class="cert-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <form id="actionNotesForm">
                @csrf
                <input type="hidden" id="actionRequestId">
                <input type="hidden" id="actionType">
                <div class="cert-modal-body">
                    <div class="cert-field">
                        <label class="cert-label">Notes (Optional)</label>
                        <textarea class="cert-input" id="actionNotes" rows="4"
                                  placeholder="Add any notes or remarks..."></textarea>
                    </div>
                </div>
                <div class="cert-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save" id="actionSubmitBtn">
                        <i class="fas fa-check"></i> Confirm
                    </button>
                </div>
            </form>
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

.btn-add-certificate{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border-radius:10px;border:none;cursor:pointer;background:var(--primary);color:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;transition:all var(--dur) var(--ease);box-shadow:0 4px 14px rgba(79,99,210,.35)}
.btn-add-certificate:hover{background:#3d4fc0;box-shadow:0 6px 20px rgba(79,99,210,.45);transform:translateY(-1px)}

.cert-search-wrap{position:relative;display:flex;align-items:center}
.cert-search-ico{position:absolute;left:12px;color:var(--muted);font-size:13px;pointer-events:none}
.cert-search{padding:8px 14px 8px 36px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);width:260px;outline:none;transition:border-color var(--dur),box-shadow var(--dur)}
.cert-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.cert-filter{padding:8px 14px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);outline:none;cursor:pointer;transition:border-color var(--dur),box-shadow var(--dur)}
.cert-filter:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}

#certificatesTable thead th{font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);background:#f8f9fd;padding:13px 16px;border-bottom:1px solid var(--border)}
#certificatesTable thead th.sortable{cursor:pointer;user-select:none}
#certificatesTable thead th.sortable:hover{background:#eef0fd;color:var(--primary)}
#certificatesTable thead th.sortable:hover .sort-icon{opacity:1}
.sort-icon{margin-left:5px;font-size:10px;opacity:0.3;transition:opacity var(--dur)}
#certificatesTable tbody td{padding:14px 16px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle}
#certificatesTable tbody tr:last-child td{border-bottom:none}
#certificatesTable tbody tr{transition:background var(--dur)}
#certificatesTable tbody tr:hover{background:#f8f9fd}
th.sorting-asc .sort-icon,th.sorting-desc .sort-icon{opacity:1;color:var(--primary)}

.cert-control{font-family:'Syne',sans-serif;font-size:11.5px;font-weight:700;color:var(--primary);background:var(--plt);padding:3px 9px;border-radius:6px;letter-spacing:.04em}
.cert-avatar{width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:14px}
.cert-type-pill{display:inline-flex;align-items:center;background:var(--plt);color:var(--primary);padding:4px 11px;border-radius:100px;font-size:12px;font-weight:600}

.status-badge{display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;padding:4px 11px;border-radius:100px}
.status-pending{background:#fff8e6;color:#f4a20a}
.status-approved{background:#e6faf3;color:#1cc88a}
.status-rejected{background:#fff0f3;color:#ff4d6d}
.status-released{background:#e3f2fd;color:#0d6efd}
.status-dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0}

.action-group{display:flex;align-items:center;justify-content:flex-end;gap:6px}
.action-btn{width:32px;height:32px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--muted);display:flex;align-items:center;justify-content:center;font-size:13px;cursor:pointer;transition:all var(--dur) var(--ease)}
.action-btn:hover{color:#4f63d2;border-color:#4f63d2;background:var(--plt)}
.action-btn-edit:hover{color:#f4a20a;border-color:#f4a20a;background:#fff8e6}
.action-btn-delete:hover{color:var(--danger);border-color:var(--danger);background:#fff0f3}
.action-btn-approve:hover{border-color:#1cc88a;background:#e6faf3}
.action-btn-reject:hover{border-color:#ff4d6d;background:#fff0f3}
.action-btn-release:hover{border-color:#0d6efd;background:#e3f2fd}

.empty-state{text-align:center;padding:48px 16px;color:var(--muted);font-size:14px}

.cert-modal{border:none;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(15,22,35,.22)}
.cert-modal-header{display:flex;align-items:center;justify-content:space-between;padding:22px 28px;border-bottom:1px solid var(--border);background:#fff}
.modal-icon-wrap{width:42px;height:42px;border-radius:12px;background:var(--plt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:17px}
.cert-modal-title{font-family:'Syne',sans-serif;font-weight:800;font-size:17px;color:var(--text);margin:0}
.cert-modal-close{width:34px;height:34px;border-radius:9px;border:1px solid var(--border);background:none;color:var(--muted);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:all var(--dur)}
.cert-modal-close:hover{background:var(--bg);color:var(--text)}
.cert-modal-body{padding:24px 28px}
.cert-modal-footer{padding:18px 28px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:flex-end;gap:10px;background:#fafbff}

.form-section-label{font-size:10.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;display:flex;align-items:center;gap:8px}
.form-section-label::after{content:'';flex:1;height:1px;background:var(--border)}

.cert-field{display:flex;flex-direction:column;gap:6px}
.cert-label{font-size:12.5px;font-weight:600;color:var(--text)}
.req{color:var(--danger)}
.cert-input{padding:9px 13px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text);outline:none;width:100%;transition:border-color var(--dur),box-shadow var(--dur),background var(--dur)}
.cert-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.cert-input option{background:#fff;color:var(--text)}

.fee-display{padding:9px 13px;border:1px solid var(--border);border-radius:9px;background:#f8f9fd;font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text)}
.fee-amount{font-weight:700;color:var(--primary);margin-right:8px}
.fee-note{font-size:11.5px;color:var(--muted)}

.btn-cancel{padding:9px 20px;border-radius:9px;border:1px solid var(--border);background:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:var(--muted);cursor:pointer;transition:all var(--dur)}
.btn-cancel:hover{border-color:var(--text);color:var(--text)}
.btn-save{display:inline-flex;align-items:center;gap:8px;padding:9px 22px;border-radius:9px;border:none;background:var(--primary);font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:#fff;cursor:pointer;box-shadow:0 4px 14px rgba(79,99,210,.35);transition:all var(--dur)}
.btn-save:hover{background:#3d4fc0;box-shadow:0 6px 20px rgba(79,99,210,.45);transform:translateY(-1px)}
.btn-print{display:inline-flex;align-items:center;gap:8px;padding:9px 22px;border-radius:9px;border:1px solid var(--border);background:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:var(--primary);cursor:pointer;transition:all var(--dur)}
.btn-print:hover{background:var(--plt);border-color:var(--primary)}

.pagination .page-item .page-link{border-radius:8px!important;font-size:13px;font-weight:500;border:1px solid var(--border);color:var(--muted);margin:0 2px;transition:all var(--dur)}
.pagination .page-item.active .page-link{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff}
.pagination .page-item .page-link:hover{border-color:var(--primary);color:var(--primary);background:var(--plt)}
#paginationLinks nav{display:flex;justify-content:flex-end}

.v-row{display:flex;gap:12px;padding:11px 0;border-bottom:1px solid var(--border)}
.v-row:last-child{border-bottom:none}
.v-lbl{font-size:11.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--muted);min-width:120px;flex-shrink:0;padding-top:2px}
.v-val{font-size:13.5px;color:var(--text);font-weight:500}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    /* ─────────────────────────────────────────
       DATA STORE
    ───────────────────────────────────────── */
    let certificatesData = [];
    let currentSort  = { column: 'control', direction: 'asc' };
    let currentPage  = 1;
    let perPage      = 10;
    let filteredData = [];

    @if(isset($certificateRequests) && $certificateRequests->count() > 0)
        @foreach($certificateRequests as $req)
        certificatesData.push({
            id:             {{ $req->id }},
            control:        '{{ addslashes($req->control_number) }}',
            resident_name:  '{{ addslashes($req->resident->full_name ?? '') }}',
            resident_code:  '{{ addslashes($req->resident->resident_code ?? '') }}',
            certificate:    '{{ addslashes($req->certificateType->certificate_name ?? '') }}',
            purpose:        '{{ addslashes($req->purpose ?? '') }}',
            requested:      '{{ $req->requested_at }}',
            status:         '{{ $req->status }}',
            avatar_color:   '{{ $req->resident ? ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($req->resident->full_name ?? 'R') % 5] : '#b0b7cc' }}',
            avatar_initial: '{{ $req->resident ? strtoupper(substr($req->resident->full_name ?? 'R', 0, 1)) : '?' }}'
        });
        @endforeach
    @endif

    filteredData = [...certificatesData];
    renderTable();

    /* ─────────────────────────────────────────
       CONTROLS
    ───────────────────────────────────────── */
    $('#showEntries').on('change', function () {
        perPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
    });

    $('#certificateSearch').on('input', function () { applyFilters(); });
    $('#filterStatus, #filterType').on('change', function () { applyFilters(); });

    // Fee auto-fill
    $('#certTypeSelect').on('change', function () {
        let fee = $(this).find('option:selected').data('fee') || 0;
        $('.fee-amount').text('₱' + parseFloat(fee).toFixed(2));
    });

    // Column sorting
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
    function applyFilters() {
        let search = $('#certificateSearch').val().toLowerCase();
        let status = $('#filterStatus').val().toLowerCase();
        let type   = $('#filterType').val().toLowerCase();

        filteredData = certificatesData.filter(function (item) {
            let matchSearch =
                !search ||
                (item.control       && item.control.toLowerCase().includes(search))       ||
                (item.resident_name && item.resident_name.toLowerCase().includes(search)) ||
                (item.resident_code && item.resident_code.toLowerCase().includes(search)) ||
                (item.certificate   && item.certificate.toLowerCase().includes(search))   ||
                (item.purpose       && item.purpose.toLowerCase().includes(search));

            let matchStatus = !status || item.status.toLowerCase() === status;
            let matchType   = !type   || (item.certificate && item.certificate.toLowerCase().includes(type));
            return matchSearch && matchStatus && matchType;
        });

        sortData();
        currentPage = 1;
        renderTable();
    }

    function sortData() {
        filteredData.sort(function (a, b) {
            let valA = a[currentSort.column];
            let valB = b[currentSort.column];
            if (currentSort.column === 'requested') {
                valA = valA ? new Date(valA).getTime() : 0;
                valB = valB ? new Date(valB).getTime() : 0;
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
        let tbody    = $('#certificatesTable tbody');
        tbody.empty();

        if (pageData.length === 0) {
            tbody.html(`<tr><td colspan="7" class="empty-state">
                <i class="fas fa-file-circle-exclamation" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                No certificate requests found.</td></tr>`);
        } else {
            pageData.forEach(function (item) {
                let dateStr = item.requested
                    ? new Date(item.requested).toLocaleDateString('en-US', { month:'short', day:'2-digit', year:'numeric' })
                    : '—';

                let actions = `<button class="action-btn view-certificate" data-id="${item.id}" title="View"><i class="fas fa-eye"></i></button>`;

                if (item.status === 'pending') {
                    actions += `
                        <button class="action-btn action-btn-edit edit-certificate" data-id="${item.id}" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                        <button class="action-btn action-btn-approve approve-certificate" data-id="${item.id}" title="Approve"><i class="fas fa-circle-check" style="color:#1cc88a"></i></button>
                        <button class="action-btn action-btn-reject reject-certificate" data-id="${item.id}" title="Reject"><i class="fas fa-circle-xmark" style="color:#ff4d6d"></i></button>`;
                } else if (item.status === 'approved') {
                    actions += `
                        <button class="action-btn action-btn-release release-certificate" data-id="${item.id}" title="Mark as Released"><i class="fas fa-hand-holding-heart" style="color:#0d6efd"></i></button>
                        <button class="action-btn action-btn-delete delete-certificate" data-id="${item.id}" title="Delete"><i class="fas fa-trash-can"></i></button>`;
                } else {
                    actions += `<button class="action-btn action-btn-delete delete-certificate" data-id="${item.id}" title="Delete"><i class="fas fa-trash-can"></i></button>`;
                }

                tbody.append(`
                    <tr>
                        <td><span class="cert-control">${item.control || '—'}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="cert-avatar" style="background:${item.avatar_color}">${item.avatar_initial}</div>
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">${item.resident_name || 'Unknown'}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">${item.resident_code || ''}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="cert-type-pill">${item.certificate || ''}</span></td>
                        <td style="max-width:200px">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--muted);font-size:13px" title="${item.purpose || ''}">
                                ${item.purpose || '—'}
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--muted)">${dateStr}</td>
                        <td>
                            <span class="status-badge status-${item.status}">
                                <span class="status-dot"></span>
                                ${item.status ? item.status.charAt(0).toUpperCase() + item.status.slice(1) : 'Pending'}
                            </span>
                        </td>
                        <td><div class="action-group">${actions}</div></td>
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
       ADD — POST /certificates
    ═══════════════════════════════════════════════════ */
    $('#addCertificateForm').on('submit', function (e) {
        e.preventDefault();
        let $btn = $(this).find('.btn-save')
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>Submitting…');

        $.ajax({
            url:  '{{ route("certificates.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#addCertificateModal').modal('hide');
                    $('#addCertificateForm')[0].reset();
                    $('.fee-amount').text('₱0.00');
                    // ── Wait for toast to finish before reload ──
                    Swal.fire({
                        icon: 'success',
                        title: 'Request Submitted!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    }).then(function () {
                        location.reload();
                    });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let msg    = errors
                    ? Object.values(errors).flat().join('\n')
                    : (xhr.responseJSON?.message || 'Something went wrong.');
                Swal.fire('Validation Error', msg, 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-floppy-disk"></i> Submit Request');
            }
        });
    });

    $('#addCertificateModal').on('hidden.bs.modal', function () {
        $('#addCertificateForm')[0].reset();
        $('.fee-amount').text('₱0.00');
    });

    /* ═══════════════════════════════════════════════════
       VIEW — GET /certificates/{id}
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '.view-certificate', function () {
        let id = $(this).data('id');

        $('#viewCertificateSubtitle').text('Loading…');
        $('#viewCertificateBody').html(`
            <div style="text-align:center;padding:40px 0;color:var(--muted)">
                <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                <div style="margin-top:10px;font-size:13px">Loading details…</div>
            </div>`);
        $('#printCertificateBtn').hide();
        $('#viewCertificateModal').modal('show');

        $.ajax({
            url:  '{{ url("certificates") }}/' + id,
            type: 'GET',
            success: function (res) {
                if (!res.success) return;
                let c        = res.data;
                let resident = c.resident;
                let certType = c.certificate_type;
                let fullName = resident ? resident.full_name : '—';
                let colors   = ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'];
                let avatarBg = colors[fullName.length % colors.length];

                let statusHtml  = `<span class="status-badge status-${c.status}"><span class="status-dot"></span>${c.status.charAt(0).toUpperCase() + c.status.slice(1)}</span>`;
                let approvedBy  = c.approver ? c.approver.name : '—';
                let approvedAt  = c.approved_at  ? new Date(c.approved_at).toLocaleString()  : '—';
                let releasedAt  = c.released_at  ? new Date(c.released_at).toLocaleString()  : '—';
                let requestedAt = c.requested_at ? new Date(c.requested_at).toLocaleString() : '—';

                $('#viewCertificateSubtitle').text(c.control_number);
                $('#viewCertificateBody').html(`
                    <div style="display:flex;align-items:center;gap:14px;padding:0 0 20px;border-bottom:1px solid var(--border);margin-bottom:4px">
                        <div class="cert-avatar" style="width:48px;height:48px;font-size:18px;background:${avatarBg}">
                            ${fullName.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:var(--text)">${fullName}</div>
                            <div style="font-size:12px;color:var(--muted);margin-top:2px">${resident ? (resident.resident_code || '') : ''}</div>
                        </div>
                    </div>
                    <div class="v-row"><span class="v-lbl">Control #</span><span class="v-val" style="font-weight:700;color:var(--primary)">${c.control_number}</span></div>
                    <div class="v-row"><span class="v-lbl">Certificate</span><span class="v-val"><span class="cert-type-pill">${certType ? certType.certificate_name : '—'}</span></span></div>
                    <div class="v-row"><span class="v-lbl">Purpose</span><span class="v-val">${c.purpose || '—'}</span></div>
                    <div class="v-row"><span class="v-lbl">Fee</span><span class="v-val">₱${certType ? parseFloat(certType.fee).toFixed(2) : '0.00'}</span></div>
                    <div class="v-row"><span class="v-lbl">Requested</span><span class="v-val">${requestedAt}</span></div>
                    <div class="v-row"><span class="v-lbl">Status</span><span class="v-val">${statusHtml}</span></div>
                    <div class="v-row"><span class="v-lbl">Approved By</span><span class="v-val">${approvedBy}</span></div>
                    <div class="v-row"><span class="v-lbl">Approved At</span><span class="v-val">${approvedAt}</span></div>
                    <div class="v-row"><span class="v-lbl">Released At</span><span class="v-val">${releasedAt}</span></div>
                `);

                if (c.status === 'approved' || c.status === 'released') {
                    $('#printCertificateBtn').show().data('id', c.id);
                }
            },
            error: function () {
                $('#viewCertificateBody').html(`
                    <div style="text-align:center;padding:30px;color:var(--danger)">
                        <i class="fas fa-circle-exclamation" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        Failed to load certificate details.
                    </div>`);
            }
        });
    });

    /* ═══════════════════════════════════════════════════
       APPROVE / REJECT / RELEASE — open notes modal
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '.approve-certificate', function () {
        $('#actionRequestId').val($(this).data('id'));
        $('#actionType').val('approve');
        $('#actionIcon').html('<i class="fas fa-circle-check" style="color:#1cc88a"></i>');
        $('#actionTitle').text('Approve Request');
        $('#actionNotes').val('');
        $('#actionNotesModal').modal('show');
    });

    $(document).on('click', '.reject-certificate', function () {
        $('#actionRequestId').val($(this).data('id'));
        $('#actionType').val('reject');
        $('#actionIcon').html('<i class="fas fa-circle-xmark" style="color:#ff4d6d"></i>');
        $('#actionTitle').text('Reject Request');
        $('#actionNotes').val('');
        $('#actionNotesModal').modal('show');
    });

    $(document).on('click', '.release-certificate', function () {
        $('#actionRequestId').val($(this).data('id'));
        $('#actionType').val('release');
        $('#actionIcon').html('<i class="fas fa-hand-holding-heart" style="color:#0d6efd"></i>');
        $('#actionTitle').text('Mark as Released');
        $('#actionNotes').val('');
        $('#actionNotesModal').modal('show');
    });

    /* ── Submit approve / reject / release ── */
    $('#actionNotesForm').on('submit', function (e) {
        e.preventDefault();
        let id     = $('#actionRequestId').val();
        let action = $('#actionType').val();
        let notes  = $('#actionNotes').val();
        let $btn   = $('#actionSubmitBtn')
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> Processing…');

        $.ajax({
            url:  '{{ url("certificates") }}/' + id + '/' + action,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', notes: notes },
            success: function (res) {
                if (res.success) {
                    $('#actionNotesModal').modal('hide');
                    // ── Wait for toast before reload ──
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    }).then(function () {
                        location.reload();
                    });
                }
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message
                       || xhr.responseJSON?.error
                       || 'Something went wrong.';
                Swal.fire('Error', msg, 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Confirm');
            }
        });
    });

    /* ═══════════════════════════════════════════════════
       DELETE — DELETE /certificates/{id}
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '.delete-certificate', function () {
        let id  = $(this).data('id');
        let url = '{{ url("certificates") }}/' + id;

        Swal.fire({
            title: 'Are you sure?',
            text:  'This certificate request will be permanently removed.',
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
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                    success: function (res) {
                        if (res.success) {
                            // ── Wait for toast before reload ──
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            }).then(function () {
                                location.reload();
                            });
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON?.message
                               || xhr.responseJSON?.error
                               || 'Something went wrong.';
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });

    /* ═══════════════════════════════════════════════════
       EDIT — GET /certificates/{id}/edit
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '.edit-certificate', function () {
        window.location.href = '{{ url("certificates") }}/' + $(this).data('id') + '/edit';
    });

    /* ═══════════════════════════════════════════════════
       PRINT — GET /certificates/{id}/print
    ═══════════════════════════════════════════════════ */
    $(document).on('click', '#printCertificateBtn', function () {
        let id = $(this).data('id');
        if (id) window.open('{{ url("certificates") }}/' + id + '/print', '_blank');
    });

});
</script>
@endpush