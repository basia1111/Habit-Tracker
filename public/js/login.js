const signUpBtnLink = document.querySelector('.signUpBtn-link');
const signInBtnLink = document.querySelector('.signInBtn-link');
const wrapper = document.querySelector('.wrapper');
const register = document.querySelector('.button-register');
const inputs = document.querySelectorAll("input");

//change of active form

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

//adding and removing active form input fields

inputs.forEach(inp => {
    inp.addEventListener("focus", () => {
        inp.classList.add('active');
    });
    inp.addEventListener("blur", () => {
        if (inp.value != "") return;
        inp.classList.remove('active');
    });
    inp.addEventListener("blur", () => {
        if (inp.value != "")
            inp.classList.add('active');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var myElement = document.getElementById('my-element');
    var myVariable = myElement.getAttribute('data-my-variable');

   if (myVariable == 1){

       inputs.forEach(inp => {
           if (inp.value != "")
               inp.classList.add('active');
       });
   }

});

var emailInput = document.getElementById('login-name');

document.addEventListener('DOMContentLoaded', function() {
    if (emailInput.value !== null) {
        emailInput.classList.add('active');
        console.log('active')
    }
    else{
        console.log('not-active')
    }
});

