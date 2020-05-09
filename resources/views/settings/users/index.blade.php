@extends('layouts.app')

@section('content')
    @include('settings.nav')
    <button
            type="button"
            class="btn btn-primary modal-open-btn"
            data-toggle="modal"
            data-target="#modal"
            data-target-url="{{ route('settings.users.create') }}"
    >
        <i class="fas fa-plus"></i>
        {{ __('Add new user') }}
    </button>
    <table class="table table-responsive mt-5">
        <thead>
            <tr>
                <td>{{ __('Type') }}</td>
                <td>{{ __('First name') }}</td>
                <td>{{ __('Second name') }}</td>
                <td>{{ __('PESEL') }}</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->pesel }}</td>
                    <td>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
