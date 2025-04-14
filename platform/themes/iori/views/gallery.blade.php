{!! Theme::partial('breadcrumb') !!}

<section class="py-4 my-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-3">
                <h2 class="mb-3 text-primary">{{  $gallery->name }}</h2>

                @if ($description = $gallery->description)
                    <p>{!! BaseHelper::clean($description) !!}</p>
                @endif
            </div>

            @foreach (gallery_meta_data($gallery) as $image)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ RvMedia::getImageUrl(Arr::get($image, 'img')) }}">
                        <div class="card my-3">
                            {{ RvMedia::image(Arr::get($image, 'img'), Arr::get($image, 'description'), 'medium', attributes: ['class' => 'card-image-top']) }}
                        </div>
                    </a>
                </div>
                @endforeach
        </div>
    </div>
</section>

{!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $gallery) !!}
