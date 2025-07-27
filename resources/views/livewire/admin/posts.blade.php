<div>
    <div class="pd-20 card-box mb-30">
        <div class="row mb-20">
            <div class="col-md-4">
                <label for="search"><b class="text-secondary">Search</b>:</label>
                <input wire:model.live='search' id="search" type="text" class="form-control" placeholder="Search Posts....">
            </div>
            @if(auth()->user()->type == 'superAdmin')
            <div class="col-md-2">
                <label for="author"><b class="text-secondary">Author</b>:</label>
                <select wire:model.live='author' id="author" class="custom-select form-control">
                    <option value="">No Selected</option>
                    @foreach(App\Models\User::whereHas('posts')->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-2">
                <label for="category"><b class="text-secondary">Category</b>:</label>
                <select wire:model.live='category' id="category" class="custom-select form-control">
                    <option value="">No Selected</option>
                    {!! $categories_html !!}
                </select>
            </div>
            <div class="col-md-2">
                <label for="visibility"><b class="text-secondary">Visibility</b>:</label>
                <select wire:model.live='visibility' id="visibility" class="custom-select form-control">
                    <option value="">No Selected</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort"><b class="text-secondary">Sort By</b>:</label>
                <select wire:model.live='sortBy' id="sort" class="custom-select form-control">
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-auto table-sm">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th scop="col">#ID</th>
                        <th scop="col">Image</th>
                        <th scop="col">Post Title</th>
                        <th scop="col">Author</th>
                        <th scop="col">Category</th>
                        <th scop="col">Visibility</th>
                        <th scop="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $item)
                    <tr>
                        <td scope="row">{{ $item->id }}</td>
                        <td>
                            <a href="">
                                <img src="/storage/posts/resized/resized_{{ $item->featured_image }}" alt="Post Image" class="img-fluid"
                                    width="100">
                            </a>
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->author->name }}</td>
                        <td>{{ $item->post_category->name }}</td>
                        <td>
                            @if($item->visibility == 1)
                            <span class="badge badge-pill badge-success"><i class="icon-copy ti-world"></i> Public</span>
                            @else
                            <span class="badge badge-pill badge-warning"><i class="icon-copy ti-lock"></i> Private</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="" data-color="#265ed7" style="color: rgb(38, 94, 215)">
                                    <i class="icon-copy dw dw-edit2"></i>
                                </a>
                                <a href="" data-color="#e95959" style="color: rgb(233, 89, 89)">
                                    <i class="icon-copy dw dw-delete-3"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-danger">No Post(s) Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="block mt-1">
            {{ $posts->links('livewire::simple-bootstrap') }}
        </div>
    </div>
</div>
