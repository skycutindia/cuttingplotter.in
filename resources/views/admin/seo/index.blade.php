@extends('admin.layouts.app')
@section('title', 'SEO')
@section('page-title', 'SEO Management')

@section('content')
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#global">Global SEO</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#redirects">Redirects</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#robots">robots.txt</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#sitemap">Sitemap</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="global">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.seo.global') }}" method="POST">@csrf @method('PUT')
                    <div class="mb-3"><label class="form-label">Default Meta Title</label><input type="text" name="default_meta_title" class="form-control" value="{{ $settings['default_meta_title'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">Default Meta Description</label><textarea name="default_meta_description" class="form-control" rows="2">{{ $settings['default_meta_description'] ?? '' }}</textarea></div>
                    <div class="mb-3"><label class="form-label">Default Keywords</label><input type="text" name="default_meta_keywords" class="form-control" value="{{ $settings['default_meta_keywords'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">Default OG Image URL</label><input type="text" name="og_image" class="form-control" value="{{ $settings['og_image'] ?? '' }}"></div>
                    <div class="mb-3"><label class="form-label">Twitter Handle</label><input type="text" name="twitter_handle" class="form-control" value="{{ $settings['twitter_handle'] ?? '' }}" placeholder="@skycutindia"></div>
                    <button type="submit" class="btn btn-primary btn-sm">Save Global SEO</button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="redirects">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white"><h6 class="mb-0">Add Redirect</h6></div>
                    <div class="card-body">
                        <form action="{{ route('admin.seo.redirects.store') }}" method="POST">@csrf
                            <div class="mb-2"><label class="form-label small">From URL</label><input type="text" name="from_url" class="form-control form-control-sm" placeholder="/old-page" required></div>
                            <div class="mb-2"><label class="form-label small">To URL</label><input type="text" name="to_url" class="form-control form-control-sm" placeholder="/new-page" required></div>
                            <div class="mb-2"><label class="form-label small">Type</label><select name="status_code" class="form-select form-select-sm"><option value="301">301 Permanent</option><option value="302">302 Temporary</option></select></div>
                            <div class="form-check mb-2"><input type="checkbox" name="is_active" value="1" class="form-check-input" checked><label class="form-check-label small">Active</label></div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Add Redirect</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead><tr><th>From</th><th>To</th><th>Code</th><th>Hits</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                @forelse($redirects as $redirect)
                                <tr>
                                    <td><code>{{ $redirect->from_url }}</code></td>
                                    <td><code>{{ $redirect->to_url }}</code></td>
                                    <td>{{ $redirect->status_code }}</td>
                                    <td>{{ $redirect->hits }}</td>
                                    <td><span class="badge bg-{{ $redirect->is_active ? 'success' : 'secondary' }}">{{ $redirect->is_active ? 'On' : 'Off' }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.seo.redirects.destroy', $redirect) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger py-0"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted">No redirects yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($redirects->hasPages())<div class="card-footer">{{ $redirects->links() }}</div>@endif
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="robots">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.seo.robots') }}" method="POST">@csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">robots.txt Content</label>
                        <textarea name="robots_txt" class="form-control font-monospace" rows="12">{{ $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /login\nSitemap: ".url('/sitemap.xml') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Save robots.txt</button>
                    <a href="{{ url('/robots.txt') }}" target="_blank" class="btn btn-outline-secondary btn-sm">View Live</a>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="sitemap">
        <div class="card">
            <div class="card-body">
                <p class="text-muted">The XML sitemap is generated automatically from products, brands, categories, blogs, and pages. It is cached for 1 hour.</p>
                <div class="d-flex gap-2">
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i> View Sitemap</a>
                    <form action="{{ route('admin.seo.sitemap.clear') }}" method="POST">@csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm"><i class="bi bi-arrow-clockwise"></i> Clear Cache</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
