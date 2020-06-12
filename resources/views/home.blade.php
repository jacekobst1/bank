@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

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

                <div id="curve_chart" style="width: 700px; height: 400px"></div>

                {{ dd($transactions[$transactions->keys()->last()]) }}

                {{-- {{ dd($transactions[$transactions->keys()->last()]->updated_at->format('d-m-Y')) }} --}}
                {{-- {{ dd($transactions[$transactions->keys()->last()-2])}} --}}

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        $('[name=bill_id]').change(function() {
            $(this.form).submit();
        });
    });
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = new google.visualization.DataTable();
      data.addColumn('number', 'Day');
      data.addColumn('number', 'Amount of Money');

    var u;
    var w;
    var x;
    var y;
    var z;

    if({!! json_encode($transactions[$transactions->keys()->last()-4]->target_bill_id)!!} == null) {
        var u = parseFloat({!! json_encode($transactions[$transactions->keys()->last()]->amount)!!});
    }else{
        var u = parseFloat({!! json_encode($transactions[$transactions->keys()->last()-4]->amount)!!});
    }

    if({!! json_encode($transactions[$transactions->keys()->last()-3]->target_bill_id)!!} == null || {!! json_encode($transactions[$transactions->keys()->last()-4]->target_bill_id)!!} == null) {
        var w = u;
    }else{
        if({!! json_encode($transactions[$transactions->keys()->first()]->target_bill_id)!!} == {!! json_encode($transactions[$transactions->keys()->last()-3]->target_bill_id)!!}){
            var w = (u + parseFloat({!! json_encode($transactions[$transactions->keys()->last()-3]->amount)!!}));
        }else{
            var w = (u - parseFloat({!! json_encode($transactions[$transactions->keys()->last()-3]->amount)!!}));
        }
    }

    if({!! json_encode($transactions[$transactions->keys()->last()-2]->target_bill_id)!!} == null || {!! json_encode($transactions[$transactions->keys()->last()-3]->target_bill_id)!!} == null) {
        var x = u;
    }else{
        if({!! json_encode($transactions[$transactions->keys()->first()]->target_bill_id)!!} == {!! json_encode($transactions[$transactions->keys()->last()-2]->target_bill_id)!!}){
            var x = (w + parseFloat({!! json_encode($transactions[$transactions->keys()->last()-2]->amount)!!}));
        }else{
            var x = (w - parseFloat({!! json_encode($transactions[$transactions->keys()->last()-2]->amount)!!}));
        }
    }

    if({!! json_encode($transactions[$transactions->keys()->last()-1]->target_bill_id)!!} == null || {!! json_encode($transactions[$transactions->keys()->last()-2]->target_bill_id)!!} == null) {
        var y = u;
    }else{
            if({!! json_encode($transactions[$transactions->keys()->first()]->target_bill_id)!!} == {!! json_encode($transactions[$transactions->keys()->last()-1]->target_bill_id)!!}){
            var y = (x + parseFloat({!! json_encode($transactions[$transactions->keys()->last()-1]->amount)!!}));
        }else{
            var y = (x - parseFloat({!! json_encode($transactions[$transactions->keys()->last()-1]->amount)!!}));
        }
    }
    
    if({!! json_encode($transactions[$transactions->keys()->last()-1]->target_bill_id)!!} == null) {
        var z = y;
    }else{
        if({!! json_encode($transactions[$transactions->keys()->first()]->target_bill_id)!!} == {!! json_encode($transactions[$transactions->keys()->last()]->target_bill_id)!!}){
            var z = (y + parseFloat({!! json_encode($transactions[$transactions->keys()->last()]->amount)!!}));
        }else{
            var z = (y - parseFloat({!! json_encode($transactions[$transactions->keys()->last()]->amount)!!}));
        }
    }

    let a = 1;
    let b = 2;
    let c = 3;
    let d = 4;
    let e = 5;

      data.addRows([
        [a,  u],
        [b,  w],
        [c,  x],
        [d,  y],
        [e,  z],
      ]);


    var options = {
      title: 'Bill timeline (lats 5 transaction)',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    var t = {!! json_encode($transactions[$transactions->keys()->last()]) !!};

    chart.draw(data, options);

    console.log(t);
    
    // $transactions->toArray(), JSON_HEX_TAG
  }
</script>

@endsection