let url = window.location.pathname.split('/');
var parts = url.filter(function (part) {
    return part !== '';
});
parts.forEach(part => {
    $('[data-url='+part+']').addClass('active');
});

