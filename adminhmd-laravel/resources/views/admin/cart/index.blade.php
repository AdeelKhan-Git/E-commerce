@extends('layouts.admin')
@section('title', 'Cart')
@section('page-title', 'Cart')
@section('page-subtitle', 'View and manage active customer cart items.')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('admin.cart.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by user or product..."
                value="{{ $search }}">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            @if ($search)
                <a href="{{ route('admin.cart.index') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i></a>
            @endif
        </form>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCartModal">
            <i class="bi bi-cart-plus"></i> Add to Cart
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <i class="bi bi-cart3 metric-icon"></i>
                <div class="metric-label">Cart Items</div>
                <div class="metric-value">{{ $total }}</div>
                <small>total items in carts</small>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title"><i class="bi bi-table me-2"></i>Cart Items</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Added On</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carts as $cart)
                        <tr>
                            <td>{{ $cart->id }}</td>
                            <td>
                                <strong>{{ $cart->user?->username ?? '—' }}</strong>
                                <div class="text-muted small">{{ $cart->user?->email }}</div>
                            </td>
                            <td>{{ $cart->product?->product_name ?? '—' }}</td>
                            <td>${{ number_format($cart->product?->price ?? 0, 2) }}</td>
                            <td>
                                <button class="btn btn-light btn-action" data-bs-toggle="modal"
                                    data-bs-target="#editCartModal" data-id="{{ $cart->id }}"
                                    data-quantity="{{ $cart->quantity }}">
                                    {{ $cart->quantity }} <i class="bi bi-pencil ms-1"></i>
                                </button>
                            </td>
                            <td>${{ number_format(($cart->product?->price ?? 0) * $cart->quantity, 2) }}</td>
                            <td>{{ $cart->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.cart.destroy', $cart) }}" class="d-inline"
                                    onsubmit="return confirm('Remove this cart item?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action"><i class="bi bi-trash"></i>
                                        Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No cart items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $carts->links() }}</div>
    </div>

    {{-- Add Cart Modal --}}
    <div class="modal fade" id="addCartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-cart-plus me-2"></i>Add to Cart</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.cart.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Customer</label>
                            <select class="form-select" name="user_id">
                                <option value="">— Select Customer —</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->username }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select class="form-select" name="product_id">
                                <option value="">— Select Product —</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->product_name }} —
                                        ${{ number_format($p->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label">Quantity</label><input type="number"
                                class="form-control" name="quantity" min=1 value=1 placeholder="1"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-cart-plus me-1"></i>Add to
                            Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Cart Modal --}}
    <div class="modal fade" id="editCartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Update Quantity</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editCartForm">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Quantity</label><input type="text"
                                class="form-control" name="quantity" id="editCartQty"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('editCartModal').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('editCartForm').action = '/admin/cart/' + btn.dataset.id;
            document.getElementById('editCartQty').value = btn.dataset.quantity;
        });
    </script>
@endpush
