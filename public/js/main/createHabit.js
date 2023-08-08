$(document).ready(function() {
    $('.create-form').submit(function (a) {
        $('#staticBackdrop').modal('hide');
        a.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '/create',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                $('#habit-list').html(response.all);
                $('#today-habits').html(response.today);
                $('.progress-bar').attr('style', 'width:' + response.percentage + '%');
                $('#percentage').text(response.percentage + '%');
                $('.number-of-habits').html(response.number);

            },
        });
        $(this)[0].reset();

        const days =  document.querySelectorAll('.day-label');
        for (let label of days){
            label.removeClass('day-active');
        }
    });
});
