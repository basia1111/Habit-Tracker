
    function changeToggler() {
    const toggler = document.getElementById('navbar-toggler');
    const rowNav = document.getElementById('row-nav');
        const navBar= document.getElementById('nav-bar');
        if (toggler.getAttribute('aria-expanded') === 'true'){
            console.log('a')
            toggler.innerHTML = '<span id="icon-close" class="navbar-toggler-icon"><i class="fa-solid fa-xmark"></i></spanid->';
            rowNav.style.backgroundColor = "transparent";
            navBar.style.backgroundColor = "rgb(0, 0, 0, 0.7)"
        }else{
            toggler.innerHTML = '<span class="navbar-toggler-icon"> <i class="fa-solid fa-bars"></i></span>';
            console.log('b')
            rowNav.style.backgroundColor = "background: linear-gradient( #F5F3F0, transparent);"
            navBar.style.backgroundColor = ""
        }
}

function logout(){
    window.localStorage.clear();
}