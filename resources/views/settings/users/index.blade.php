@extends('layouts.app')

@section('content')
    @include('settings.nav')
    {!! Form::open(['route' => 'settings.users', 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-4">
                <button
                        type="button"
                        class="btn btn-success modal-open-btn"
                        data-toggle="modal"
                        data-target="#modal"
                        data-target-url="{{ route('settings.users.create') }}"
                >
                    <i class="fas fa-plus"></i>
                    {{ __('Add new user') }}
                </button>
            </div>
            <div class="col-md-4 offset-md-4 mt-2 mt-md-0">
                <div class="input-group">
                    <input
                            name="search"
                            type="text"
                            class="form-control"
                            placeholder="{{ __('Search by name or PESEL') }}"
                            value="{{ $search }}"
                    >
                    <div class="input-group-append">
                        <button
                                class="btn btn-outline-secondary"
                                type="submit"
                        >
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}


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
                    <td style="min-width: 130px">
                        <button
                            type="button"
                            class="btn btn-sm btn-primary modal-open-btn"
                            data-toggle="modal"
                            data-target="#modal"
                            data-target-url="{{ route('settings.users.edit', $user->id) }}"
                            title="{{ __('Edit') }}"
                        >
                            <i class="fa fa-pencil"></i>
                        </button>
                        <a
                            href="{{ route('settings.users.download-file', $user->id) }}"
                            class="btn btn-sm btn-warning modal-open-btn"
                            title="{{ __('Download initial document') }}"
                        >
                            <i class="fa fa-download"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary modal-open-btn"
                            data-toggle="modal"
                            data-target="#modal"
                            data-target-url="{{ route('settings.users.change-password', $user->id) }}"
                            title="{{ __('Change password') }}"
                        >
                            <i class="fa fa-key"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-sm btn-danger modal-open-btn"
                            data-toggle="modal"
                            data-target="#modal"
                            data-target-url="{{ route('settings.users.delete', $user->id) }}"
                            title="{{ __('Delete') }}"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection
