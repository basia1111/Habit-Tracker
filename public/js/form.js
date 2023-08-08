
function labelDayActive(id){

    var day = document.getElementById(id)

    console.log(day)
    day.classList.toggle('day-active');
    day.classList.toggle('day-label');
}

function labelIconActive(id){
    var icon = document.getElementById(id)
    var icons = document.querySelectorAll('.radio')
    for(let icon of icons){
        icon.classList.remove('icon-active')
    }
    console.log(icon)
    icon.classList.add('icon-active');


}