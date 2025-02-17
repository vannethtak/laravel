<link rel="icon" href="{{ custom_asset('image/me.jpg') }}" type="image/x-icon" />

<!-- Fonts and icons -->
<script src="{{ custom_asset('js/plugin/webfont/webfont.min.js') }}"></script>
<script>
    WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons",
            ],
            urls: ["{{ custom_asset('css/fonts.min.css') }}"],
        },
        active: function () {
            sessionStorage.fonts = true;
        },
    });
</script>

<!-- CSS Files -->
<link rel="stylesheet" href="{{ custom_asset('css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ custom_asset('css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ custom_asset('css/kaiadmin.min.css') }}" />
<link rel="stylesheet" href="{{ custom_asset('css/pretty-checkbox.min.css') }}" />

<!-- CSS Just for demo purpose, don't include it in your project -->
<link rel="stylesheet" href="{{ custom_asset('css/demo.css') }}" />
<link rel="stylesheet" href="{{ custom_asset('css/customize.css') }}" />

