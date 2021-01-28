@extends('layouts.base')
@section('title', trans('global.home.title'))
@section('parentPageTitle', trans('global.home.parentPageTitle'))

@section('content')
    @can('dashboard-access')
        HI !
    @endcan
@stop

@push('after-scripts')
    <script>

    </script>
@endpush
