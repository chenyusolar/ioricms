@if ($galleries->isNotEmpty())
    @php
        Theme::set('pageTitle', __('Galleries'));
    @endphp

    {!! Theme::partial('breadcrumb') !!}
    <section class="py-4 my-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <h2 class="mb-3 text-primary">{{  __('Galleries') }}</h2>
                </div>

                @forelse($galleries as $gallery)
                    <div class="col-md-6 col-lg-4">
                        <div class="card my-3">
                            {{ RvMedia::image($gallery->image, $gallery->title, 'medium', attributes: ['class' => 'card-image-top']) }}

                            <div class="card-body">
                                <h3 class="card-title"><a href="{{ $gallery->url }}" class="text-primary">{{ $gallery->name }}</a></h3>
                                @if($description = $gallery->description)
                                    <p class="card-text truncate-2-custom">{!! $description !!}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    {!! Theme::partial('no-content') !!}
                @endforelse
            </div>

            @if ($galleries instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $galleries->total() > 0)
                <div class="text-center mt-30">
                    {{ $galleries->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) }}
                </div>
            @endif
        </div>
    </section>
@endif
