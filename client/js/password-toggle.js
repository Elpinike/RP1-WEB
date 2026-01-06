/* Password toggle & (optional) validation */
document.addEventListener('DOMContentLoaded', () => {
  /* show/hide password — works for any page */
  document.querySelectorAll('.toggle-pass').forEach(icon => {
    icon.addEventListener('click', () => {
      const input = document.getElementById(icon.dataset.target);
      if (!input) return;                    // safety guard
      input.type = (input.type === 'password') ? 'text' : 'password';
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  });

  /* ---------- sign-up form only ---------- */
  const form  = document.getElementById('signupForm');
  const pass  = document.getElementById('pass');
  const pass2 = document.getElementById('pass2');
  const match = document.getElementById('passMatchMsg');

  if (form && pass && pass2 && match) {      // run only on signup page
    function passwordsMatch () {
      if (pass2.value === '') return true;
      return pass.value === pass2.value;
    }

    pass2.addEventListener('input', () => {
      if (passwordsMatch()) {
        pass2.setCustomValidity('');
        match.textContent = 'Looks good!';
      } else {
        pass2.setCustomValidity('Mismatch');
        match.textContent = 'Passwords don’t match.';
      }
    });

    form.addEventListener('submit', evt => {
      if (!form.checkValidity() || !passwordsMatch()) {
        evt.preventDefault();
        evt.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  }
});
