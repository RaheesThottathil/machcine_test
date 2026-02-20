document.getElementById('password-toggle').addEventListener('click', function() {
    const input = document.getElementById('password');
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    this.textContent = isPassword ? 'Hide' : 'Show';
});