<div>
    <div class="pd-20 card-box mb-30">
        <div class="row mb-20">filters here....</div>
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
