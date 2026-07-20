@extends('admin.layouts.app')
@section('title', 'Settings')
@section('page-title', 'Website Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">General</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Site Name</label><input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">Tagline</label><input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? '' }}"></div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Company Details</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Company Name</label><input type="text" name="company_name" class="form-control" value="{{ $settings['company_name'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">Address</label><textarea name="company_address" class="form-control" rows="2">{{ $settings['company_address'] ?? '' }}</textarea></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">Phone</label><input type="text" name="company_phone" class="form-control" value="{{ $settings['company_phone'] ?? '' }}"></div>
                        <div class="col-6 mb-3"><label class="form-label">WhatsApp</label><input type="text" name="company_whatsapp" class="form-control" value="{{ $settings['company_whatsapp'] ?? '' }}"></div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">Email</label><input type="email" name="company_email" class="form-control" value="{{ $settings['company_email'] ?? '' }}"></div>
                        <div class="col-6 mb-3"><label class="form-label">GST Number</label><input type="text" name="company_gst" class="form-control" value="{{ $settings['company_gst'] ?? '' }}"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Social Media</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Facebook</label><input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">Instagram</label><input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">YouTube</label><input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">LinkedIn</label><input type="url" name="linkedin_url" class="form-control" value="{{ $settings['linkedin_url'] ?? '' }}"></div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">SEO Defaults</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Default Meta Title</label><input type="text" name="default_meta_title" class="form-control" value="{{ $settings['default_meta_title'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">Default Meta Description</label><textarea name="default_meta_description" class="form-control" rows="2">{{ $settings['default_meta_description'] ?? '' }}</textarea></div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
@endsection
