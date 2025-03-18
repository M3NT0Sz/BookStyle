const inputs = document.querySelectorAll('input');
const labels = {
    name: document.getElementById('nameLabelRe'),
    email: document.getElementById('emailLabelRe'),
    cpf: document.getElementById('cpfLabelRe'),
    password: document.getElementById('passwordLabelRe'),
    password_confirmation: document.getElementById('passwordConfirmationLabel')
};

inputs.forEach(input => {
    input.addEventListener('focus', () => {
        labels[input.name].style.display = 'block';
        input.placeholder = '';
    });

    input.addEventListener('blur', () => {
        labels[input.name].style.display = 'none';
        input.placeholder = input.name.charAt(0).toUpperCase() + input.name.slice(1).replace('_', ' ');
    });
});
