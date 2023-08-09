$(document).ready(function() {

    const storedPage = localStorage.getItem('page');
    const storedNumber = localStorage.getItem('number');


    if (storedPage) {
        changeActive();
        $("#" + storedPage).addClass('active');
        if (storedNumber && storedPage === 'users') {
            changePage(storedPage,storedNumber );
        } else {
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
    $.ajax({
        type: 'GET',
        url: '/admin_page/'+page,
        data:  {
            number: numberVal,
        },
        dataType: 'json',
        success: function (response){
            changeActive();
            console.log(response.number)
            $("#"+page).addClass('active');
            $('#page-container').html(response.html);
            localStorage.setItem('page', page);

        }
    });
}

function changeActive(){
    $("#users").removeClass('active');
    $("#create").removeClass('active');
    $("#posts").removeClass('active');

}
