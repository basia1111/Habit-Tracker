const signUpBtnLink = document.querySelector('.signUpBtn-link');
const signInBtnLink = document.querySelector('.signInBtn-link');
const wrapper = document.querySelector('.wrapper');
const register = document.querySelector('.button-register');
const inputs = document.querySelectorAll("input");

signUpBtnLink.addEventListener('click', () => {
    wrapper.classList.toggle('active');
});



signInBtnLink.addEventListener('click', () => {
    wrapper.classList.toggle('active');
});

register.addEventListener('click', () => {
    wrapper.classList.toggle('active');
    console.log("register");
});

inputs.forEach(inp => {
    inp.addEventListener("focus", () => {
        inp.classList.add('active');
        console.log("active")
    });
    inp.addEventListener("blur", () => {
        if (inp.value != "") return;
        inp.classList.remove('active');
        console.log("remove-active")

    });
    inp.addEventListener("blur", () => {
        if (inp.value != "")
            inp.classList.add('active');
        console.log("remove-active")

    });


});

