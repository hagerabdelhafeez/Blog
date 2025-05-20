<div>
    <div class="mb-3">
        @if (Session::has('info'))
            <div class="alert alert-info">
                {{ Session::get('info') }}
                <button class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (Session::has('fail'))
        <div class="alert alert-danger">
            {{ Session::get('fail') }}
            <button class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
        <button class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
    </div>
</div>
