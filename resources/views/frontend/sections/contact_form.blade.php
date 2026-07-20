<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(!empty($content['title']))<h2 class="section-title">{{ $content['title'] }}</h2>@endif
                @if(!empty($content['subtitle']))<p class="text-muted mb-4">{{ $content['subtitle'] }}</p>@endif
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
</section>
