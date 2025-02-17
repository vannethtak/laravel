<!DOCTYPE html>
<html lang="{{App()->getLocale()}}">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>@yield('title'){{ ucfirst($breadcrumbs['page_action']['name_en'] ?? '') }} | @lang('site_name', [], 'en') </title>
        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
        @include('components.head')
        @yield('style')
    </head>
    <body class="language_{{ App::getLocale() }}">
        <div class="wrapper">
            @include('components.sidebar') <!-- Sidebar -->
            @include('components.spinner') <!-- Spinner -->
            <div class="main-panel">
                @include('components.header')

                <div class="container">
                    <div class="page-inner">
                        {{-- @include('components.breadcrumb') --}}
                        @yield('content')
                    </div>
                </div>

                @include('components.footer') <!-- Footer -->
            </div>
            {{-- @include('components.template') <!-- Custom template --> --}}
        </div>
        @include('components.script')
        @yield('script')
    </body>
</html>
