
$(document).ready(function(){
    $(` .row-content`).on('click',
        ' .myButton',
        function () {
            $.ajax({
                type: 'GET',
                url: $(this).attr('href'),
                dataType: 'json',
                success: function (response) {
                    $('#percentage').text(response.percentage + '%');
                    $('.progress-bar').attr('style', 'width:' + response.percentage +'%');
                    $('.streak-' + response.id).text(response.streak);
                    console.log('streak' + response.streak + 'id' + response.id)
                }
            });

            $('#name-' + $(this).attr('habit-id')).toggleClass("name-check name-uncheck");
            $('#habit-' + $(this).attr('habit-id')).toggleClass("habit-check habit-uncheck");
            $('#Button-' + $(this).attr('habit-id')).toggleClass("button-check button-uncheck");
            $(this).toggleClass("uncheck check");

            if ($(this).attr('status') === 'check') {
                $(this).attr("status", "uncheck")
            } else {
                $(this).attr("status", "check")
            }
        });
});

function showModal(objectId){
    // 2 second delay
    if ($('#Button-' + objectId).attr('status') === 'check'){
        setTimeout(function() {
            $('#exampleModalLong-' + objectId).modal('show');
}, 1000);
}
}

