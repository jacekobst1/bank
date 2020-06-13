@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <h3>{{ __('Bills') }}</h3>
            <table class="table table-responsive">
                <tbody>
                @foreach ($bills as $bill_id => $bill)
                    <tr>
                        <td>{{ $bill }}</td>
                        <td>
                            <a
                                    href="/transactions?bill_id={{ $bill_id }}"
                                    class="btn btn-sm btn-info"
                                    title="{{ __('Show details') }}"
                            >
                                <i class="fas fa-info-circle"></i>
                            </a>
                            <button
                                type="button"
                                class="btn btn-sm btn-success modal-open-btn"
                                data-toggle="modal"
                                data-target="#modal"
                                data-target-url="{{ route('transactions.create', ['bill_id' => $bill_id]) }}"
                                title="{{ __('Make new transaction') }}"
                            >
                                <i class="fas fa-money-bill-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12 col-md-6">
            <h3>{{ __('Cards') }}</h3>
            <table class="table table-responsive">
                <tbody>
                @foreach ($cards as $card)
                    <tr>
                        <td
                            @if (!$card->active)
                            class="payment-card-deactivated"
                            @endif
                        >{{ $card->formatted_number }}</td>
                        <td>
                            {!! Form::open(['url' => ['/settings/cards/toggle-active', $card->id], 'method' => 'patch']) !!}
                            @if ($card->active)
                                <button
                                    type="submit"
                                    class="btn btn-sm btn-secondary modal-open-btn"
                                    title="{{ __('Block card') }}"
                                >
                                    <i class="fas fa-ban"></i>
                                </button>
                            @else
                                <button
                                    type="submit"
                                    class="btn btn-sm btn-primary modal-open-btn"
                                    title="{{ __('Unblock card') }}"
                                >
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            @endif
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
