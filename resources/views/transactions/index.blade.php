@extends('layouts.app')

@section('scripts')
    <script>
        $(function() {
            $('[name=bill_id]').change(function() {
                $(this.form).submit();
            });
        });
    </script>
@endsection
@section('content')
    {!! Form::open(['route' => 'transactions', 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-4">
                <button
                        type="button"
                        class="btn btn-success modal-open-btn"
                        data-toggle="modal"
                        data-target="#modal"
                        data-target-url="{{ route('transactions.create', ['bill_id' => $bill_id]) }}"
                >
                    <i class="fas fa-money-bill-alt"></i>
                    {{ __('Make new transaction') }}
                </button>
            </div>
            <div class="col-md-6 offset-md-2 mt-2 mt-md-0">
                {!! Form::select('bill_id', $bills , $bill_id , ['class' => 'form-control']) !!}
            </div>
        </div>
    {!! Form::close() !!}


    <table class="table table-responsive mt-5">
        <thead>
            <tr>
                <td>{{ __('Type') }}</td>
                <td>{{ __('Receiver/sender') }}</td>
                <td>{{ __('Amount') }}</td>
                <td>{{ __('Date') }}</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->type }}</td>
                    <td>
                        @if ($transaction->target_bill_id == $bill_id)
                            @if ($transaction->sourceBill)
                                {{ $transaction->sourceBill->users->first()->full_name }}
                            @endif
                        @elseif ($transaction->targetBill)
                            {{ $transaction->targetBill->users->first()->full_name }}
                        @endif
                    </td>
                    <td>
                        @if ($transaction->target_bill_id == $bill_id)
                            <span class="text-success">{{ $transaction->amount }}</span>
                        @elseif ($transaction->sourceBill)
                            <span class="text-danger">-{{ $transaction->amount }}</span>
                        @endif
                    </td>
                    <td>{{ Date('Y-m-d', strtotime($transaction->created_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $transactions->links() }}
@endsection
