@extends('layouts.app')

@section('title', 'Edit subscriber')

@section('content')
    @if ($errors->any())
        <div class="notification is-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post" action="{{ route('subscribers.update', $subscriber->id) }}">
        @csrf
        @method('PUT')
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input class="input" type="text" name="name" placeholder="Name" value="{{ $subscriber->name }}"/>
            </div>
        </div>
        <div class="field">
            <label class="label">Country</label>
            <div class="control">
                <input class="input" type="text" name="country" placeholder="Country"
                       value="{{ $subscriber->country }}"/>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-link">Submit</button>
            </div>
        </div>
    </form>
@endsection
