// Form validation functions
function validatePassword(password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
    return regex.test(password);
}

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validateName(name) {
    return name.length >= 2 && /^[a-zA-Z\s-]+$/.test(name);
}

// Register form validation
function validateRegisterForm(event) {
    const form = event.target;
    const firstname = form.firstname.value.trim();
    const lastname = form.lastname.value.trim();
    const email = form.email.value.trim();
    const password = form.password.value;
    const role = form.role.value;
    
    let errors = [];
    
    // Validate firstname
    if (!validateName(firstname)) {
        errors.push("First name should contain only letters and be at least 2 characters long");
        form.firstname.classList.add('error');
    } else {
        form.firstname.classList.remove('error');
    }
    
    // Validate lastname
    if (!validateName(lastname)) {
        errors.push("Last name should contain only letters and be at least 2 characters long");
        form.lastname.classList.add('error');
    } else {
        form.lastname.classList.remove('error');
    }
    
    // Validate email
    if (!validateEmail(email)) {
        errors.push("Please enter a valid email address");
        form.email.classList.add('error');
    } else {
        form.email.classList.remove('error');
    }
    
    // Validate password
    if (!validatePassword(password)) {
        errors.push("Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number");
        form.password.classList.add('error');
    } else {
        form.password.classList.remove('error');
    }
    
    // Validate role
    if (!role) {
        errors.push("Please select a role");
        form.role.classList.add('error');
    } else {
        form.role.classList.remove('error');
    }
    
    // Show errors if any
    if (errors.length > 0) {
        event.preventDefault();
        displayErrors(errors);
    }
}

// Login form validation
function validateLoginForm(event) {
    const form = event.target;
    const email = form.email.value.trim();
    const password = form.password.value;
    
    let errors = [];
    
    // Validate email
    if (!validateEmail(email)) {
        errors.push("Please enter a valid email address");
        form.email.classList.add('error');
    } else {
        form.email.classList.remove('error');
    }
    
    // Validate password (basic check)
    if (password.length < 8) {
        errors.push("Password must be at least 8 characters long");
        form.password.classList.add('error');
    } else {
        form.password.classList.remove('error');
    }
    
    // Show errors if any
    if (errors.length > 0) {
        event.preventDefault();
        displayErrors(errors);
    }
}

// Display errors function
function displayErrors(errors) {
    const errorDiv = document.querySelector('.alert-danger') || document.createElement('div');
    errorDiv.className = 'alert alert-danger';
    errorDiv.innerHTML = errors.map(error => `<p>${error}</p>`).join('');
    
    const form = document.querySelector('form');
    form.insertBefore(errorDiv, form.firstChild);
}

// Real-time password strength indicator
function updatePasswordStrength(password) {
    const strengthMeter = document.getElementById('password-strength');
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    
    const strengthText = ['Weak', 'Medium', 'Strong', 'Very Strong'];
    const strengthClass = ['weak', 'medium', 'strong', 'very-strong'];
    
    strengthMeter.textContent = strengthText[strength - 1] || '';
    strengthMeter.className = strengthClass[strength - 1] || '';
} 