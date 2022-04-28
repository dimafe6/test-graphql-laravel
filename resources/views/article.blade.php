<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="antialiased">
<div id="app">
    <article-page article_id="{{ $article->id }}"></article-page>
</div>
</body>

<script src="{{ mix('js/app.js') }}"></script>

</html>
