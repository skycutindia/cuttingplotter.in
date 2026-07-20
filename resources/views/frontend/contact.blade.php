@extends('frontend.layouts.app')

@section('title', 'Contact Us | '.($settings['site_name'] ?? ''))

@section('content')
<div class="bg-light py-4"><div class="container"><h1 class="h3 mb-0">Contact Us</h1></div></div>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <h4>Get in Touch</h4>
            <p class="text-muted">Have questions about our products? Need a quote? We're here to help.</p>
            <div class="mb-3"><i class="bi bi-telephone text-primary"></i> <strong>Phone:</strong> {{ $settings['company_phone'] ?? '' }}</div>
            <div class="mb-3"><i class="bi bi-envelope text-primary"></i> <strong>Email:</strong> {{ $settings['company_email'] ?? '' }}</div>
            <div class="mb-3"><i class="bi bi-geo-alt text-primary"></i> <strong>Address:</strong> {{ $settings['company_address'] ?? '' }}</div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('contact.store') }}" method="POST">@csrf
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label">Company</label><input type="text" name="company" class="form-control"></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Message *</label><textarea name="message" class="form-control" rows="4" required></textarea></div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
