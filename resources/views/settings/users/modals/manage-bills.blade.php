<script>
    $(function() {
        getBills();

        // Usunięcie rachunku
        $('body').on('click', '.delete-bill-btn', function() {
            $.ajax({
                url: '/settings/bills/delete/' + $(this).attr('data-bill-id'),
                type: 'DELETE',
                data: {
                    user_id: "{{ $user->id }}"
                },
                success: response => {
                    getBills();
                },
            });
        });

        $('#create-bill-btn').click(function() {
            $.post('{{ route('settings.bills.store', $user->id) }}')
                .then(response => {
                    getBills();
                });
        });

        $('#assign-bill-btn').click(function() {
            $.post('{{ route('settings.bills.attach-user') }}', {
                    'user_id': "{{ $user->id }}",
                    'bill_number': $('#bill-number-input').val()
                })
                .done(response => {
                    $('.error-text').remove();
                    getBills();
                })
                .fail(response => {
                    $('.error-text').remove();
                    $('#bill-number-input-group').after(`<small class='error-text text-danger'>${response.responseJSON.errors.bill_number[0]}</small>`);
                });
        });

        // Pobranie wszystkich rachunków dla danego usera
        function getBills() {
            $.get('{{ route('settings.bills.get-all', $user->id) }}')
                .then(response => {
                    $('#bills-list').empty();
                    response.bills.forEach(el => {
                       let html =
                           "<li>" + el.formatted_number+' ('+el.balance+' '+el.currency+')';
                       if (!el.balance) {
                           html +=
                               "<button "
                               +        "type='button' "
                               +        "class='delete-bill-btn btn btn-sm btn-danger ml-2' "
                               +        "style='font-size: 0.5rem' "
                               +        "data-bill-id='"+el.id+"' "
                               +        "title='{{ __('Delete') }}' "
                               + ">"
                               +    "<i class='fa fa-trash'></i>"
                               + "</button>";
                       }
                           html += "</li>";
                        $('#bills-list').append(html);
                    });
                });
        }
    });
</script>

<div class="modal-header">
    <h5 class="modal-title">
        {{ __('Manage bills') }}
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div id="bill-number-input-group" class="input-group">
        <input
                name="bill_number"
                type="text"
                id="bill-number-input"
                class="form-control"
                minlength="26"
                maxlength="26"
                placeholder="{{ __('Link an existing bill by number') }}"
        >
        <div class="input-group-append">
            <button
                    type="button"
                    id="assign-bill-btn"
                    class="btn btn-sm btn-success"
            >
                <i class="fas fa-link"></i>
            </button>
        </div>
    </div>
    <div class="row mt-4">
        <ul id="bills-list"></ul>
    </div>
    <button
            type="button"
            id="create-bill-btn"
            class="btn btn-sm btn-success"
    >
        <i class="fa fa-plus"></i>
        {{ __('Create new bill') }}
    </button>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        {{ __('Close') }}
    </button>
</div>
