$(document).ready(function() {

    const storedPage = localStorage.getItem('page');
    const storedNumber = localStorage.getItem('number');
    const storedId = localStorage.getItem('id');

    if (storedPage) {
        changeActive();


        if (storedNumber && storedPage === 'users') {
            $("#" + storedPage).addClass('active');
            changePage(storedPage,storedNumber);
        }else if(storedId && storedPage === 'post'){
            $("#posts").addClass('active');
            changePage(storedPage,storedId );
        }else{
            $("#" + storedPage).addClass('active');
            changePage(storedPage);
        }

    }
    else{
        $("#users").addClass('active');
        changePage('users', 1);
    }

});

function changePage(page, number){
    let numberVal;
    if (page === 'users') {
        numberVal = number;
        localStorage.setItem('number', numberVal);
    }
    if (page === 'post') {
        numberVal = number;
        localStorage.setItem('id', numberVal);
    }
    $.ajax({
        type: 'GET',
        url: '/admin_page/'+page,
        data:  {
            number: numberVal,
        },
        dataType: 'json',
        success: function (response){
            changeActive();
            $("#"+page).addClass('active');
            $('#page-container').html(response.html);
            localStorage.setItem('page', page);

            if(page ==='post'){
                $("#posts").addClass('active');
            }

        }
    });
}

function changeActive(){
    $("#users").removeClass('active');
    $("#create").removeClass('active');
    $("#posts").removeClass('active');

}
