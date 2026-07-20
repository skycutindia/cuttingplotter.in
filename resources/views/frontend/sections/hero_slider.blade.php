@if(!empty($content['slides']))
<section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($content['slides'] as $i => $slide)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            @if(!empty($slide['heading']))<h1>{{ $slide['heading'] }}</h1>@endif
                            @if(!empty($slide['subheading']))<p class="lead mt-3 opacity-75">{{ $slide['subheading'] }}</p>@endif
                            @if(!empty($slide['button_text']))
                            <a href="{{ $slide['button_url'] ?? '#' }}" class="btn btn-light btn-lg mt-3">{{ $slide['button_text'] }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if(count($content['slides']) > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        @endif
    </div>
</section>
@endif
