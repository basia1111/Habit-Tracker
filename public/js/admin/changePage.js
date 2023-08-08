$(document).ready(function() {

    const storedPage = localStorage.getItem('page');


    if (storedPage){
        changeActive();
        $("#"+storedPage).addClass('active');
        changePage(storedPage);

    }
    else{
        $("#users").addClass('active');
        changePage('users');
    }

});

function changePage(page){

    $.ajax({
        type: 'GET',
        url: '/admin_page/'+page,
        dataType: 'json',
        success: function (response){
            changeActive();
            $("#"+page).addClass('active');
            $('#page-container').html(response.html);
            localStorage.setItem('page', page);
        }
    });
}

function changeActive(){
    $("#users").removeClass('active');
    $("#create").removeClass('active');
    $("#posta").removeClass('active');

}
