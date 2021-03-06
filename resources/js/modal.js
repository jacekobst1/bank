/**
 * Wypełnianie zawartości modalu
 */
$('.modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var url = button.data('target-url');
    $.get(url, response => {
        $(this).find('.modal-content').html(response);
    });
});

/**
 * Wysyłanie formularza z modalu
 */
$('body').on('click', '.modal-submit-btn', function() {
    let form = $('.modal-form');
    let url = form.attr('action');
    let method = form.attr('method');
    $.ajax({
        url: url,
        type: method,
        data: form.serialize(),
        success: response => {
            // Przeładowanie strony lub/i przekierowanie na inny adres w przypadku powodzenia
            if (response.url) {
                $('#modal').modal('hide');
                if (response.download) {
                    open(response.url,'_blank');
                    location.reload();
                } else {
                    location.href = response.url;
                }
            } else {
                location.reload();
            }
        },
        error: response => {
            if (response.status === 422) {
                // Wyświetlanie komunikatów błędów w przypadku statusu 422 (nieudanea walidacja)
                let errors = JSON.parse(response.responseText).errors;
                $('.error-text').remove();
                for (let [key, value] of Object.entries(errors)) {
                    $(`[name=${key}]`).after(`<small class='error-text text-danger'>${value}</small>`);
                }
            } else if (response.status === 400) {
                // Wyświetlanie alertu o błędzie w przypadku statusu 400
                let error = JSON.parse(response.responseText).error;
                $('.alert-danger').text(error).slideDown().delay(5000).slideUp();
            }
        }
    });
});

/**
 * Ukrywanie alertu w przypadku zamknięcia modalu
 */
$('#modal').on('hide.bs.modal', function (event) {
    $('.alert').hide();
});
