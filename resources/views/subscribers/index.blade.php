@extends('layouts.app')

@section('title', 'All subscribers')

@section('content')

    <table class="table is-fullwidth" id="subscribers" style="display: none;">
        <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Country</th>
            <th>Subscribe date</th>
            <th>Subscribe time</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (count($subscribers) > 0)
            @foreach ($subscribers as $subscriber)
                <tr>
                    <td>
                        <a href="{{ route('subscribers.edit', $subscriber->id) }}">{{ $subscriber->email }}</a>
                    </td>
                    <td>{{ $subscriber->name }}</td>
                    <td>{{ $subscriber->country }}</td>
                    <td>{{ $subscriber->subscribeDate }}</td>
                    <td>{{ $subscriber->subscribeTime }}</td>
                    <td data-subscriber="{{ $subscriber->id }}">
                        <a class="button is-small is-danger" data-subscriber="{{ $subscriber->id }}">Remove</a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Country</th>
            <th>Subscribe date</th>
            <th>Subscribe time</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
@endsection

@section('scripts')
    <script type=" text/javascript">
        _settings = @js([
        'listUrl' => route('subscribers.index'),
        'destroyUrl' => route('subscribers.destroy', ''),
        'deferLoading' => $deferLoading,
    ]);
    </script>
    <script type="text/javascript" src="{{ asset('js/subscribers.index.js') }}"></script>
@endsection
