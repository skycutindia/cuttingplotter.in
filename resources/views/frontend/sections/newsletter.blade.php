<section class="py-5" style="background: linear-gradient(135deg, #1a56db, #1e40af); color: #fff;">
    <div class="container text-center">
        @if(!empty($content['title']))<h2 class="mb-2">{{ $content['title'] }}</h2>@endif
        @if(!empty($content['subtitle']))<p class="opacity-75 mb-4">{{ $content['subtitle'] }}</p>@endif
        <form class="row g-2 justify-content-center" action="{{ route('contact.store') }}" method="POST">@csrf
            <div class="col-md-4"><input type="email" name="email" class="form-control" placeholder="Your email" required></div>
            <input type="hidden" name="name" value="Newsletter Subscriber">
            <input type="hidden" name="phone" value="-">
            <input type="hidden" name="message" value="Newsletter subscription request">
            <div class="col-auto"><button type="submit" class="btn btn-light">{{ $content['button_text'] ?? 'Subscribe' }}</button></div>
        </form>
    </div>
</section>
