<script>
    $(function() {
        $("#generate-report-form").submit(function(e){
            e.preventDefault();
            const start_date = $('#start-date').val();
            const end_date = $('#end-date').val();
            const url = e.target.action + '&start_date=' + start_date + '&end_date=' + end_date;
            if (Date.parse(start_date) > Date.parse(end_date)) {
                $('.alert-danger').text('Start date must be before, or same as end date').slideDown().delay(5000).slideUp();
            } else {
                $('#modal').modal('hide');
                open(url,'_blank');
            }
        });
    });
</script>

{!! Form::open([
        'route' => ['transactions.generate-report', ['bill_id' => $bill->id]],
        'id' => 'generate-report-form',
]) !!}
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('Generating bank statement for bill') }}
            <br>
            {{ $bill->formatted_number }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <label class="w-100">
                    {{ __('Start date') }}
                    {!! Form::date('start_date', null, [
                        'id' => 'start-date',
                        'class' => 'form-control',
                        'min' => $min_date,
                        'max' => $max_date
                    ]) !!}
                </label>
            </div>
            <div class="col-6">
                <label class="w-100">
                    {{ __('End date') }}
                    {!! Form::date('end_date', null, [
                        'id' => 'end-date',
                        'class' => 'form-control',
                        'min' => $min_date,
                        'max' => $max_date
                    ]) !!}
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            {{ __('Generate') }}
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            {{ __('Close') }}
        </button>
    </div>
{!! Form::close() !!}
