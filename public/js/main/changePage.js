$(document).ready(function() {

    const storedPage = localStorage.getItem('page');


    if (storedPage){
        document.getElementById('skeleton-'+storedPage).style.display ='block';
        changeActive();
        $("#"+storedPage).addClass('active');
        changePage(storedPage);

    }
    else{
        document.getElementById('skeleton-home').style.display ='block';
        $("#home").addClass('active');
        changePage('home');
    }

});

function changePage(page){

    $.ajax({
        type: 'GET',
        url: '/page/'+page,
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
    $("#home").removeClass('active');
    $("#week").removeClass('active');
    $("#user-details").removeClass('active');

}
