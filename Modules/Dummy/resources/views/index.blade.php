@extends('dummy::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('dummy.name') !!}</p>
@endsection
