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
                            @forelse ($pcategories as $item )
                            <tr data-index="{{ $item->id }}" data-ordering="{{ $item->ordering }}">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td> - </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="javascript:;" wire:click="editParentCategory({{ $item->id }})" class="text-primary mx-2">
                                            <i class="dw dw-edit2"></i>
                                        </a>
                                        <a href="javascript:;" wire:click="deleteParentCategory({{ $item->id }})" class="text-danger mx-2">
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
            </div>
        </div>
        <div class="col-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text-blue">Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-primary btn-small">Add category</a>
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
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>P. Cat 1</td>
                                <td>any</td>
                                <td>4</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="" class="text-primary mx-2">
                                            <i class="dw dw-edit2"></i>
                                        </a>
                                        <a href="" class="text-danger mx-2">
                                            <i class="dw dw-delete-3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>c
                        </tbody>
                    </table>
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
</div>
