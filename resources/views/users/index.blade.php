@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);margin:0">User Management</h2>
        <p style="font-size:13px;color:var(--muted);margin:3px 0 0">Manage system users, roles, and access levels</p>
    </div>
    <button type="button" class="btn-add-user" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus"></i> New User
    </button>
</div>

<div class="stat-strip">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef0fd;color:#4f63d2"><i class="fas fa-users"></i></div>
        <div><div class="stat-val">{{ $totalUsers ?? 0 }}</div><div class="stat-lbl">Total Users</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e6faf3;color:#1cc88a"><i class="fas fa-circle-check"></i></div>
        <div><div class="stat-val">{{ $activeCount ?? 0 }}</div><div class="stat-lbl">Active</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff8e6;color:#f4a20a"><i class="fas fa-circle-pause"></i></div>
        <div><div class="stat-val">{{ $inactiveCount ?? 0 }}</div><div class="stat-lbl">Inactive</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff0f3;color:#ff4d6d"><i class="fas fa-lock"></i></div>
        <div><div class="stat-val">{{ $lockedCount ?? 0 }}</div><div class="stat-lbl">Locked</div></div>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding:0">
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="font-size:13px;color:var(--muted)">Show</span>
                    <select id="showEntries" class="user-filter" style="width:70px">
                        <option>10</option><option>25</option><option>50</option><option>100</option>
                    </select>
                    <span style="font-size:13px;color:var(--muted)">entries</span>
                </div>
                <select id="filterStatus" class="user-filter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="locked">Locked</option>
                </select>
                <select id="filterRole" class="user-filter">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                    <option value="{{ strtolower($role->role_name) }}">{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="user-search-wrap">
                <i class="fas fa-magnifying-glass user-search-ico"></i>
                <input type="text" id="userSearch" class="user-search" placeholder="Search users...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="name">User <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="username">Username <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="role">Role <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="contact">Contact <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="last_login">Last Login <i class="fas fa-sort sort-icon"></i></th>
                        <th class="sortable" data-sort="status">Status <i class="fas fa-sort sort-icon"></i></th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $u)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($u->profile_photo)
                                    <img src="{{ asset('storage/'.$u->profile_photo) }}" style="width:38px;height:38px;border-radius:10px;object-fit:cover" alt="">
                                @else
                                    <div class="user-avatar" style="background:{{ ['#4f63d2','#1cc88a','#f4a20a','#ff4d6d','#7c5cbf'][crc32($u->full_name)%5] }}">
                                        {{ strtoupper(substr($u->full_name,0,1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;color:var(--text)">{{ $u->full_name }}</div>
                                    <div style="font-size:11.5px;color:var(--muted)">{{ $u->email ?? '---' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="username-pill">@{{ $u->username }}</span></td>
                        <td>
                            <span class="role-badge role-{{ strtolower(str_replace(' ','-',$u->role->role_name ?? 'user')) }}">
                                <i class="fas fa-shield-halved"></i> {{ $u->role->role_name ?? '---' }}
                            </span>
                        </td>
                        <td style="font-size:13px;color:var(--muted)">{{ $u->contact_number ?? '---' }}</td>
                        <td style="font-size:13px;color:var(--muted)">
                            {{ $u->last_login ? \Carbon\Carbon::parse($u->last_login)->diffForHumans() : 'Never' }}
                        </td>
                        <td>
                            <span class="status-badge status-{{ $u->status }}">
                                <span class="status-dot"></span>{{ ucfirst($u->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view-user" data-id="{{ $u->id }}" title="View"><i class="fas fa-eye"></i></button>
                                <button class="action-btn action-btn-edit edit-user" data-id="{{ $u->id }}" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                                @if($u->id !== Auth::id())
                                <button class="action-btn action-btn-delete delete-user" data-id="{{ $u->id }}" title="Delete"><i class="fas fa-trash-can"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="empty-state">
                        <i class="fas fa-users-slash" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>No users found.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($users) && $users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div style="font-size:13px;color:var(--muted)" id="tableInfo">
                Showing {{ $users->firstItem()??0 }} to {{ $users->lastItem()??0 }} of {{ $users->total() }} entries
            </div>
            <div id="paginationLinks" style="display:flex;justify-content:flex-end;">{{ $users->links() }}</div>
        </div>
        @endif
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content user-modal">
            <div class="user-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-user-plus"></i></div>
                    <div><h5 class="user-modal-title">New User</h5><p style="font-size:12px;color:var(--muted);margin:0">Create a new system user</p></div>
                </div>
                <button type="button" class="user-modal-close" data-bs-dismiss="modal"><i class="fas fa-xmark"></i></button>
            </div>
            <form id="addUserForm" enctype="multipart/form-data">
                @csrf
                <div class="user-modal-body">
                    <div style="display:flex;justify-content:center;margin-bottom:20px">
                        <div class="photo-wrap">
                            <div id="addPhotoPlaceholder" class="photo-placeholder"><i class="fas fa-user"></i></div>
                            <img id="addPhotoImg" src="" style="width:90px;height:90px;object-fit:cover;border-radius:50%;display:none;position:absolute;top:0;left:0" alt="">
                            <label class="photo-cam-btn"><i class="fas fa-camera"></i><input type="file" name="profile_photo" id="addPhotoInput" accept="image/*" hidden></label>
                        </div>
                    </div>
                    <div class="form-section-label">Personal Information</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Full Name <span class="req">*</span></label><input type="text" class="user-input" name="full_name" placeholder="Juan dela Cruz" required></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Contact Number</label><input type="text" class="user-input" name="contact_number" placeholder="09XX-XXX-XXXX"></div></div>
                        <div class="col-12"><div class="user-field"><label class="user-label">Email Address</label><input type="email" class="user-input" name="email" placeholder="user@example.com"></div></div>
                    </div>
                    <div class="form-section-label">Account Settings</div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Username <span class="req">*</span></label><div style="position:relative"><span class="at-sign">@</span><input type="text" class="user-input" name="username" placeholder="username" style="padding-left:28px" required></div></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Role <span class="req">*</span></label><select class="user-input" name="role_id" required><option value="">--- Select Role ---</option>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->role_name }}</option>@endforeach</select></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Password <span class="req">*</span></label><div style="position:relative"><input type="password" class="user-input" name="password" id="addPassword" placeholder="Min. 6 characters" required><button type="button" class="pw-toggle" data-target="addPassword"><i class="fas fa-eye"></i></button></div></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Confirm Password <span class="req">*</span></label><div style="position:relative"><input type="password" class="user-input" name="password_confirmation" id="addPasswordConfirm" placeholder="Re-enter password" required><button type="button" class="pw-toggle" data-target="addPasswordConfirm"><i class="fas fa-eye"></i></button></div></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Status <span class="req">*</span></label><select class="user-input" name="status" required><option value="active">Active</option><option value="inactive">Inactive</option><option value="locked">Locked</option></select></div></div>
                    </div>
                </div>
                <div class="user-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save"><i class="fas fa-floppy-disk"></i> Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content user-modal">
            <div class="user-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-user-pen"></i></div>
                    <div><h5 class="user-modal-title">Edit User</h5><p style="font-size:12px;color:var(--muted);margin:0">Update user information</p></div>
                </div>
                <button type="button" class="user-modal-close" data-bs-dismiss="modal"><i class="fas fa-xmark"></i></button>
            </div>
            <form id="editUserForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editUserId">
                <div class="user-modal-body">
                    <div style="display:flex;justify-content:center;margin-bottom:20px">
                        <div class="photo-wrap">
                            <div id="editPhotoPlaceholder" class="photo-placeholder"><i class="fas fa-user"></i></div>
                            <img id="editPhotoImg" src="" style="width:90px;height:90px;object-fit:cover;border-radius:50%;display:none;position:absolute;top:0;left:0" alt="">
                            <label class="photo-cam-btn"><i class="fas fa-camera"></i><input type="file" name="profile_photo" id="editPhotoInput" accept="image/*" hidden></label>
                        </div>
                    </div>
                    <div class="form-section-label">Personal Information</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Full Name <span class="req">*</span></label><input type="text" class="user-input" id="editFullName" name="full_name" required></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Contact Number</label><input type="text" class="user-input" id="editContact" name="contact_number"></div></div>
                        <div class="col-12"><div class="user-field"><label class="user-label">Email Address</label><input type="email" class="user-input" id="editEmail" name="email"></div></div>
                    </div>
                    <div class="form-section-label">Account Settings</div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Username <span class="req">*</span></label><div style="position:relative"><span class="at-sign">@</span><input type="text" class="user-input" id="editUsername" name="username" style="padding-left:28px" required></div></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Role <span class="req">*</span></label><select class="user-input" id="editRoleId" name="role_id" required><option value="">--- Select Role ---</option>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->role_name }}</option>@endforeach</select></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">New Password <small style="color:var(--muted)">(leave blank to keep)</small></label><div style="position:relative"><input type="password" class="user-input" name="password" id="editPassword" placeholder="Min. 6 characters"><button type="button" class="pw-toggle" data-target="editPassword"><i class="fas fa-eye"></i></button></div></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Confirm New Password</label><div style="position:relative"><input type="password" class="user-input" name="password_confirmation" id="editPasswordConfirm" placeholder="Re-enter new password"><button type="button" class="pw-toggle" data-target="editPasswordConfirm"><i class="fas fa-eye"></i></button></div></div></div>
                        <div class="col-md-6"><div class="user-field"><label class="user-label">Status <span class="req">*</span></label><select class="user-input" id="editStatus" name="status" required><option value="active">Active</option><option value="inactive">Inactive</option><option value="locked">Locked</option></select></div></div>
                    </div>
                </div>
                <div class="user-modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save"><i class="fas fa-floppy-disk"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content user-modal">
            <div class="user-modal-header">
                <div style="display:flex;align-items:center;gap:12px">
                    <div class="modal-icon-wrap"><i class="fas fa-id-card"></i></div>
                    <div><h5 class="user-modal-title">User Profile</h5><p style="font-size:12px;color:var(--muted);margin:0" id="viewUserSubtitle">Loading...</p></div>
                </div>
                <button type="button" class="user-modal-close" data-bs-dismiss="modal"><i class="fas fa-xmark"></i></button>
            </div>
            <div class="user-modal-body" id="viewUserBody">
                <div style="text-align:center;padding:40px 0;color:var(--muted)">
                    <i class="fas fa-spinner fa-spin" style="font-size:24px"></i>
                    <div style="margin-top:10px;font-size:13px">Loading...</div>
                </div>
            </div>
            <div class="user-modal-footer"><button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<style>
.stat-strip{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:22px}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:16px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 10px rgba(15,22,35,.05);transition:box-shadow var(--dur) var(--ease),transform var(--dur) var(--ease)}
.stat-card:hover{box-shadow:0 6px 22px rgba(15,22,35,.1);transform:translateY(-2px)}
.stat-icon{width:42px;height:42px;border-radius:11px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px}
.stat-val{font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text);line-height:1}
.stat-lbl{font-size:11.5px;color:var(--muted);margin-top:3px;font-weight:500}
.btn-add-user{display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border-radius:10px;border:none;cursor:pointer;background:var(--primary);color:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;transition:all var(--dur) var(--ease);box-shadow:0 4px 14px rgba(79,99,210,.35)}
.btn-add-user:hover{background:#3d4fc0;transform:translateY(-1px)}
.user-search-wrap{position:relative;display:flex;align-items:center}
.user-search-ico{position:absolute;left:12px;color:var(--muted);font-size:13px;pointer-events:none}
.user-search{padding:8px 14px 8px 36px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);width:260px;outline:none;transition:border-color var(--dur)}
.user-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.user-filter{padding:8px 14px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);outline:none;cursor:pointer}
#usersTable thead th{font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);background:#f8f9fd;padding:13px 16px;border-bottom:1px solid var(--border)}
#usersTable thead th.sortable{cursor:pointer;user-select:none}
#usersTable thead th.sortable:hover{background:#eef0fd;color:var(--primary)}
.sort-icon{margin-left:5px;font-size:10px;opacity:.3}
th.sorting-asc .sort-icon,th.sorting-desc .sort-icon{opacity:1;color:var(--primary)}
#usersTable tbody td{padding:14px 16px;font-size:13.5px;border-bottom:1px solid var(--border);vertical-align:middle}
#usersTable tbody tr:last-child td{border-bottom:none}
#usersTable tbody tr:hover{background:#f8f9fd}
.user-avatar{width:38px;height:38px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:15px}
.username-pill{font-size:12px;background:#f0f2f8;color:#4f63d2;padding:3px 10px;border-radius:6px;font-weight:600;font-family:monospace}
.role-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:100px;font-size:12px;font-weight:600}
.role-admin{background:#eef0fd;color:#4f63d2}
.role-staff{background:#e6faf3;color:#1cc88a}
.role-superadmin{background:#fff0f3;color:#ff4d6d}
.role-user{background:#f0f2f8;color:#7c5cbf}
.status-badge{display:inline-flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;padding:4px 11px;border-radius:100px}
.status-active{background:#e6faf3;color:#1cc88a}
.status-inactive{background:#fff8e6;color:#f4a20a}
.status-locked{background:#fff0f3;color:#ff4d6d}
.status-dot{width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0}
.action-group{display:flex;align-items:center;justify-content:flex-end;gap:6px}
.action-btn{width:32px;height:32px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--muted);display:flex;align-items:center;justify-content:center;font-size:13px;cursor:pointer;transition:all var(--dur) var(--ease)}
.action-btn:hover{color:#4f63d2;border-color:#4f63d2;background:var(--plt)}
.action-btn-edit:hover{color:#f4a20a;border-color:#f4a20a;background:#fff8e6}
.action-btn-delete:hover{color:var(--danger);border-color:var(--danger);background:#fff0f3}
.empty-state{text-align:center;padding:48px 16px;color:var(--muted);font-size:14px}
.user-modal{border:none;border-radius:18px;overflow:hidden;box-shadow:0 24px 64px rgba(15,22,35,.22)}
.user-modal-header{display:flex;align-items:center;justify-content:space-between;padding:22px 28px;border-bottom:1px solid var(--border);background:#fff}
.modal-icon-wrap{width:42px;height:42px;border-radius:12px;background:var(--plt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:17px}
.user-modal-title{font-family:'Syne',sans-serif;font-weight:800;font-size:17px;color:var(--text);margin:0}
.user-modal-close{width:34px;height:34px;border-radius:9px;border:1px solid var(--border);background:none;color:var(--muted);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:all var(--dur)}
.user-modal-close:hover{background:var(--bg);color:var(--text)}
.user-modal-body{padding:24px 28px;max-height:70vh;overflow-y:auto}
.user-modal-footer{padding:18px 28px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:flex-end;gap:10px;background:#fafbff}
.photo-wrap{width:90px;height:90px;border-radius:50%;background:#f0f2f8;border:3px dashed var(--border);position:relative;display:flex;align-items:center;justify-content:center;overflow:visible}
.photo-placeholder{color:var(--muted);font-size:28px}
.photo-cam-btn{position:absolute;bottom:-4px;right:-4px;width:28px;height:28px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;box-shadow:0 2px 8px rgba(79,99,210,.4)}
.at-sign{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;pointer-events:none}
.form-section-label{font-size:10.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;display:flex;align-items:center;gap:8px}
.form-section-label::after{content:'';flex:1;height:1px;background:var(--border)}
.user-field{display:flex;flex-direction:column;gap:6px}
.user-label{font-size:12.5px;font-weight:600;color:var(--text)}
.req{color:var(--danger)}
.user-input{padding:9px 13px;border:1px solid var(--border);border-radius:9px;background:var(--bg);font-family:'DM Sans',sans-serif;font-size:13.5px;color:var(--text);outline:none;width:100%;transition:border-color var(--dur),box-shadow var(--dur),background var(--dur)}
.user-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,99,210,.12);background:#fff}
.pw-toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;font-size:13px;transition:color var(--dur)}
.pw-toggle:hover{color:var(--primary)}
.btn-cancel{padding:9px 20px;border-radius:9px;border:1px solid var(--border);background:#fff;font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:var(--muted);cursor:pointer}
.btn-cancel:hover{border-color:var(--text);color:var(--text)}
.btn-save{display:inline-flex;align-items:center;gap:8px;padding:9px 22px;border-radius:9px;border:none;background:var(--primary);font-family:'DM Sans',sans-serif;font-size:13.5px;font-weight:600;color:#fff;cursor:pointer;box-shadow:0 4px 14px rgba(79,99,210,.35);transition:all var(--dur)}
.btn-save:hover{background:#3d4fc0;transform:translateY(-1px)}
.pagination .page-item .page-link{border-radius:8px!important;font-size:13px;font-weight:500;border:1px solid var(--border);color:var(--muted);margin:0 2px}
.pagination .page-item.active .page-link{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff}
.pagination .page-item .page-link:hover{border-color:var(--primary);color:var(--primary);background:var(--plt)}
#paginationLinks nav{display:flex;justify-content:flex-end}
.v-row{display:flex;gap:12px;padding:11px 0;border-bottom:1px solid var(--border)}
.v-row:last-child{border-bottom:none}
.v-lbl{font-size:11.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--muted);min-width:110px;flex-shrink:0;padding-top:2px}
.v-val{font-size:13.5px;color:var(--text);font-weight:500}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let usersData = [], filteredData = [];
    let currentSort = { column: 'name', direction: 'asc' };
    let currentPage = 1, perPage = 10;

    @if(isset($users) && $users->count() > 0)
    @foreach($users as $u)
    usersData.push({
        id: {{ $u->id }},
        name: '{{ addslashes($u->full_name) }}',
        username: '{{ addslashes($u->username) }}',
        email: '{{ addslashes($u->email ?? '') }}',
        role: '{{ addslashes($u->role->role_name ?? '') }}',
        contact: '{{ addslashes($u->contact_number ?? '') }}',
        status: '{{ $u->status }}',
        last_login: '{{ $u->last_login ?? '' }}',
        photo: '{{ $u->profile_photo ? asset("storage/".$u->profile_photo) : "" }}',
        avatar_color: '{{ ["#4f63d2","#1cc88a","#f4a20a","#ff4d6d","#7c5cbf"][crc32($u->full_name)%5] }}',
        avatar_initial: '{{ strtoupper(substr($u->full_name,0,1)) }}',
        is_self: {{ $u->id === Auth::id() ? 'true' : 'false' }}
    });
    @endforeach
    @endif

    filteredData = [...usersData];
    renderTable();

    $('#showEntries').on('change', function () { perPage = parseInt($(this).val()); currentPage = 1; renderTable(); });
    $('#userSearch, #filterStatus, #filterRole').on('input change', applyFilters);

    $(document).on('click', '.sortable', function () {
        let col = $(this).data('sort');
        currentSort.direction = (currentSort.column === col && currentSort.direction === 'asc') ? 'desc' : 'asc';
        currentSort.column = col;
        $('.sortable').removeClass('sorting-asc sorting-desc');
        $(this).addClass('sorting-' + currentSort.direction);
        sortData(); renderTable();
    });

    function applyFilters() {
        let search = $('#userSearch').val().toLowerCase();
        let status = $('#filterStatus').val();
        let role   = $('#filterRole').val().toLowerCase();
        filteredData = usersData.filter(function (i) {
            return (!search || i.name.toLowerCase().includes(search) || i.username.toLowerCase().includes(search) || i.email.toLowerCase().includes(search) || i.role.toLowerCase().includes(search))
                && (!status || i.status === status)
                && (!role   || i.role.toLowerCase().includes(role));
        });
        sortData(); currentPage = 1; renderTable();
    }

    function sortData() {
        filteredData.sort(function (a, b) {
            let va = String(a[currentSort.column]||'').toLowerCase(), vb = String(b[currentSort.column]||'').toLowerCase();
            return va < vb ? (currentSort.direction==='asc'?-1:1) : va > vb ? (currentSort.direction==='asc'?1:-1) : 0;
        });
    }

    function renderTable() {
        let start = (currentPage-1)*perPage, page = filteredData.slice(start, start+perPage), tbody = $('#usersTable tbody');
        tbody.empty();
        if (!page.length) {
            tbody.html('<tr><td colspan="7" class="empty-state"><i class="fas fa-users-slash" style="font-size:36px;color:var(--border);display:block;margin-bottom:10px"></i>No users found.</td></tr>');
        } else {
            page.forEach(function (item) {
                let avatar = item.photo
                    ? `<img src="${item.photo}" style="width:38px;height:38px;border-radius:10px;object-fit:cover" alt="">`
                    : `<div class="user-avatar" style="background:${item.avatar_color}">${item.avatar_initial}</div>`;
                let lastLogin = item.last_login ? new Date(item.last_login).toLocaleDateString('en-PH',{month:'short',day:'2-digit',year:'numeric'}) : 'Never';
                let roleCls   = 'role-'+item.role.toLowerCase().replace(/\s+/g,'-');
                let delBtn    = item.is_self ? '' : `<button class="action-btn action-btn-delete delete-user" data-id="${item.id}" title="Delete"><i class="fas fa-trash-can"></i></button>`;
                tbody.append(`<tr>
                    <td><div style="display:flex;align-items:center;gap:10px">${avatar}<div>
                        <div style="font-weight:600;font-size:13.5px;color:var(--text)">${item.name}</div>
                        <div style="font-size:11.5px;color:var(--muted)">${item.email||'---'}</div>
                    </div></div></td>
                    <td><span class="username-pill">@${item.username}</span></td>
                    <td><span class="role-badge ${roleCls}"><i class="fas fa-shield-halved"></i> ${item.role||'---'}</span></td>
                    <td style="font-size:13px;color:var(--muted)">${item.contact||'---'}</td>
                    <td style="font-size:13px;color:var(--muted)">${lastLogin}</td>
                    <td><span class="status-badge status-${item.status}"><span class="status-dot"></span>${item.status.charAt(0).toUpperCase()+item.status.slice(1)}</span></td>
                    <td><div class="action-group">
                        <button class="action-btn view-user" data-id="${item.id}" title="View"><i class="fas fa-eye"></i></button>
                        <button class="action-btn action-btn-edit edit-user" data-id="${item.id}" title="Edit"><i class="fas fa-pen-to-square"></i></button>
                        ${delBtn}
                    </div></td>
                </tr>`);
            });
        }
        updatePagination();
        let total=filteredData.length, first=total?start+1:0, last=Math.min(start+perPage,total);
        $('#tableInfo').text(`Showing ${first} to ${last} of ${total} entries`);
    }

    function updatePagination() {
        let tp=Math.ceil(filteredData.length/perPage), pag=$('#paginationLinks');
        if (tp<=1){pag.empty();return;}
        let html='<nav><ul class="pagination">';
        html+=`<li class="page-item ${currentPage===1?'disabled':''}"><a class="page-link" href="#" data-page="${currentPage-1}">Previous</a></li>`;
        let s=Math.max(1,currentPage-2),e=Math.min(tp,s+4);
        if(s>1){html+=`<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;if(s>2)html+=`<li class="page-item disabled"><span class="page-link">...</span></li>`;}
        for(let i=s;i<=e;i++)html+=`<li class="page-item ${i===currentPage?'active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        if(e<tp){if(e<tp-1)html+=`<li class="page-item disabled"><span class="page-link">...</span></li>`;html+=`<li class="page-item"><a class="page-link" href="#" data-page="${tp}">${tp}</a></li>`;}
        html+=`<li class="page-item ${currentPage===tp?'disabled':''}"><a class="page-link" href="#" data-page="${currentPage+1}">Next</a></li></ul></nav>`;
        pag.html(html);
        pag.find('.page-link').on('click',function(e){e.preventDefault();let p=parseInt($(this).data('page'));if(p&&p!==currentPage&&p>=1&&p<=tp){currentPage=p;renderTable();}});
    }

    function photoPreview(inputId, imgId, placeholderId) {
        $('#'+inputId).on('change',function(){
            if(!this.files[0])return;
            let r=new FileReader();
            r.onload=function(e){$('#'+placeholderId).hide();$('#'+imgId).attr('src',e.target.result).show();};
            r.readAsDataURL(this.files[0]);
        });
    }
    photoPreview('addPhotoInput','addPhotoImg','addPhotoPlaceholder');
    photoPreview('editPhotoInput','editPhotoImg','editPhotoPlaceholder');

    $(document).on('click','.pw-toggle',function(){
        let input=$('#'+$(this).data('target'));
        input.attr('type',input.attr('type')==='password'?'text':'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    /* ADD */
    $('#addUserForm').on('submit',function(e){
        e.preventDefault();
        let $btn=$(this).find('.btn-save').prop('disabled',true).html('<i class="fas fa-spinner fa-spin me-2"></i>Creating...');
        $.ajax({
            url:'{{ route("users.store") }}',type:'POST',data:new FormData(this),processData:false,contentType:false,
            success:function(res){
                if(res.success){
                    $('#addUserModal').modal('hide');$('#addUserForm')[0].reset();
                    Swal.fire({icon:'success',title:'User Created!',text:res.message,timer:2000,showConfirmButton:false,toast:true,position:'top-end'})
                        .then(function(){location.reload();});
                }
            },
            error:function(xhr){
                let e=xhr.responseJSON?.errors,msg=e?Object.values(e).flat().join('\n'):(xhr.responseJSON?.message||'Something went wrong.');
                Swal.fire('Error',msg,'error');
            },
            complete:function(){$btn.prop('disabled',false).html('<i class="fas fa-floppy-disk"></i> Create User');}
        });
    });
    $('#addUserModal').on('hidden.bs.modal',function(){$('#addUserForm')[0].reset();$('#addPhotoImg').hide();$('#addPhotoPlaceholder').show();});

    /* VIEW */
    $(document).on('click','.view-user',function(){
        let id=$(this).data('id');
        $('#viewUserSubtitle').text('Loading...');
        $('#viewUserBody').html('<div style="text-align:center;padding:40px 0;color:var(--muted)"><i class="fas fa-spinner fa-spin" style="font-size:24px"></i><div style="margin-top:10px;font-size:13px">Loading...</div></div>');
        $('#viewUserModal').modal('show');
        $.ajax({
            url:'{{ url("users") }}/'+id,type:'GET',
            success:function(res){
                if(!res.success)return;
                let u=res.data,roleName=u.role?.role_name||'---',roleCls='role-'+roleName.toLowerCase();
                let avatar=u.profile_photo
                    ?`<img src="{{ asset('storage') }}/${u.profile_photo}" style="width:72px;height:72px;border-radius:50%;object-fit:cover" alt="">`
                    :`<div style="width:72px;height:72px;border-radius:50%;background:#4f63d2;display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Syne',sans-serif;font-weight:800;font-size:26px">${(u.full_name||'?').charAt(0).toUpperCase()}</div>`;
                $('#viewUserSubtitle').text('@'+u.username);
                $('#viewUserBody').html(`
                    <div style="display:flex;align-items:center;gap:16px;padding-bottom:18px;border-bottom:1px solid var(--border);margin-bottom:4px">
                        ${avatar}
                        <div><div style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--text)">${u.full_name}</div>
                        <span class="role-badge ${roleCls}" style="margin-top:6px;display:inline-flex"><i class="fas fa-shield-halved"></i> ${roleName}</span></div>
                    </div>
                    <div class="v-row"><span class="v-lbl">Username</span><span class="v-val"><span class="username-pill">@${u.username}</span></span></div>
                    <div class="v-row"><span class="v-lbl">Email</span><span class="v-val">${u.email||'---'}</span></div>
                    <div class="v-row"><span class="v-lbl">Contact</span><span class="v-val">${u.contact_number||'---'}</span></div>
                    <div class="v-row"><span class="v-lbl">Status</span><span class="v-val"><span class="status-badge status-${u.status}"><span class="status-dot"></span>${u.status.charAt(0).toUpperCase()+u.status.slice(1)}</span></span></div>
                    <div class="v-row"><span class="v-lbl">Last Login</span><span class="v-val">${u.last_login?new Date(u.last_login).toLocaleString():'Never'}</span></div>
                    <div class="v-row"><span class="v-lbl">Joined</span><span class="v-val">${new Date(u.created_at).toLocaleDateString('en-PH',{month:'long',day:'2-digit',year:'numeric'})}</span></div>`);
            },
            error:function(){$('#viewUserBody').html('<div style="text-align:center;padding:30px;color:var(--danger)"><i class="fas fa-circle-exclamation" style="font-size:24px;display:block;margin-bottom:8px"></i>Failed to load user details.</div>');}
        });
    });

    /* EDIT */
    $(document).on('click','.edit-user',function(){
        let id=$(this).data('id');
        $.ajax({
            url:'{{ url("users") }}/'+id+'/edit',type:'GET',
            success:function(res){
                if(!res.success)return;
                let u=res.data;
                $('#editUserId').val(u.id);$('#editFullName').val(u.full_name);$('#editContact').val(u.contact_number||'');
                $('#editEmail').val(u.email||'');$('#editUsername').val(u.username);$('#editRoleId').val(u.role_id);
                $('#editStatus').val(u.status);$('#editPassword,#editPasswordConfirm').val('');
                if(u.profile_photo){$('#editPhotoImg').attr('src','{{ asset("storage") }}/'+u.profile_photo).show();$('#editPhotoPlaceholder').hide();}
                else{$('#editPhotoImg').hide();$('#editPhotoPlaceholder').show();}
                $('#editUserModal').modal('show');
            },
            error:function(){Swal.fire('Error','Could not load user data.','error');}
        });
    });

    $('#editUserForm').on('submit',function(e){
        e.preventDefault();
        let id=$('#editUserId').val();
        let $btn=$(this).find('.btn-save').prop('disabled',true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        $.ajax({
            url:'{{ url("users") }}/'+id,type:'POST',data:new FormData(this),processData:false,contentType:false,
            success:function(res){
                if(res.success){
                    $('#editUserModal').modal('hide');
                    Swal.fire({icon:'success',title:'Updated!',text:res.message,timer:2000,showConfirmButton:false,toast:true,position:'top-end'})
                        .then(function(){location.reload();});
                }
            },
            error:function(xhr){
                let e=xhr.responseJSON?.errors,msg=e?Object.values(e).flat().join('\n'):(xhr.responseJSON?.message||'Something went wrong.');
                Swal.fire('Error',msg,'error');
            },
            complete:function(){$btn.prop('disabled',false).html('<i class="fas fa-floppy-disk"></i> Save Changes');}
        });
    });

    /* DELETE */
    $(document).on('click','.delete-user',function(){
        let id=$(this).data('id'),url='{{ url("users") }}/'+id;
        Swal.fire({title:'Delete User?',text:'This account will be permanently removed.',icon:'warning',showCancelButton:true,
            confirmButtonColor:'#ff4d6d',cancelButtonColor:'#b0b7cc',confirmButtonText:'Yes, delete!',cancelButtonText:'Cancel'
        }).then(function(result){
            if(result.isConfirmed){
                $.ajax({
                    url:url,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                    success:function(res){
                        if(res.success){
                            Swal.fire({icon:'success',title:'Deleted!',text:res.message,timer:2000,showConfirmButton:false,toast:true,position:'top-end'})
                                .then(function(){location.reload();});
                        } else {
                            Swal.fire('Cannot Delete',res.message,'warning');
                        }
                    },
                    error:function(xhr){Swal.fire('Error',xhr.responseJSON?.message||'Something went wrong.','error');}
                });
            }
        });
    });
});
</script>
@endpush