<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
@inertia

{{--@php--}}
{{--use App\Models\Question;--}}

{{--$answers = Question::find(1)->answers();--}}
{{--$ans = \App\Models\Answer::all();--}}

{{--@endphp--}}
</body>
</html>
