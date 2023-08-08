
$(document).ready(function() {
    $('#all-habits').on('submit', '.form-delete-habit', function(a){
        $('.modal-delete-habit').modal('hide');
        a.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {

                $('#habit-list').html(response.all);
                $('#today-habits').html(response.today);
                $('.progress-bar').attr('style', 'width:' + response.percentage +'%');
                $('#percentage').text(response.percentage + '%')
                $('.number-of-habits').html(response.number);
            },
        });
    });
});