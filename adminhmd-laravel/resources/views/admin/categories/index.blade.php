@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')
@section('page-subtitle', 'Manage product categories.')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search categories..."
                value="{{ $search }}">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            @if ($search)
                <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-danger"><i
                        class="bi bi-x"></i></a>
            @endif
        </form>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg"></i> Add Category
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <i class="bi bi-tags metric-icon"></i>
                <div class="metric-label">Total Categories</div>
                <div class="metric-value">{{ $categories->total() }}</div>
                <small>product categories</small>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title"><i class="bi bi-table me-2"></i>Category List</div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td><strong>{{ $category->category_name }}</strong></td>
                            <td>{{ $category->_description ?? '—' }}</td>
                            <td>
                                @if ($category->category_image)
                                    <img src="{{ asset($category->category_image) }}"
                                        style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $category->created_at?->format('M d, Y') }}</td>
                            <td class="text-end">
                                <button class="btn btn-light btn-action" data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal" data-id="{{ $category->id }}"
                                    data-name="{{ $category->category_name }}" data-desc="{{ $category->_description }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                               <form method="POST"
                                action="{{ route('admin.categories.destroy', $category) }}"
                                class="d-inline deleteCategoryForm">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="button"
                                    class="btn btn-danger btn-action delete-category-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal"
                                    data-url="{{ route('admin.categories.destroy', $category) }}"
                                    data-name="{{ $category->category_name }}">

                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $categories->links() }}</div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Category</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Category Name</label><input type="text"
                                class="form-control" name="category_name"></div>
                        <div class="mb-3"><label class="form-label">Description</label>
                            <textarea class="form-control" name="_description" rows="3"></textarea>
                        </div>
                        <!-- <div class="mb-3"><label class="form-label">Image URL</label><input type="text" class="form-control" name="category_image" placeholder="uploads/..."></div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add
                            Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Category</h5><button type="button"
                        class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editCategoryForm">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Category Name</label><input type="text"
                                class="form-control" name="category_name" id="editCatName"></div>
                        <div class="mb-3"><label class="form-label">Description</label>
                            <textarea class="form-control" name="_description" id="editCatDesc" rows="3"></textarea>
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
      <!-- Delete Product Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
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
                    <i class="bi bi-trash-fill text-danger"
                       style="font-size:42px;"></i>
                </div>

                <h3 class="mt-4 fw-bold">
                    Delete Category?
                </h3>

                <p class="text-muted mt-3 mb-2">
                    Are you sure you want to permanently delete
                </p>

                <h5 id="deleteCategoryName"
                    class="fw-bold text-dark mb-4">
                </h5>

                <p class="text-danger small">
                    This action cannot be undone.
                </p>

                <div class="d-flex gap-3 mt-4">

                    <button
                        class="btn btn-light border w-50 py-2"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <form
                        id="deleteCategoryForm"
                        method="POST"
                        class="w-50">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger w-100 py-2">

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
        document.getElementById('editCategoryModal').addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('editCategoryForm').action = '/admin/categories/' + btn.dataset.id;
            document.getElementById('editCatName').value = btn.dataset.name;
            document.getElementById('editCatDesc').value = btn.dataset.desc;
        });
         document.querySelectorAll('.delete-category-btn').forEach(button => {

        button.addEventListener('click', function () {

            document.getElementById('deleteCategoryForm').action =
                this.dataset.url;

            document.getElementById('deleteCategoryName').textContent =
                this.dataset.name;

        });

});
    </script>
@endpush
