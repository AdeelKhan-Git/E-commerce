@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage your product catalog and images.')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex gap-2 flex-wrap">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products by name..."
                value="{{ $search }}">
            <select name="category_id" class="form-select form-select-sm" style="width:auto">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->category_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            @if ($search || $category_id)
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-danger"><i
                        class="bi bi-x"></i></a>
            @endif
        </form>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-lg"></i> Add Product
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <i class="bi bi-box-seam metric-icon"></i>
                <div class="metric-label">Total Products</div>
                <div class="metric-value">{{ $products->total() }}</div>
                <small>in catalog</small>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title"><i class="bi bi-table me-2"></i>Product List</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Hot</th>
                        <th>Active</th>
                        <th>Images</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($product->primaryImage)
                                        <img src="{{ asset('uploads/' . $product->primaryImage->file_name) }}"
                                            style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div
                                            style="width:40px;height:40px;border-radius:6px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $product->product_name }}</strong>
                                        <div class="text-muted small">{{ Str::limit($product->_description, 35) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category?->category_name ?? '—' }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if ($product->ishot)
                                    <span class="badge bg-danger">Hot</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if ($product->isactive)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-outline-primary btn-action" data-bs-toggle="modal"
                                    data-bs-target="#attachmentModal" data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->product_name }}">
                                    <i class="bi bi-images"></i> {{ optional($product->productattachments)->count() ?? 0 }}
                                </button>
                            </td>
                            <td>{{ $product->created_at?->format('M d, Y') }}</td>
                            <td class="text-end">
                                <button class="btn btn-light btn-action" data-bs-toggle="modal"
                                    data-bs-target="#editProductModal" data-id="{{ $product->id }}"
                                    data-name="{{ $product->product_name }}" data-desc="{{ $product->_description }}"
                                    data-price="{{ $product->price }}" data-category="{{ $product->category_id }}"
                                    data-ishot="{{ $product->ishot ? '1' : '0' }}"
                                    data-isactive="{{ $product->isactive ? '1' : '0' }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    class="d-inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-action"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $products->links() }}</div>
    </div>

    {{-- Add Product Modal --}}
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Product</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.products.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12"><label class="form-label">Product Name</label><input type="text"
                                    class="form-control" name="product_name"></div>
                            <div class="col-md-6"><label class="form-label">Price</label><input type="number"
                                    class="form-control" name="price"step="0.01" min="0" placeholder="0.00"></div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category_id">
                                    <option value="">— Select Category —</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label">Description</label>
                                <textarea class="form-control" name="_description" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="ishot" value="1"
                                        id="addIshot">
                                    <label class="form-check-label" for="addIshot">Mark as Hot</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="isactive" value="0"
                                        id="addIsactive">
                                    <label class="form-check-label" for="addIsactive">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add
                            Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Product Modal --}}
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Product</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editProductForm">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12"><label class="form-label">Product Name</label><input type="text"
                                    class="form-control" name="product_name" id="editProdName"></div>
                            <div class="col-md-6"><label class="form-label">Price</label><input type="text"
                                    class="form-control" name="price" id="editProdPrice"></div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category_id" id="editProdCategory">
                                    <option value="">— Select Category —</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label">Description</label>
                                <textarea class="form-control" name="_description" id="editProdDesc" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="ishot" value="1"
                                        id="editIshot">
                                    <label class="form-check-label" for="editIshot">Mark as Hot</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="isactive" value="1"
                                        id="editIsactive">
                                    <label class="form-check-label" for="editIsactive">Active</label>
                                </div>
                            </div>
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

    {{-- Attachment Modal --}}
    <div class="modal fade" id="attachmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-images me-2"></i>Images — <span id="attProductName"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Upload Form --}}
                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="row g-2 align-items-end mb-4">
                            <div class="col">
                                <label class="form-label">Upload Image <small class="text-muted">(jpg, png, gif, webp —
                                        max 2MB)</small></label>
                                <input type="file" class="form-control" name="image"
                                    accept=".jpg,.jpeg,.png,.gif,.webp">
                            </div>
                            <div class="col-auto">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_primary" value="1"
                                        id="isPrimary">
                                    <label class="form-check-label" for="isPrimary">Set as primary</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100"><i
                                        class="bi bi-upload me-1"></i>Upload</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    {{-- Product Images --}}
                    @foreach ($products as $product)
                        <div class="d-none" id="attGrid_{{ $product->id }}">
                            @if ($product->productattachments->isEmpty())
                                <p class="text-muted small">No images uploaded yet.</p>
                            @else
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($product->productattachments as $att)
                                        <div class="text-center">
                                            <img src="{{ asset('uploads/' . $att->file_name) }}"
                                                style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:2px solid {{ $att->is_primary ? '#28a745' : '#dee2e6' }}">
                                            @if ($att->is_primary)
                                                <div><span class="badge bg-success mt-1">Primary</span></div>
                                            @endif
                                            <div class="d-flex gap-1 mt-1">
                                                @if (!$att->is_primary)
                                                    <form method="POST"
                                                        action="{{ route('admin.products.attachments.primary', [$product, $att]) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm"
                                                            style="font-size:10px;">
                                                            <i class="bi bi-star"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST"
                                                    action="{{ route('admin.products.attachments.destroy', $att) }}"
                                                    onsubmit="return confirm('Delete image?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        style="font-size:10px;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('editProductModal').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('editProductForm').action = '/admin/products/' + btn.dataset.id;
            document.getElementById('editProdName').value = btn.dataset.name;
            document.getElementById('editProdDesc').value = btn.dataset.desc;
            document.getElementById('editProdPrice').value = btn.dataset.price;
            document.getElementById('editProdCategory').value = btn.dataset.category;
            document.getElementById('editIshot').checked = btn.dataset.ishot === '1';
            document.getElementById('editIsactive').checked = btn.dataset.isactive === '1';
        });

        const attModal = document.getElementById('attachmentModal');
        attModal.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            const pid = btn.dataset.productId;
            document.getElementById('attProductName').textContent = btn.dataset.productName;
            document.getElementById('uploadForm').action = '/admin/products/' + pid + '/attachments';
            document.querySelectorAll('[id^="attGrid_"]').forEach(g => g.classList.add('d-none'));
            const grid = document.getElementById('attGrid_' + pid);
            if (grid) grid.classList.remove('d-none');
        });
    </script>
@endpush
