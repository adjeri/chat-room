@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Video call</div>
        <div class="card-body">
            <video-chat :me="{{ $user }}" :friends="{{ $others }}"></video-chat>
        </div>
    </div>
</div>
@endsection
