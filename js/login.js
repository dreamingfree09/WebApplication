// Wait for the DOM to be fully loaded before executing the script
document.addEventListener('DOMContentLoaded', event => {
    // Function to validate the email in the login form
    window.validateLoginEmail = function () {
        // Get the email input element and the error message element
        const email = document.getElementById('login-email')
        const errorDiv = document.getElementById('login-email-error')
        
        // Regular expression for validating email format
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
        
        // Check if the email value matches the regular expression
        if (!regex.test(email.value)) {
            errorDiv.textContent = 'Invalid email format.'
        } else {
            errorDiv.textContent = '' // Clear the error message if email is valid
        }
    }

    // Function to validate the password in the login form
    window.validateLoginPassword = function () {
        // Get the password input element and the error message element
        const password = document.getElementById('login-password')
        const errorDiv = document.getElementById('login-password-error')
        
        // Check if the password length is less than 8 characters
        if (password.value.length < 8) {
            errorDiv.textContent = 'Password must be at least 8 characters.'
        } else {
            errorDiv.textContent = '' // Clear the error message if password is valid
        }
    }

    // Set the current year in the footer
    document.getElementById('currentYear').textContent = new Date().getFullYear()

    // Output a message to the console
    console.log('LOGIN')
})
