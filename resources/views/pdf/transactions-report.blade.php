<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Transactions report') }}</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-top: 50px;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .incoming-amount {
            color: green;
        }

        .outgoing-amount {}
    </style>
</head>
<body>
    <span style="text-align: center">
        <p>{{ __('Transactions report ') }}</p>
        <h3>{{ $bill->formatted_number }}</h3>
        <p>
            {{ $data['start_date'] }}
            -
            {{ $data['end_date'] }}
        </p>
    </span>
    <table>
        <thead>
            <tr>
                <th>{{ __('No') }}.</th>
                <th>{{ __('Bill owner') }}</th>
                <th>{{ __('Bill number') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach($transactions as $transaction)
                {!!
                    $transaction_second_bill = null;
                    $class_name = 'incoming-amount';
                    $amount_sign = '+';
                    if ($transaction->targetBill && $transaction->targetBill->id !== $bill->id) {
                        $transaction_second_bill = $transaction->targetBill;
                        $class_name = 'outgoing-amount';
                        $amount_sign = '-';
                    } else if ($transaction->sourceBill && $transaction->sourceBill->id !== $bill->id) {
                        $transaction_second_bill = $transaction->sourceBill;
                    }
                !!}
                <tr>
                    <th>{{ $no++ }}.</th>
                    <td>{{ $transaction_second_bill ? $transaction_second_bill->users->first()->full_name : '' }}</td>
                    <td>{{ $transaction_second_bill ? $transaction_second_bill-> formatted_number : '' }}</td>
                    <td class="{{ $class_name }}">
                        {{ $amount_sign }}
                        {{ $transaction->amount }}</td>
                    <td>{{ \Date('Y-m-d', strtotime($transaction->created_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
