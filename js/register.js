// Wait for the DOM to be fully loaded before executing the script
document.addEventListener('DOMContentLoaded', event => {
    // Function to validate the username
    window.validateUsername = function () {
        // Get the username input element and the error message element
        const username = document.getElementById('username')
        const errorDiv = document.getElementById('username-error')
        
        // Check if the username length is not between 6 and 20 characters
        if (username.value.length < 6 || username.value.length > 20) {
            errorDiv.textContent = 'Username must be between 6 and 20 characters.'
        } else {
            errorDiv.textContent = '' // Clear the error message if username is valid
        }
    }

    // Function to validate the email
    window.validateEmail = function () {
        // Get the email input element and the error message element
        const email = document.getElementById('email')
        const errorDiv = document.getElementById('email-error')
        
        // Regular expression for validating email format
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
        
        // Check if the email value matches the regular expression
        if (!regex.test(email.value)) {
            errorDiv.textContent = 'Invalid email format.'
        } else {
            errorDiv.textContent = '' // Clear the error message if email is valid
        }
    }

    // Function to validate the password
    window.validatePassword = function () {
        // Get the password input element and the error message element
        const password = document.getElementById('password')
        const errorDiv = document.getElementById('password-error')
        
        // Check if the password length is not between 8 and 20 characters
        if (password.value.length < 8 || password.value.length > 20) {
            errorDiv.textContent = 'Password must be between 8 and 20 characters.'
        } else {
            errorDiv.textContent = '' // Clear the error message if password is valid
        }
    }

    // Function to validate the confirm password
    window.validateConfirmPassword = function () {
        // Get the password and confirm password input elements, and the error message element
        const password = document.getElementById('password')
        const confirmPassword = document.getElementById('confirm-password')
        const errorDiv = document.getElementById('confirm-password-error')
        
        // Check if the confirm password value matches the password value
        if (confirmPassword.value !== password.value) {
            errorDiv.textContent = 'Passwords do not match.'
        } else {
            errorDiv.textContent = '' // Clear the error message if passwords match
        }
    }

    // Set the current year in the footer
    document.getElementById('currentYear').textContent = new Date().getFullYear()

    // Output a message to the console
    console.log('REGISTER')
})
