$(document).ready(function() {
    $('.star').click(function() {
        var value = $(this).data('value');
        $('#testimony_form_rating_' + value).prop('checked', true);
        $('.star').removeClass('highlight');
        $('.star').each(function(index) {
            if(index < value) {
                $(this).addClass('highlight');
            }
        });
    });
});
