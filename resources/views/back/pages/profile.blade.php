@extends('back.layout.pages-layout')
@section('pageTitle', $pageTitle ?? 'Page Title')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <livewire:admin.profile>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#profilePictureFile').kropify({
                    preview: 'image#profilePicturePreview',
                    viewMode: 1,
                    aspectRatio: 1,
                    cancelButtonText: 'Cancel',
                    resetButtonText: 'Reset',
                    cropButtonText: 'Crop & update',
                    processURL: "{{ route('admin.update_profile_picture') }}",
                    maxSize: 2097152, //2MB
                    showLoader: true,
                    animationClass: 'headShake', //headShake, bounceIn, pulse
                    fileName: 'profilePictureFile',
                    success: function(data) {
                        if (data.status == 1) {
                            Livewire.dispatch('updateTopUserInfo', []);
                            Livewire.dispatch('updateProfile', []);
                            Swal.fire({
                                title: data.message,
                                icon: 'success',
                                draggable: true
                            });
                        } else {
                            Swal.fire({
                                title: data.message,
                                icon: 'error',
                                draggable: true
                            });
                        }
                    }
                });
            });
        </script>
    @endpush
