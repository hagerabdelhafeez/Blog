<div>
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a wire:click="selectTab('general_settings')"
                    class="nav-link {{ $tab == 'general_settings' ? 'active' : '' }}" data-toggle="tab"
                    href="#general_settings" role="tab" aria-selected="true">General Settings</a>
            </li>
            <li class="nav-item">
                <a wire:click="selectTab('logo_favicon')" class="nav-link  {{ $tab == 'logo_favicon' ? 'active' : '' }}"
                    data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">Logo &
                    Favicon</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade {{ $tab == 'general_settings' ? 'active show' : '' }}" id="general_settings"
                role="tabpanel">
                <div class="pd-20">
                    <form wire:submit='updateSiteInfo()'>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Site title</b></label>
                                    <input type="text" class="form-control" wire:model='site_title'
                                        placeholder="Enter site title">
                                    @error('site_title')
                                        <span class="text-danger ml-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Site email</b></label>
                                    <input type="text" class="form-control" wire:model='site_email'
                                        placeholder="Enter site email">
                                    @error('site_email')
                                        <span class="text-danger ml-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Site phone number</b> <small>(Optional)</small></label>
                                    <input type="text" class="form-control" wire:model='site_phone'
                                        placeholder="Enter site contact number">
                                    @error('site_phone')
                                        <span class="text-danger ml-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><b>Site Meta keywords</b> <small>(Optional)</small></label>
                                    <input type="text" class="form-control" wire:model='site_meta_keywords'
                                        placeholder="Eg: ecommerce, free api, laravel">
                                    @error('site_meta_keywords')
                                        <span class="text-danger ml-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><b>Site Meta Description</b> <small>(Optional)</small></label>
                            <textarea class="form-control" wire:model='site_meta_description' cols="4" rows="4"
                                placeholder="Type site meta description ..."></textarea>
                            @error('site_meta_description')
                                <span class="text-danger ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade {{ $tab == 'logo_favicon' ? 'active show' : '' }}" id="logo_favicon"
                role="tabpanel">
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('admin.update_logo') }}" method="POST" enctype="multipart/form-data"
                                id="updateLogoForm">
                                @csrf
                                <div class="form-group">
                                    <label><b>Site logo</b></label>
                                    <input type="file" class="form-control" id="" name="site_logo">
                                    <span class="text-danger ml-1"></span>
                                </div>
                                <button type="submit" class="btn btn-primary">Change Logo</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('admin.update_favicon') }}" method="POST" enctype="multipart/form-data"
                            id="updateFaviconForm">
                            @csrf
                            <div class="form-group">
                                <label><b>Site Favicon</b></label>
                                <input type="file" class="form-control" id="" name="site_favicon">
                                <span class="text-danger ml-1"></span>
                            </div>
                            <button type="submit" class="btn btn-primary">Change Favicon</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
