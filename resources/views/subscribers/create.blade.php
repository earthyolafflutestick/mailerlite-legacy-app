@extends('layouts.app')

@section('title', 'Create subscriber')

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
    <form method="post" action="{{ route('subscribers.store') }}">
        @csrf
        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input class="input" type="text" name="email" placeholder="Email" value="{{ old('email') }}"/>
            </div>
        </div>
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input class="input" type="text" name="name" placeholder="Name" value="{{ old('name') }}"/>
            </div>
        </div>
        <div class="field">
            <label class="label">Country</label>
            <div class="control">
                <input class="input" type="text" name="country" placeholder="Country" value="{{ old('country') }}"/>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-link">Submit</button>
            </div>
        </div>
    </form>
@endsection
