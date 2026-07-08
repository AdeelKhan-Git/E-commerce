@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')
@section('page-subtitle', 'Manage all registered user accounts.')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search users..."
                    value="{{ $search }}">

                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-search"></i>
                </button>

                @if ($search)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </form>

            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Search by <strong>username</strong>, <strong>email address</strong>,
                <strong>phone number</strong>, or <strong>city</strong>.
            </small>
        </div>

        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> Add User
        </button>

    </div>

    {{-- Metric --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <i class="bi bi-people metric-icon"></i>
                <div class="metric-label">Total Users</div>
                <div class="metric-value">{{ $users->total() }}</div>
                <small>registered accounts</small>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="panel">
        <div class="panel-title"><i class="bi bi-table me-2"></i>User List</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Designation</th>
                        <th>Admin</th>
                        <th>Joined</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><strong>{{ $user->username }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number ?? '—' }}</td>
                            <td>{{ $user->city ?? '—' }}</td>
                            <td>{{ $user->designation ?? '—' }}</td>
                            <td>
                                @if ($user->is_admin)
                                    <span class="badge bg-success">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at?->format('M d, Y') }}</td>
                            <td class="text-end">
                                <button class="btn btn-light btn-action" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal" data-id="{{ $user->id }}"
                                    data-username="{{ $user->username }}" data-email="{{ $user->email }}"
                                    data-phone="{{ $user->phone_number }}" data-city="{{ $user->city }}"
                                    data-designation="{{ $user->designation }}"
                                    data-is-admin="{{ $user->is_admin ? '1' : '0' }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-action delete-user-btn"
                                        data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                        data-url="{{ route('admin.users.destroy', $user) }}"
                                        data-name="{{ $user->username }}">

                                        <i class="bi bi-trash"></i> Delete

                                    </button>

                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    </div>

    {{-- Add User Modal --}}
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Username</label><input type="text"
                                class="form-control" name="username"></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="text"
                                class="form-control" name="email"></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password"
                                class="form-control" name="password"></div>
                        <div class="mb-3"><label class="form-label">Phone</label><input type="text"
                                class="form-control" name="phone_number"></div>
                        <div class="mb-3"><label class="form-label">City</label><input type="text" class="form-control"
                                name="city"></div>
                        <div class="mb-3"><label class="form-label">Designation</label><input type="text"
                                class="form-control" name="designation"></div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_admin" value="1"
                                id="addIsAdmin">
                            <label class="form-check-label" for="addIsAdmin">Is Admin</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Add
                            User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editUserForm">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Username</label><input type="text"
                                class="form-control" name="username" id="editUsername"></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="text"
                                class="form-control" name="email" id="editEmail"></div>
                        <div class="mb-3"><label class="form-label">New Password <small class="text-muted">(leave blank
                                    to keep)</small></label><input type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-3"><label class="form-label">Phone</label><input type="text"
                                class="form-control" name="phone_number" id="editPhone"></div>
                        <div class="mb-3"><label class="form-label">City</label><input type="text"
                                class="form-control" name="city" id="editCity"></div>
                        <div class="mb-3"><label class="form-label">Designation</label><input type="text"
                                class="form-control" name="designation" id="editDesignation"></div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_admin" value="1"
                                id="editIsAdmin">
                            <label class="form-check-label" for="editIsAdmin">Is Admin</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-body text-center p-5">

                    <div
                        style="
                    width:90px;
                    height:90px;
                    margin:auto;
                    border-radius:50%;
                    background:#ffe8e8;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                ">
                        <i class="bi bi-person-x-fill text-danger" style="font-size:42px;"></i>
                    </div>

                    <h3 class="fw-bold mt-4">
                        Delete User?
                    </h3>

                    <p class="text-muted mt-3 mb-2">
                        Are you sure you want to permanently delete
                    </p>

                    <h5 id="deleteUserName" class="fw-bold text-dark mb-4">
                    </h5>

                    <p class="text-danger small">
                        This action cannot be undone.
                    </p>

                    <div class="d-flex gap-3 mt-4">

                        <button class="btn btn-light border w-50 py-2" data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <form id="deleteUserForm" method="POST" class="w-50">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger w-100 py-2">

                                <i class="bi bi-trash me-2"></i>

                                Delete

                            </button>

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('editUserModal').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('editUserForm').action = '/admin/users/' + btn.dataset.id;
            document.getElementById('editUsername').value = btn.dataset.username;
            document.getElementById('editEmail').value = btn.dataset.email;
            document.getElementById('editPhone').value = btn.dataset.phone;
            document.getElementById('editCity').value = btn.dataset.city;
            document.getElementById('editDesignation').value = btn.dataset.designation;
            document.getElementById('editIsAdmin').checked = btn.dataset.isAdmin === '1';
        });
        document.querySelectorAll('.delete-user-btn').forEach(button => {

            button.addEventListener('click', function() {

                document.getElementById('deleteUserForm').action =
                    this.dataset.url;

                document.getElementById('deleteUserName').textContent =
                    this.dataset.name;

            });

        });
    </script>
@endpush
