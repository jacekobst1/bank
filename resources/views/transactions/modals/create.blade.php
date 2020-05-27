{!! Form::open(['route' => 'transactions.store', 'class' => 'modal-form']) !!}
    {!! Form::hidden('bill_id', $bill_id) !!}
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('New transaction') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <label class="w-100">
                    {{ __('Bill number') }}
                    {!! Form::text('target_bill_number', null, [
                        'class' => 'form-control',
                        'minlength' => 26,
                        'maxlength' => 26
                    ]) !!}
                </label>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start">
                <label>
                {{ __('Amount') }}
                {!! Form::number('amount', null, ['class' => 'form-control']) !!}
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary modal-submit-btn">
            {{ __('Send') }}
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ __('Close') }}
        </button>
    </div>
{!! Form::close() !!}
