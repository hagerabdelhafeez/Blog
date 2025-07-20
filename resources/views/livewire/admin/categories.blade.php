<div>
    <div class="row">
        <div class="col-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text-blue">Parent Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addParentCategory()" class="btn btn-primary btn-small">Add P.
                            category</a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-striped table-sm">
                        <thead class="bg-secondary text-white">
                            <th>#</th>
                            <th>Name</th>
                            <th>N. of categories</th>
                            <th>Actions</th>
                        </thead>
                        <tbody id="sortable_parent_categories">
                            @forelse ($pcategories as $item)
                                <tr data-index="{{ $item->id }}" data-ordering="{{ $item->ordering }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->children->count() }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="javascript:;" wire:click="editParentCategory({{ $item->id }})"
                                                class="text-primary mx-2">
                                                <i class="dw dw-edit2"></i>
                                            </a>
                                            <a href="javascript:;"
                                                wire:click="deleteParentCategory({{ $item->id }})"
                                                class="text-danger mx-2">
                                                <i class="dw dw-delete-3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <span class="text-danger">No Items Found!</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-block mt-1 text-center">
                    {{ $pcategories->links('livewire::simple-bootstrap') }}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text-blue">Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addCategory()" class="btn btn-primary btn-small">Add
                            category</a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-striped table-sm">
                        <thead class="bg-secondary text-white">
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent category</th>
                            <th>N. of posts</th>
                            <th>Actions</th>
                        </thead>
                        <tbody id="sortable_categories">
                            @forelse ($categories as $item)
                                <tr data-index="{{ $item->id }}" data-ordering="{{ $item->ordering }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->parent_category?->name ?? '-' }}</td>
                                    <td>-</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="javascript:;" wire:click="editCategory({{ $item->id }})" class="text-primary mx-2">
                                                <i class="dw dw-edit2"></i>
                                            </a>
                                            <a href="javascript:;" wire:click="deleteCategory({{ $item->id }})" class="text-danger mx-2">
                                                <i class="dw dw-delete-3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <span class="text-danger">No Items Found!</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-block mt-1 text-center">
                    {{ $categories->links('livewire::simple-bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    <div wire:ignore.self class="modal fade" id="pcategory_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content"
                wire:submit="{{ $isUpdateParentCategoryMode ? 'updateParentCategory()' : 'createParentCategory()' }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateParentCategoryMode ? 'Update P. Category' : 'Add P. Category' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateParentCategoryMode)
                        <input type="hidden" wire:model="pcategory_id">
                    @endif
                    <div class="form-group">
                        <label for=""><b>Parent category name</b></label>
                        <input type="text" class="form-control" wire:model="pcategory_name"
                            placeholder="Enter parent category name here...">
                        @error('pcategory_name')
                            <span class="text-danger ml-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateParentCategoryMode ? 'Save changes' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="category_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content"
                wire:submit="{{ $isUpdatedCategoryMode ? 'updateCategory()' : 'createCategory()' }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdatedCategoryMode ? 'Update Category' : 'Add Category' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdatedCategoryMode)
                        <input type="hidden" wire:model="category_id">
                    @endif
                    <div class="form-group">
                        <label for=""><b>Parent category</b>:</label>
                        <select wire:model="parent" class="custom-select">
                            <option value="0">Uncategorized</option>
                            @foreach ($pcategories as $items)
                                <option value="{{ $items->id }}">{{ $items->name }}</option>
                            @endforeach
                        </select>
                        @error('parent')
                            <span class="text-danger ml-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for=""><b>Category name</b></label>
                        <input type="text" class="form-control" wire:model="category_name"
                            placeholder="Enter category name here...">
                        @error('category_name')
                            <span class="text-danger ml-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdatedCategoryMode ? 'Save changes' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
