
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const emailLabel = document.getElementById('emailLabel');
const senhaLabel = document.getElementById('senhaLabel');

emailInput.addEventListener('focus', () => {
    emailLabel.style.display = 'block';
    emailInput.placeholder = '';
});

emailInput.addEventListener('blur', () => {
    emailLabel.style.display = 'none';
    emailInput.placeholder = 'EMAIL';
});

passwordInput.addEventListener('focus', () => {
    senhaLabel.style.display = 'block';
    passwordInput.placeholder = '';
});

passwordInput.addEventListener('blur', () => {
    senhaLabel.style.display = 'none';
    passwordInput.placeholder = 'SENHA';
});


document.addEventListener("DOMContentLoaded", () => {
    const main = document.querySelector("main");
    const loginLink = document.querySelector("a[href='#register']");
    const registerLink = document.querySelector("a[href='#login']");
    const registerForm = document.querySelector("#register .form-container");
    const loginForm = document.querySelector("#login .login-form");

    function restartAnimation(element) {
        element.classList.remove("animate");
        setTimeout(() => {
            element.classList.add("animate");
        }, 10); // Pequeno delay para resetar a animação
    }

    loginLink.addEventListener("click", (e) => {
        e.preventDefault();
        main.style.transform = "translateX(-100vw)";
        restartAnimation(registerForm);
    });

    registerLink.addEventListener("click", (e) => {
        e.preventDefault();
        main.style.transform = "translateX(0)";
        restartAnimation(loginForm);
    });
});



