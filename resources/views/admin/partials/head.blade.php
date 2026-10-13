<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex, nofollow, noarchive">
<title>@yield('title', 'Administration') — Tafely Kôrsy</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary': '#0b3b8f',
                    'primary-container': '#1653c9',
                    'primary-deep': '#061a44',
                    'primary-fixed': '#dbe6ff',
                    'on-primary': '#ffffff',
                    'secondary': '#c8102e',
                    'secondary-container': '#e5283f',
                    'secondary-fixed': '#ffdad8',
                    'on-secondary': '#ffffff',
                    'background': '#f6f8fe',
                    'surface-container-lowest': '#ffffff',
                    'surface-container-low': '#eef3fd',
                    'surface-container': '#e6edfb',
                    'on-surface': '#0f1b33',
                    'on-surface-variant': '#40495e',
                    'outline': '#6b7590',
                    'outline-variant': '#c2cbe0'
                },
                fontFamily: {
                    sans: ['Manrope', 'system-ui', 'sans-serif'],
                    serif: ['"Source Serif 4"', 'Georgia', 'serif']
                }
            }
        }
    }
</script>
<style>
    .icon-fill { font-variation-settings: 'FILL' 1; }
    .material-symbols-outlined { font-size: 20px; line-height: 1; }
</style>