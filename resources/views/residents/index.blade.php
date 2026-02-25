@extends('layouts.app')

@section('title', 'Residents')

@section('content')

{{-- ── Page header ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);margin:0;line-height:1.2">Residents</h2>
        <p style="font-size:13px;color:var(--muted);margin:3px 0 0">Manage and view all registered barangay residents</p>
    </div>
    <button type="button" class="btn-add-resident" data-bs-toggle="modal" data-bs-target="#addResidentModal">
        <i class="fas fa-plus"></i> Add Resident
    </button>
</div>

{{-- ── Stats strip ── --}}
<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fd;color:#4f63d2"><i class="fas fa-users"></i></div>
        <div><div class="stat-val">{{ $residents->total() }}</div><div class="stat-lbl">Total Residents</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e6faf3;color:#1cc88a"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-val">{{ $residents->where('status','active')->count() }}</div><div class="stat-lbl">Active</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f3;color:#ff4d6d"><i class="fas fa-circle-xmark"></i></div>
        <div><div class="stat-val">{{ $residents->where('status','inactive')->count() }}</div><div class="stat-lbl">Inactive</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-vote-yea"></i></div>
        <div><div class="stat-val">{{ $residents->where('voter_status',true)->count() }}</div><div class="stat-lbl">Voters</div></div>
    </div>
</div>

{{-- ── Table card ── --}}
<div class="card">
    <div class="card-body" style="padding:0">

        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="font-size:13px;color:var(--muted)">Show</span>
                    <select id="showEntries" class="res-filter" style="width:70px">
                        <option value="10">10</option><option value="25">25</option>
                        <option value="50">50</option><option value="100">100</option>
                    </select>
                    <span style="font-size:13px;color:var(--muted)">entries</span>
                </div>
                <select id="filterStatus" class="res-filter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="filterGender" class="res-filter">
                    <option value="">All Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="res-search-wrap">
                <i class="fas fa-magnifying-glass res-search-ico"></i>
                <input type="text" id="residentSearch" class="res-search" placeholder="Search residents…">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="residentsTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="code">Code <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="name">Resident <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="gender">Gender <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="age">Age <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="civil_status">Civil Status <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="contact">Contact <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="status">Status <i class="fas fa-sort sort-icon"></i></th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                    <tr>
                        <td><span class="res-code">{{ $resident->resident_code ?? '—' }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($resident->photo)
                                    <img src="{{ asset('storage/'.$resident->photo) }}" class="res-avatar-img" alt="">
                                @else
                                    <div class="res-avatar" style="background:{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($resident->full_name ?? 'R') % 5] }}">
                                        {{ strtoupper(substr($resident->full_name ?? 'R', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">{{ $resident->full_name }}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">{{ $resident->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="gender-pill gender-{{ strtolower($resident->gender ?? 'n') }}">
                                @if(strtolower($resident->gender ?? '') === 'male')<i class="fas fa-mars"></i>
                                @elseif(strtolower($resident->gender ?? '') === 'female')<i class="fas fa-venus"></i>
                                @endif
                                {{ ucfirst($resident->gender ?? '—') }}
                            </span>
                        </td>
                        <td style="font-weight:600;color:var(--text)">{{ $resident->age ?? '—' }}</td>
                        <td style="font-size:13px;color:var(--muted)">{{ ucfirst($resident->civil_status ?? '—') }}</td>
                        <td style="font-size:13px">
                            @if($resident->contact_number)
                                <i class="fas fa-phone" style="color:var(--muted);font-size:11px;margin-right:4px"></i>{{ $resident->contact_number }}
                            @else
                                <span style="color:var(--border)">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $resident->status ?? 'inactive' }}">
                                <span class="status-dot"></span>{{ ucfirst($resident->status ?? 'inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-resident" data-id="{{ $resident->id }}" title="View"><i class="fas fa-eye"></i></button>
                                <button class="action-btn action-btn-edit edit-resident" data-id="{{ $resident->id }}" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                                <button class="action-btn action-btn-delete delete-resident" data-id="{{ $resident->id }}" title="Delete"><i class="fas fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="fas fa-users-slash" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>
                            No residents found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $residents->firstItem() ?? 0 }} to {{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }} entries
            </div>
            <div id="paginationLinks">{{ $residents->links() }}</div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════
     ADD RESIDENT MODAL
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="addResidentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content res-modal">
            <div class="res-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <h5 class="res-modal-title">Add New Resident</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Fill in the resident's information below</p>
                    </div>
                </div>
                <button type="button" class="res-modal-close" data-bs-dismiss="modal"><i class="fas fa-xmark"></i></button>
            </div>
            <form id="addResidentForm" enctype="multipart/form-data">
                @csrf
                <div class="res-modal-body">

                    {{-- Photo Upload --}}
                    <div class="form-section-label">Profile Photo</div>
                    <div class="photo-upload-wrap">
                        <div class="photo-preview" id="addPhotoPreview">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="photo-upload-info">
                            <label class="photo-upload-btn" for="addPhotoInput">
                                <i class="fas fa-camera"></i> Choose Photo
                            </label>
                            <input type="file" id="addPhotoInput" name="photo" accept="image/*" style="display:none">
                            <p style="font-size:11.5px;color:var(--muted);margin:6px 0 0">JPG, PNG or WEBP · Max 2MB</p>
                            <button type="button" class="photo-remove-btn" id="addPhotoRemove" style="display:none">
                                <i class="fas fa-xmark"></i> Remove
                            </button>
                        </div>
                    </div>

                    <div class="form-section-label" style="margin-top:24px">Personal Information</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">First Name <span class="req">*</span></label>
                                <input type="text" class="res-input" name="first_name" placeholder="e.g. Juan" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Middle Name</label>
                                <input type="text" class="res-input" name="middle_name" placeholder="Optional">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Last Name <span class="req">*</span></label>
                                <input type="text" class="res-input" name="last_name" placeholder="e.g. Dela Cruz" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Suffix</label>
                                <select class="res-input" name="suffix">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="III">III</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Gender <span class="req">*</span></label>
                                <select class="res-input" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Birthdate <span class="req">*</span></label>
                                <input type="date" class="res-input" name="birthdate" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-label" style="margin-top:24px">Status & Contact</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Civil Status <span class="req">*</span></label>
                                <select class="res-input" name="civil_status" required>
                                    <option value="">Select Status</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Contact Number</label>
                                <input type="text" class="res-input" name="contact_number" placeholder="e.g. 09XX XXX XXXX">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Email Address</label>
                                <input type="email" class="res-input" name="email" placeholder="optional@email.com">
                            </div>
                        </div>
                    </div>

                    <div class="form-section-label" style="margin-top:24px">Additional Details</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Occupation</label>
                                <input type="text" class="res-input" name="occupation" placeholder="e.g. Teacher">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Household</label>
                                <select class="res-input" name="household_id">
                                    <option value="">Select Household</option>
                                    @foreach($households ?? [] as $household)
                                        <option value="{{ $household->id }}">{{ $household->household_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Flags</label>
                                <div style="display:flex;flex-direction:column;gap:10px;padding-top:4px">
                                    <label class="res-toggle">
                                        <input type="checkbox" name="voter_status">
                                        <span class="toggle-track"></span>
                                        <span class="toggle-label">Registered Voter</span>
                                    </label>
                                    <label class="res-toggle">
                                        <input type="checkbox" name="is_pwd">
                                        <span class="toggle-track"></span>
                                        <span class="toggle-label">PWD</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="res-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save" id="addSaveBtn">
                        <i class="fas fa-floppy-disk"></i> Save Resident
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════
     VIEW RESIDENT MODAL
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="viewResidentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content res-modal">
            <div class="res-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-eye"></i></div>
                    <div>
                        <h5 class="res-modal-title">Resident Details</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Viewing resident information</p>
                    </div>
                </div>
                <button type="button" class="res-modal-close" data-bs-dismiss="modal"><i class="fas fa-xmark"></i></button>
            </div>
            <div class="res-modal-body" id="viewResidentBody">
                <div style="text-align:center;padding:40px">
                    <i class="fas fa-spinner fa-spin" style="font-size:28px;color:var(--primary)"></i>
                </div>
            </div>
            <div class="res-modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn-save" id="openEditFromView">
                    <i class="fas fa-pen-to-square"></i> Edit Resident
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════
     EDIT RESIDENT MODAL
══════════════════════════════════════════════════ --}}
<div class="modal fade" id="editResidentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content res-modal">
            <div class="res-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-pen-to-square"></i></div>
                    <div>
                        <h5 class="res-modal-title">Edit Resident</h5>
                        <p style="font-size:12px;color:var(--muted);margin:0">Update resident information</p>
                    </div>
                </div>
                <button type="button" class="res-modal-close" data-bs-dismiss="modal"><i class="fas fa-xmark"></i></button>
            </div>
            <form id="editResidentForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="remove_photo" id="editRemovePhoto" value="0">
                <div class="res-modal-body">

                    {{-- Photo Upload --}}
                    <div class="form-section-label">Profile Photo</div>
                    <div class="photo-upload-wrap">
                        <div class="photo-preview" id="editPhotoPreview">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="photo-upload-info">
                            <label class="photo-upload-btn" for="editPhotoInput">
                                <i class="fas fa-camera"></i> Change Photo
                            </label>
                            <input type="file" id="editPhotoInput" name="photo" accept="image/*" style="display:none">
                            <p style="font-size:11.5px;color:var(--muted);margin:6px 0 0">JPG, PNG or WEBP · Max 2MB</p>
                            <button type="button" class="photo-remove-btn" id="editPhotoRemove" style="display:none">
                                <i class="fas fa-xmark"></i> Remove Photo
                            </button>
                        </div>
                    </div>

                    <div class="form-section-label" style="margin-top:24px">Personal Information</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">First Name <span class="req">*</span></label>
                                <input type="text" class="res-input" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Middle Name</label>
                                <input type="text" class="res-input" name="middle_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Last Name <span class="req">*</span></label>
                                <input type="text" class="res-input" name="last_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Suffix</label>
                                <select class="res-input" name="suffix">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="III">III</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Gender <span class="req">*</span></label>
                                <select class="res-input" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Birthdate <span class="req">*</span></label>
                                <input type="date" class="res-input" name="birthdate" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-label" style="margin-top:24px">Status & Contact</div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="res-field">
                                <label class="res-label">Civil Status <span class="req">*</span></label>
                                <select class="res-input" name="civil_status" required>
                                    <option value="">Select</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="res-field">
                                <label class="res-label">Status</label>
                                <select class="res-input" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="res-field">
                                <label class="res-label">Contact Number</label>
                                <input type="text" class="res-input" name="contact_number">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="res-field">
                                <label class="res-label">Email Address</label>
                                <input type="email" class="res-input" name="email">
                            </div>
                        </div>
                    </div>

                    <div class="form-section-label" style="margin-top:24px">Additional Details</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Occupation</label>
                                <input type="text" class="res-input" name="occupation">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Household</label>
                                <select class="res-input" name="household_id">
                                    <option value="">Select Household</option>
                                    @foreach($households ?? [] as $household)
                                        <option value="{{ $household->id }}">{{ $household->household_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="res-field">
                                <label class="res-label">Flags</label>
                                <div style="display:flex;flex-direction:column;gap:10px;padding-top:4px">
                                    <label class="res-toggle">
                                        <input type="checkbox" name="voter_status" id="edit_voter_status">
                                        <span class="toggle-track"></span>
                                        <span class="toggle-label">Registered Voter</span>
                                    </label>
                                    <label class="res-toggle">
                                        <input type="checkbox" name="is_pwd" id="edit_is_pwd">
                                        <span class="toggle-track"></span>
                                        <span class="toggle-label">PWD</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="res-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save" id="editSaveBtn">
                        <i class="fas fa-floppy-disk"></i> Update Resident
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════ STYLES ══════════════════════════════════════════════════ --}}
<style>
.stat-strip{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:22px}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 10px rgba(15,22,35,.05);transition:box-shadow .2s,transform .2s}
.stat-card:hover{box-shadow:0 6px 22px rgba(15,22,35,.1);transform:translateY(-2px)}
.stat-icon{width:42px;height:42px;border-radius:11px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px}
.stat-val{font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);line-height:1}
.stat-lbl{font-size:11.5px;color:var(--muted);margin-top:3px;font-weight:500}

.btn-add-resident{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border-radius:10px;border:none;cursor:pointer;background:var(--primary);color:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;transition:all .2s;box-shadow:0 4px 14px rgba(79,99,210,.35)}
.btn-add-resident:hover{background:#3d4fc0;box-shadow:0 6px 20px rgba(79,99,210,.45);transform:translateY(-1px)}

.res-search-wrap{position:relative;display:flex;align-items:center}
.res-search-ico{position:absolute;left:12px;color:var(--muted);font-size:13px;pointer-events:none}
.res-search{padding:8px 14px 8px 36px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);width:260px;outline:none;transition:border-color .2s,box-shadow .2s}
.res-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.res-filter{padding:8px 14px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);outline:none;cursor:pointer}
.res-filter:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12)}

#residentsTable thead th{font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);background:#f8f9fd;padding:13px 16px;border-bottom:1px solid var(--border)}
#residentsTable thead th.sortable{cursor:pointer;user-select:none}
#residentsTable thead th.sortable:hover{background:#eef0fd;color:var(--primary)}
.sort-icon{margin-left:5px;font-size:10px;opacity:.3;transition:opacity .2s}
#residentsTable thead th.sortable:hover .sort-icon{opacity:1}
#residentsTable tbody td{padding:14px 16px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle}
#residentsTable tbody tr:last-child td{border-bottom:none}
#residentsTable tbody tr:hover{background:#f8f9fd}
th.sorting-asc .sort-icon::before{content:"\f0de";opacity:1;color:var(--primary)}
th.sorting-desc .sort-icon::before{content:"\f0dd";opacity:1;color:var(--primary)}

.res-avatar{width:38px;height:38px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:14px}
.res-avatar-img{width:38px;height:38px;border-radius:10px;object-fit:cover;flex-shrink:0;border:1px solid var(--border)}
.res-code{font-family:'Syne',sans-serif;font-size:11.5px;font-weight:700;color:var(--primary);background:var(--plt);padding:3px 9px;border-radius:6px;letter-spacing:.04em}

.gender-pill{display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:3px 11px;border-radius:100px}
.gender-male{background:#eef3ff;color:#4f63d2}
.gender-female{background:#fff0f7;color:#d45fa0}
.gender-n{background:#f0f2f8;color:var(--muted)}

.status-badge{display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;padding:4px 11px;border-radius:100px}
.status-active{background:#e6faf3;color:#1cc88a}
.status-inactive{background:#fff0f3;color:#ff4d6d}
.status-dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0}

.action-group{display:flex;align-items:center;justify-content:flex-end;gap:6px}
.action-btn{width:32px;height:32px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--muted);display:flex;align-items:center;justify-content:center;font-size:13px;cursor:pointer;transition:all .2s}
.action-btn:hover{color:#4f63d2;border-color:#4f63d2;background:var(--plt)}
.action-btn-edit:hover{color:#f4a20a;border-color:#f4a20a;background:#fff8e6}
.action-btn-delete:hover{color:#ff4d6d;border-color:#ff4d6d;background:#fff0f3}
.empty-state{text-align:center;padding:48px 16px;color:var(--muted);font-size:14px}

/* Modal */
.res-modal{border:none;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(15,22,35,.22)}
.res-modal-header{display:flex;align-items:center;justify-content:space-between;padding:22px 28px;border-bottom:1px solid var(--border);background:#fff}
.modal-icon-wrap{width:42px;height:42px;border-radius:12px;background:var(--plt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:17px}
.res-modal-title{font-family:'Syne',sans-serif;font-weight:800;font-size:17px;color:var(--text);margin:0}
.res-modal-close{width:34px;height:34px;border-radius:9px;border:1px solid var(--border);background:none;color:var(--muted);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:all .2s}
.res-modal-close:hover{background:var(--bg);color:var(--text)}
.res-modal-body{padding:24px 28px;max-height:65vh;overflow-y:auto}
.res-modal-footer{padding:18px 28px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:flex-end;gap:10px;background:#fafbff}

/* Photo upload */
.photo-upload-wrap{display:flex;align-items:center;gap:20px;padding:16px;background:var(--bg);border:1px solid var(--border);border-radius:12px}
.photo-preview{width:80px;height:80px;border-radius:14px;background:#eef0fd;border:2px dashed var(--border);display:flex;align-items:center;justify-content:center;font-size:32px;color:var(--muted);overflow:hidden;flex-shrink:0}
.photo-preview img{width:100%;height:100%;object-fit:cover}
.photo-upload-info{display:flex;flex-direction:column;gap:4px}
.photo-upload-btn{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:8px;background:var(--primary);color:#fff;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;border:none}
.photo-upload-btn:hover{background:#3d4fc0}
.photo-remove-btn{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;background:none;border:1px solid #ff4d6d;color:#ff4d6d;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:600;cursor:pointer;transition:all .2s;margin-top:4px}
.photo-remove-btn:hover{background:#fff0f3}

/* Form */
.form-section-label{font-size:10.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;display:flex;align-items:center;gap:8px}
.form-section-label::after{content:'';flex:1;height:1px;background:var(--border)}
.res-field{display:flex;flex-direction:column;gap:6px}
.res-label{font-size:12.5px;font-weight:600;color:var(--text)}
.req{color:#ff4d6d}
.res-input{padding:9px 13px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text);outline:none;width:100%;transition:border-color .2s,box-shadow .2s}
.res-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.res-toggle{display:flex;align-items:center;gap:9px;cursor:pointer;user-select:none}
.res-toggle input{display:none}
.toggle-track{width:36px;height:20px;border-radius:100px;background:var(--border);flex-shrink:0;position:relative;transition:background .2s}
.toggle-track::after{content:'';position:absolute;top:3px;left:3px;width:14px;height:14px;border-radius:50%;background:#fff;transition:transform .2s;box-shadow:0 1px 4px rgba(0,0,0,.2)}
.res-toggle input:checked ~ .toggle-track{background:var(--primary)}
.res-toggle input:checked ~ .toggle-track::after{transform:translateX(16px)}
.toggle-label{font-size:13px;font-weight:500;color:var(--text)}
.btn-cancel{padding:9px 20px;border-radius:9px;border:1px solid var(--border);background:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:var(--muted);cursor:pointer;transition:all .2s}
.btn-cancel:hover{border-color:var(--text);color:var(--text)}
.btn-save{display:inline-flex;align-items:center;gap:8px;padding:9px 22px;border-radius:9px;border:none;background:var(--primary);font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:#fff;cursor:pointer;box-shadow:0 4px 14px rgba(79,99,210,.35);transition:all .2s}
.btn-save:hover{background:#3d4fc0;transform:translateY(-1px)}

/* View detail grid */
.view-detail-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(175px,1fr));gap:14px}
.view-detail-item{background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:14px 16px}
.view-detail-label{font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:5px}
.view-detail-value{font-size:13.5px;font-weight:600;color:var(--text);word-break:break-word}

/* Pagination */
.pagination .page-item .page-link{border-radius:8px!important;font-size:13px;font-weight:500;border:1px solid var(--border);color:var(--muted);margin:0 2px;transition:all .2s}
.pagination .page-item.active .page-link{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff}
.pagination .page-item .page-link:hover{border-color:var(--primary);color:var(--primary);background:var(--plt)}
#paginationLinks{display:flex;justify-content:flex-end}
#paginationLinks nav{display:flex;justify-content:flex-end}
</style>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    // ── Data store ───────────────────────────────────────────────────────────
    let residentsData = [];
    let currentSort   = { column: 'name', direction: 'asc' };
    let currentPage   = 1;
    let perPage       = 10;
    let filteredData  = [];

    @foreach($residents as $resident)
    residentsData.push({
        id:             {{ $resident->id }},
        code:           '{{ addslashes($resident->resident_code ?? '') }}',
        name:           '{{ addslashes($resident->full_name ?? '') }}',
        email:          '{{ addslashes($resident->email ?? '') }}',
        gender:         '{{ $resident->gender ?? '' }}',
        age:            {{ $resident->age ?? 0 }},
        civil_status:   '{{ $resident->civil_status ?? '' }}',
        contact:        '{{ addslashes($resident->contact_number ?? '') }}',
        status:         '{{ $resident->status ?? '' }}',
        voter_status:   {{ $resident->voter_status ? 'true' : 'false' }},
        photo_url:      '{{ $resident->photo ? asset("storage/".$resident->photo) : "" }}',
        avatar_color:   '{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($resident->full_name ?? 'R') % 5] }}',
        avatar_initial: '{{ strtoupper(substr($resident->full_name ?? 'R', 0, 1)) }}'
    });
    @endforeach

    filteredData = [...residentsData];
    renderTable();

    // ── Controls ─────────────────────────────────────────────────────────────
    $('#showEntries').on('change', function () { perPage = parseInt($(this).val()); currentPage = 1; renderTable(); });
    $('#residentSearch').on('input', applyFilters);
    $('#filterStatus, #filterGender').on('change', applyFilters);

    $('.sortable').on('click', function () {
        let col = $(this).data('sort');
        currentSort.direction = currentSort.column === col
            ? (currentSort.direction === 'asc' ? 'desc' : 'asc') : 'asc';
        currentSort.column = col;
        $('.sortable').removeClass('sorting-asc sorting-desc');
        $(this).addClass('sorting-' + currentSort.direction);
        sortData(); renderTable();
    });

    function applyFilters() {
        let search = $('#residentSearch').val().toLowerCase();
        let status = $('#filterStatus').val().toLowerCase();
        let gender = $('#filterGender').val().toLowerCase();
        filteredData = residentsData.filter(item =>
            (search === '' || item.name.toLowerCase().includes(search) || item.code.toLowerCase().includes(search) || item.email.toLowerCase().includes(search) || item.contact.toLowerCase().includes(search)) &&
            (status === '' || item.status.toLowerCase() === status) &&
            (gender === '' || item.gender.toLowerCase() === gender)
        );
        sortData(); currentPage = 1; renderTable();
    }

    function sortData() {
        filteredData.sort((a, b) => {
            let va = currentSort.column === 'age' ? (parseInt(a[currentSort.column]) || 0) : String(a[currentSort.column]).toLowerCase();
            let vb = currentSort.column === 'age' ? (parseInt(b[currentSort.column]) || 0) : String(b[currentSort.column]).toLowerCase();
            return va < vb ? (currentSort.direction === 'asc' ? -1 : 1) : va > vb ? (currentSort.direction === 'asc' ? 1 : -1) : 0;
        });
    }

    function cap(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '—'; }

    function avatarHtml(item) {
        return item.photo_url
            ? `<img src="${item.photo_url}" class="res-avatar-img" alt="">`
            : `<div class="res-avatar" style="background:${item.avatar_color}">${item.avatar_initial}</div>`;
    }

    // ── Render table ─────────────────────────────────────────────────────────
    function renderTable() {
        let start    = (currentPage - 1) * perPage;
        let pageData = filteredData.slice(start, start + perPage);
        let tbody    = $('#residentsTable tbody');
        tbody.empty();

        if (!pageData.length) {
            tbody.html(`<tr><td colspan="8" class="empty-state"><i class="fas fa-users-slash" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>No residents found.</td></tr>`);
        } else {
            pageData.forEach(item => {
                let genderIcon = item.gender === 'male' ? '<i class="fas fa-mars"></i>' : item.gender === 'female' ? '<i class="fas fa-venus"></i>' : '';
                let contactHtml = item.contact ? `<i class="fas fa-phone" style="color:var(--muted);font-size:11px;margin-right:4px"></i>${item.contact}` : `<span style="color:var(--border)">—</span>`;
                tbody.append(`
                    <tr>
                        <td><span class="res-code">${item.code || '—'}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                ${avatarHtml(item)}
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">${item.name}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">${item.email}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="gender-pill gender-${item.gender || 'n'}">${genderIcon} ${cap(item.gender)}</span></td>
                        <td style="font-weight:600;color:var(--text)">${item.age || '—'}</td>
                        <td style="font-size:13px;color:var(--muted)">${cap(item.civil_status)}</td>
                        <td style="font-size:13px">${contactHtml}</td>
                        <td><span class="status-badge status-${item.status || 'inactive'}"><span class="status-dot"></span>${cap(item.status)}</span></td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-resident" data-id="${item.id}" title="View"><i class="fas fa-eye"></i></button>
                                <button class="action-btn action-btn-edit edit-resident" data-id="${item.id}" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                                <button class="action-btn action-btn-delete delete-resident" data-id="${item.id}" title="Delete"><i class="fas fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>`);
            });
        }
        updatePagination();
        let total = filteredData.length, first = total ? start + 1 : 0, last = Math.min(start + perPage, total);
        $('#tableInfo').text(`Showing ${first} to ${last} of ${total} entries`);
    }

    function updatePagination() {
        let total = filteredData.length, totalPages = Math.ceil(total / perPage), $pg = $('#paginationLinks');
        if (totalPages <= 1) { $pg.empty(); return; }
        let html = '<nav><ul class="pagination">';
        html += `<li class="page-item ${currentPage===1?'disabled':''}"><a class="page-link" href="#" data-page="${currentPage-1}">Previous</a></li>`;
        let sp = Math.max(1, currentPage - 2), ep = Math.min(totalPages, sp + 4);
        if (sp > 1) { html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`; if (sp > 2) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`; }
        for (let i = sp; i <= ep; i++) html += `<li class="page-item ${i===currentPage?'active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        if (ep < totalPages) { if (ep < totalPages-1) html += `<li class="page-item disabled"><span class="page-link">…</span></li>`; html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`; }
        html += `<li class="page-item ${currentPage===totalPages?'disabled':''}"><a class="page-link" href="#" data-page="${currentPage+1}">Next</a></li></ul></nav>`;
        $pg.html(html);
        $pg.find('.page-link').on('click', function(e) {
            e.preventDefault();
            let pg = parseInt($(this).data('page'));
            if (pg && pg !== currentPage && pg >= 1 && pg <= totalPages) { currentPage = pg; renderTable(); }
        });
    }

    // ── Photo preview helper ──────────────────────────────────────────────────
    function setupPhotoPreview(inputId, previewId, removeId) {
        $('#' + inputId).on('change', function () {
            let file = this.files[0];
            if (!file) return;
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#' + previewId).html(`<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`);
                $('#' + removeId).show();
            };
            reader.readAsDataURL(file);
        });
        $('#' + removeId).on('click', function () {
            $('#' + inputId).val('');
            $('#' + previewId).html('<i class="fas fa-user-circle"></i>');
            $(this).hide();
            if (inputId === 'editPhotoInput') $('#editRemovePhoto').val('1');
        });
    }

    setupPhotoPreview('addPhotoInput',  'addPhotoPreview',  'addPhotoRemove');
    setupPhotoPreview('editPhotoInput', 'editPhotoPreview', 'editPhotoRemove');

    // ── ADD RESIDENT ─────────────────────────────────────────────────────────
    $('#addResidentForm').on('submit', function (e) {
        e.preventDefault();
        let btn = $('#addSaveBtn');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Saving…').prop('disabled', true);

        // Use FormData for file upload
        let formData = new FormData(this);

        $.ajax({
            url:         '{{ route("residents.store") }}',
            type:        'POST',
            data:        formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    $('#addResidentModal').modal('hide');
                    Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: res.message,
    showConfirmButton: false,
    timer: 1800,
    timerProgressBar: true
}).then(() => location.reload());
                }
            },
            error: function (xhr) {
                btn.html('<i class="fas fa-floppy-disk"></i> Save Resident').prop('disabled', false);
                if (xhr.status === 422) {
                    let msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    Swal.fire({ icon:'error', title:'Validation Error', text:msg, confirmButtonColor:'var(--primary)' });
                } else {
                    Swal.fire({ icon:'error', title:'Error', text:'Something went wrong.', confirmButtonColor:'var(--primary)' });
                }
            }
        });
    });

    $('#addResidentModal').on('hidden.bs.modal', function () {
        $('#addResidentForm')[0].reset();
        $('#addPhotoPreview').html('<i class="fas fa-user-circle"></i>');
        $('#addPhotoRemove').hide();
        $('#addSaveBtn').html('<i class="fas fa-floppy-disk"></i> Save Resident').prop('disabled', false);
    });

    // ── VIEW RESIDENT ─────────────────────────────────────────────────────────
    let currentViewId = null;

    $(document).on('click', '.view-resident', function () {
        currentViewId = $(this).data('id');
        $('#viewResidentBody').html(`<div style="text-align:center;padding:40px"><i class="fas fa-spinner fa-spin" style="font-size:28px;color:var(--primary)"></i></div>`);
        $('#viewResidentModal').modal('show');

        $.ajax({
            url: '{{ url("residents") }}/' + currentViewId,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                if (!res.success) { showViewError(); return; }
                let r      = res.resident;
                let colors = ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'];
                let color  = colors[Math.abs((r.full_name||'R').charCodeAt(0)) % 5];
                let init   = (r.full_name||'R').charAt(0).toUpperCase();
                let stCls  = r.status === 'active' ? 'status-active' : 'status-inactive';

                let avatarBlock = r.photo_url
                    ? `<img src="${r.photo_url}" style="width:72px;height:72px;border-radius:16px;object-fit:cover;flex-shrink:0;border:2px solid var(--border)" alt="">`
                    : `<div style="width:72px;height:72px;border-radius:16px;background:${color};display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:28px;flex-shrink:0">${init}</div>`;

                $('#viewResidentBody').html(`
                    <div style="display:flex;align-items:center;gap:18px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border)">
                        ${avatarBlock}
                        <div>
                            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:20px;color:var(--text)">${r.full_name||'—'}</div>
                            <div style="font-size:12px;color:var(--muted);margin-top:2px">${r.resident_code||'No Code'}</div>
                            <span class="status-badge ${stCls}" style="margin-top:6px;display:inline-flex">
                                <span class="status-dot"></span>${cap(r.status)}
                            </span>
                        </div>
                    </div>
                    <div class="view-detail-grid">
                        ${vd('Gender',       cap(r.gender))}
                        ${vd('Birthdate',    r.birthdate||'—')}
                        ${vd('Age',          r.age||'—')}
                        ${vd('Civil Status', cap(r.civil_status))}
                        ${vd('Contact',      r.contact_number||'—')}
                        ${vd('Email',        r.email||'—')}
                        ${vd('Occupation',   r.occupation||'—')}
                        ${vd('Voter Status', r.voter_status ? 'Registered Voter' : 'Not a Voter')}
                        ${vd('PWD',          r.is_pwd ? 'Yes' : 'No')}
                        ${vd('Senior',       r.is_senior ? 'Yes' : 'No')}
                        ${vd('Nationality',  r.nationality||'—')}
                        ${vd('Household',    r.household ? r.household.household_number : '—')}
                    </div>`);
            },
            error: showViewError
        });
    });

    function vd(label, value) {
        return `<div class="view-detail-item"><div class="view-detail-label">${label}</div><div class="view-detail-value">${value}</div></div>`;
    }
    function showViewError() {
        $('#viewResidentBody').html(`<p style="color:#ff4d6d;text-align:center;padding:24px">Failed to load resident data.</p>`);
    }

    $('#openEditFromView').on('click', function () {
        $('#viewResidentModal').modal('hide');
        setTimeout(() => openEditModal(currentViewId), 400);
    });

    // ── EDIT RESIDENT ─────────────────────────────────────────────────────────
    $(document).on('click', '.edit-resident', function () { openEditModal($(this).data('id')); });

    function openEditModal(id) {
        $.ajax({
            url:     '{{ url("residents") }}/' + id + '/edit',
            type:    'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                if (!res.success) { Swal.fire({ icon:'error', title:'Error', text:'Failed to load resident.', confirmButtonColor:'var(--primary)' }); return; }
                let r = res.resident, f = $('#editResidentForm');

                f.attr('data-id', id);
                $('#editRemovePhoto').val('0');
                f.find('[name="first_name"]').val(r.first_name    || '');
                f.find('[name="middle_name"]').val(r.middle_name  || '');
                f.find('[name="last_name"]').val(r.last_name      || '');
                f.find('[name="suffix"]').val(r.suffix            || '');
                f.find('[name="gender"]').val(r.gender            || '');
                f.find('[name="birthdate"]').val(r.birthdate      || '');
                f.find('[name="civil_status"]').val(r.civil_status|| '');
                f.find('[name="status"]').val(r.status            || 'active');
                f.find('[name="contact_number"]').val(r.contact_number || '');
                f.find('[name="email"]').val(r.email              || '');
                f.find('[name="occupation"]').val(r.occupation    || '');
                f.find('[name="household_id"]').val(r.household_id|| '');
                $('#edit_voter_status').prop('checked', r.voter_status == true);
                $('#edit_is_pwd').prop('checked', r.is_pwd == true);

                // Show existing photo
                if (r.photo_url) {
                    $('#editPhotoPreview').html(`<img src="${r.photo_url}" style="width:100%;height:100%;object-fit:cover">`);
                    $('#editPhotoRemove').show();
                } else {
                    $('#editPhotoPreview').html('<i class="fas fa-user-circle"></i>');
                    $('#editPhotoRemove').hide();
                }

                $('#editResidentModal').modal('show');
            },
            error: function () {
                Swal.fire({ icon:'error', title:'Error', text:'Failed to load resident data.', confirmButtonColor:'var(--primary)' });
            }
        });
    }

    // ── Submit Edit ───────────────────────────────────────────────────────────
    $('#editResidentForm').on('submit', function (e) {
        e.preventDefault();
        let id  = $(this).attr('data-id');
        let btn = $('#editSaveBtn');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Saving…').prop('disabled', true);

        // Use FormData for file upload
        let formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url:         '{{ url("residents") }}/' + id,
            type:        'POST',
            data:        formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    $('#editResidentModal').modal('hide');
                   Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: res.message,
    showConfirmButton: false,
    timer: 1800,
    timerProgressBar: true
}).then(() => location.reload());
                }
            },
            error: function (xhr) {
                btn.html('<i class="fas fa-floppy-disk"></i> Update Resident').prop('disabled', false);
                if (xhr.status === 422) {
                    let msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    Swal.fire({ icon:'error', title:'Validation Error', text:msg, confirmButtonColor:'var(--primary)' });
                } else {
                    Swal.fire({ icon:'error', title:'Error', text:'Something went wrong.', confirmButtonColor:'var(--primary)' });
                }
            }
        });
    });

    $('#editResidentModal').on('hidden.bs.modal', function () {
        $('#editResidentForm')[0].reset();
        $('#editPhotoPreview').html('<i class="fas fa-user-circle"></i>');
        $('#editPhotoRemove').hide();
        $('#editRemovePhoto').val('0');
        $('#editSaveBtn').html('<i class="fas fa-floppy-disk"></i> Update Resident').prop('disabled', false);
    });

    // ── DELETE ────────────────────────────────────────────────────────────────
    $(document).on('click', '.delete-resident', function () {
        let id = $(this).data('id');
        Swal.fire({
            icon: 'warning', title: 'Delete Resident?',
            text: 'This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText:  'Cancel',
            confirmButtonColor: '#ff4d6d',
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url:  '{{ url("residents") }}/' + id,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                    success: function (res) {
                        if (res.success) {
                           Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: res.message,
    showConfirmButton: false,
    timer: 1800,
    timerProgressBar: true
}).then(() => location.reload());
                        }
                    },
                    error: function () {
                        Swal.fire({ icon:'error', title:'Error', text:'Could not delete resident.', confirmButtonColor:'var(--primary)' });
                    }
                });
            }
        });
    });

});
</script>
@endpush