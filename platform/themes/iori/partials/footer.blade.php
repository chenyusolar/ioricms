@php
    $backgroundColor = theme_option('footer_background_color', '#FFFFFF');
    $textColor = theme_option('footer_text_color', theme_option('text_color', '#3E4073'));
    $headingColor = theme_option('footer_heading_color', theme_option('primary_color', '#14176C'));
    $backgroundImage = theme_option('footer_background_image');
    $bottomBackgroundColor = theme_option('footer_bottom_background_color', '#ECF6FA');
    $backgroundImage = $backgroundImage ? RvMedia::getImageUrl($backgroundImage) : null;
@endphp

{!! dynamic_sidebar('pre_footer_sidebar') !!}
<footer class="footer" @style([
    "--footer-background-color: $backgroundColor",
    "--footer-heading-color: $headingColor",
    "--footer-text-color: $textColor",
    "--footer-bottom-background-color: $bottomBackgroundColor",
    "--footer-background-image: url($backgroundImage)" => $backgroundImage,
])>
    <div class="footer-1">
        <div class="container">
            <div class="row">
                {!! dynamic_sidebar('footer_menu') !!}
            </div>
        </div>
    </div>
    <div class="footer-2">
        <div class="container">
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-lg-6 col-md-12 text-center text-lg-start">
                        {!! Menu::renderMenuLocation('footer-bottom-menu', [
                                'view'    => 'footer-bottom-menu',
                                'options' => ['class' => 'menu-bottom'],
                            ])
                        !!}
                    </div>
                    @if($copyright = theme_option('copyright'))
                        <div class="col-lg-6 col-md-12 text-center text-lg-end"><span class="color-grey-300 font-md">{!! BaseHelper::clean($copyright) !!}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
<div>
    @if (is_plugin_active('ecommerce'))
        {!! Theme::partial('ecommerce.quick-view-modal') !!}
    @endif
</div>
    <script>
        'use strict';
        window.isRtl = {{ BaseHelper::siteLanguageDirection() === 'rtl' ? 'true' : 'false' }};
        window.siteConfig = {
            "url" : "{{ route('public.index') }}",
            @if (is_plugin_active('ecommerce'))
                @if(EcommerceHelper::isCartEnabled())
                    "ajaxCount": "{{ route('public.ajax.cart-count') }}",
                    "ajaxCartSidebar": "{{ route('public.ajax.cart-sidebar') }}",
                    "cartUrl": "{{ route('public.cart') }}",
                @endif
                @if(EcommerceHelper::isWishlistEnabled())
                    "wishlistUrl": "{{ route('public.wishlist') }}",
                @endif
                @if(EcommerceHelper::isCompareEnabled())
                    "compareUrl": "{{ route('public.compare') }}",
                @endif
            @endif
        };
    </script>
        {!! Theme::footer() !!}
    </body>
</html>
