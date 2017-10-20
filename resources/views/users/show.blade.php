@extends('layouts.default')
@section('title', $user->name)
@section('content')
    <a href="{{ route('users.show', $user->id) }}">
        <img src="{{ $user->gravatar('140') }}" alt="{{ $user->name }}" class="gravatar"/>
    </a>
    <h1>{{ $user->name }}</h1>
@stop


