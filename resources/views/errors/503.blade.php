@extends('errors::layout')

@section('title', 'Service Unavailable')

@section('message')
        <h2>Temporary Down for Maintenance.</h2>
        <p style="font-size: smaller;">
            We are performing scheduled maintenance. We should be back online shortly.
        </p>
        <p style="font-size: smaller;">
            In the meantime you may <a href="{{ url('//openbookpublishers.com') }}">visit our main site</a>.
        </p>
@endsection
