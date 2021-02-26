<!DOCTYPE html>
<html>
    <head>
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="py-20">
        <div id="app">
            <people-component :people="{{ $people }}"></people-component>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
