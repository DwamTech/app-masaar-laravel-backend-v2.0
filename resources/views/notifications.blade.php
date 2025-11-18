@extends('layouts.dashboard')

@section('title', 'الإشعارات')

@include('notifications.partials.styles')

@section('content')
<div class="notifications-page">
    <div class="notifications-container">
        @include('notifications.partials.header')
        @include('notifications.partials.filters')
        @include('notifications.partials.body')
        @include('notifications.partials.audio')
        @include('notifications.partials.send-modal')
    </div>
    </div>
@endsection

@include('notifications.partials.scripts')