
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
