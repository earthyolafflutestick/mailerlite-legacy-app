@extends('layouts.app')

@section('title', 'API key')

@section('content')
    <form method="post" action="{{ route('apikeys.store') }}">
        @csrf
        <div class="field">
            <label class="label">Api Key</label>
            <div class="control">
                <textarea name="api_key" class="textarea" placeholder="Api Key"></textarea>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-link">Submit</button>
            </div>
        </div>
    </form>
@endsection
