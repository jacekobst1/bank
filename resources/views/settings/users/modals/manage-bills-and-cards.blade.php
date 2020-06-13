<script>
    $(function() {
        loadData();

        // Utworzenie nowego rachunku
        $('#create-bill-btn').click(function() {
            $.post('{{ route('settings.bills.store', $user->id) }}')
                .then(response => {
                    loadData();
                });
        });

        // Usunięcie rachunku
        $('body').on('click', '.delete-bill-btn', function() {
            $.ajax({
                url: '{{ env('APP_URL') }}/settings/bills/detach-user/' + $(this).attr('data-bill-id'),
                type: 'POST',
                data: {
                    user_id: "{{ $user->id }}"
                },
                success: response => {
                    loadData();
                },
            });
        });

        // Dopisanie istniejącego rachunku do usera
        $('#assign-bill-btn').click(function() {
            $.post('{{ route('settings.bills.attach-user') }}', {
                    'user_id': "{{ $user->id }}",
                    'bill_number': $('#bill-number-input').val()
                })
                .done(response => {
                    $('.error-text').remove();
                    loadData();
                })
                .fail(response => {
                    $('.error-text').remove();
                    $('#bill-number-input-group').after(`<small class='error-text text-danger'>${response.responseJSON.errors.bill_number[0]}</small>`);
                });
        });

        // Utworzenie nowej karty
        $('body').on('click', '.create-card-btn', function() {
            let bill_id = $(this).attr('data-bill-id');
            $.post("{{ env('APP_URL') }}/settings/cards/store/{{ $user->id }}/" + bill_id)
                .then(response => {
                    loadData();
                });
        });

        // Usunięcie karty
        $('body').on('click', '.delete-card-btn', function() {
            $.ajax({
                url: '{{ env('APP_URL') }}/settings/cards/delete/' + $(this).attr('data-card-id'),
                type: 'DELETE',
                success: response => {
                    loadData();
                },
            });
        });

        // Blokoda/odblokowanie karty
        $('body').on('click', '.toggle-active-card-btn', function() {
            $.ajax({
                url: '{{ env('APP_URL') }}/settings/cards/toggle-active/' + $(this).attr('data-card-id'),
                type: 'PATCH',
                success: response => {
                    loadData();
                },
            });
        });

        // Pobranie wszystkich rachunków i kard dla danego usera
        function loadData() {
            $.get('{{ route('settings.bills.get-all', $user->id) }}')
                .then(response => {
                    let bills = response.bills;
                    $.get('{{ route('settings.cards.get-all', $user->id) }}')
                        .then(response => {
                            let cards = response.cards;
                            $('#bills-list').empty();
                            bills.forEach(bill => {
                                let html = "<li style='margin: 25px 0;'>" + bill.formatted_number + ' (' + bill.balance + ' ' + bill.currency + ')';
                                if (!bill.balance) {
                                    html +=
                                        "<button "
                                        + "type='button' "
                                        + "class='delete-bill-btn btn btn-sm btn-danger ml-2' "
                                        + "style='font-size: 0.5rem' "
                                        + "data-bill-id='" + bill.id + "' "
                                        + "title='{{ __('Delete') }}' "
                                        + ">"
                                        + "<i class='fa fa-trash'></i>"
                                        + "</button>";
                                }
                                html +=
                                    "<button "
                                    + "type='button' "
                                    + "class='create-card-btn btn btn-sm btn-success ml-2' "
                                    + "style='font-size: 0.5rem' "
                                    + "data-bill-id='" + bill.id + "' "
                                    + "title='{{ __('Add card') }}' "
                                    + ">"
                                    + "<i class='fas fa-credit-card'></i>"
                                    + "</button>";
                                html += "<ul>";
                                cards.forEach(card => {
                                    if (parseInt(card.bill_id) === parseInt(bill.id)) {
                                        let clss = "class='payment-card'";
                                        if (!card.active) {
                                            clss = "class='payment-card payment-card-deactivated'";
                                        }
                                        html += "<li " + clss + ">" + card.formatted_number + ' (' + card.type + ', expires ' + card.expiration_date + ')';
                                        html +=
                                            "<button "
                                            + "type='button' "
                                            + "class='delete-card-btn btn btn-sm btn-danger ml-2' "
                                            + "style='font-size: 0.5rem' "
                                            + "data-card-id='" + card.id + "' "
                                            + "title='{{ __('Delete the card') }}' "
                                            + ">"
                                            + "<i class='fas fa-credit-card'></i>"
                                            + "</button>";
                                        if (card.active) {
                                            html +=
                                                "<button "
                                                + "type='button' "
                                                + "class='toggle-active-card-btn btn btn-sm btn-dark ml-2' "
                                                + "style='font-size: 0.5rem' "
                                                + "data-card-id='" + card.id + "' "
                                                + "title='{{ __('Block the card') }}' "
                                                + ">"
                                                + "<i class='fas fa-ban'></i>"
                                                + "</button>";
                                        } else {
                                            html +=
                                                "<button "
                                                + "type='button' "
                                                + "class='toggle-active-card-btn btn btn-sm btn-primary ml-2' "
                                                + "style='font-size: 0.5rem' "
                                                + "data-card-id='" + card.id + "' "
                                                + "title='{{ __('Activate the card') }}' "
                                                + ">"
                                                + "<i class='far fa-check-circle'></i>"
                                                + "</button>";
                                        }
                                        html += '</li>';
                                    }
                                });
                                html += "</ul><hr></li>";
                                $('#bills-list').append(html);
                            });
                        });
                    });
        }
    });
</script>

<div class="modal-header">
    <h5 class="modal-title">
        {{ __('Manage bills and cards') }}
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

<style>
    .payment-card {
        font-size: 0.8rem;
        margin: 7px 0;
    }
    .payment-card button {
        font-size: 0.35rem !important;
    }
</style>
