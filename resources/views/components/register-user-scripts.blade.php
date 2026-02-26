<script>
    document.getElementById('Type_Personnel')?.addEventListener('change', function () {
        var elderlyTypeGroup = document.getElementById('elderly-type-group');
        if (this.options[this.selectedIndex].text === 'แพทย์') {
            elderlyTypeGroup.style.display = 'block';
        } else {
            elderlyTypeGroup.style.display = 'none';
        }
    });

    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('Password');

    togglePassword?.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>