// Update the current year in an element with the id "currentYear"
document.getElementById('currentYear').textContent = new Date().getFullYear();

// Add click event listeners to elements with class "dropdown-btn"
document.querySelectorAll('.dropdown-btn').forEach(button => {
    button.addEventListener('click', function () {
        var dropdownContent = this.nextElementSibling;

        // Toggle the display of the dropdown content
        if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
        } else {
            dropdownContent.style.display = 'block';
        }
    });
});

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropdown-btn')) {
        var dropdowns = document.getElementsByClassName('dropdown-content');
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === 'block') {
                openDropdown.style.display = 'none';
            }
        }
    }
};

// Function to delete a question (placeholder for demonstration)
function deleteQuestion() {
    var selectedQuestion = document.getElementById('deleteQuestion').value;
    // Here you would typically make an AJAX request to your server to delete the selected question
    alert('Delete question: ' + selectedQuestion); // This is just a placeholder for demonstration
}

// Function to get CSRF token from the server
function getCSRFToken() {
    return fetch('/get-csrf-token') // You would replace this URL with your actual endpoint
        .then(response => response.json())
        .then(data => data.csrfToken);
}

// Add CSRF token to all forms
function addCSRFTokenToForms() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const csrfToken = getCSRFToken();
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrfToken';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    });
}

// Add CSRF token to all AJAX requests
function addCSRFTokenToAjaxRequests() {
    const xhrOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function (
        method,
        url,
        async,
        user,
        password
    ) {
        const csrfToken = getCSRFToken();
        if (url.includes('?')) {
            url += `&csrfToken=${csrfToken}`;
        } else {
            url += `?csrfToken=${csrfToken}`;
        }
        xhrOpen.call(this, method, url, async, user, password);
    };
}

// Call functions to add CSRF tokens to forms and AJAX requests
addCSRFTokenToForms();
addCSRFTokenToAjaxRequests();
