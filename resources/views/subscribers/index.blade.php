@extends('layouts.app')

@section('title', 'All subscribers')

@section('content')
    @if (isset($error))
        <div class="notification is-danger">
            {{ $error }}
        </div>
    @endif

    <table class="table is-fullwidth" id="subscribers" style="display: none;">
        <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Country</th>
            <th>Subscribe date</th>
            <th>Subscribe time</th>
        </tr>
        </thead>
        <tbody>
        @if (count($subscribers) > 0)
            @foreach ($subscribers as $subscriber)
                <tr>
                    <td>{{ $subscriber->email }}</td>
                    <td>{{ $subscriber->name }}</td>
                    <td>{{ $subscriber->country }}</td>
                    <td>{{ $subscriber->subscribeDate }}</td>
                    <td>{{ $subscriber->subscribeTime }}</td>
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
        </tr>
        </tfoot>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">
        _settings = @js([
        'route' => route('subscribers.index'),
        'deferLoading' => $deferLoading,
    ]);
    </script>
    <script type="text/javascript" src="{{ asset('js/subscribers.index.js') }}"></script>
@endsection
