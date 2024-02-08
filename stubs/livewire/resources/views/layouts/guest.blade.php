<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
            {{ $slot }}
        </div>

        @if (config('services.recaptcha.enable'))
        <script src="https://www.google.com/recaptcha/api.js?onload=handleRecaptchaLoad&render=explicit" async defer></script>
        <script class="grecaptcha">
            let captchaIds = ['loginCaptcha']
            function handleRecaptchaLoad() {
                captchaIds.forEach((captchaId, key) => {
                    if (!document.getElementById(captchaId)) {
                        return
                    }

                    window[`widget_captcha${key}`] = grecaptcha.render(
                        captchaId, {
                            'sitekey': '{{ config('services.recaptcha.site_key') }}'
                        }
                    )
                })
            }
            window.addEventListener('reset-google-recaptcha', () => {
                captchaIds.forEach((captchaId, key) => {
                    if (!document.getElementById(captchaId)) {
                        return
                    }

                    grecaptcha.reset(window[`widget_captcha${key}`], {
                        'sitekey': '{{ config('services.recaptcha.site_key') }}'
                    })
                })
            })
        </script>
        @endif
        @livewireScripts
    </body>
</html>
