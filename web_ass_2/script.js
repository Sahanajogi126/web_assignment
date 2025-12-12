$(document).ready(function() {
    // Handle form submission
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (validateForm()) {
            // Collect form data
            const formData = new FormData(this);
            
            // Send data to PHP via AJAX
            $.ajax({
                url: 'process.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Parse the JSON response
                    const data = JSON.parse(response);
                    
                    if (data.status === 'success') {
                        // Display results
                        displayResults(data.data);
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                }
            });
        }
    });

    // Real-time validation
    $('input, select, textarea').on('blur', function() {
        validateField($(this));
    });
});

function validateForm() {
    let isValid = true;

    // Validate each required field
    $('#fullName, #email, #phone, #dob, #address, #country, #course').each(function() {
        if (!validateField($(this))) {
            isValid = false;
        }
    });

    // Validate gender
    if (!$('input[name="gender"]:checked').length) {
        isValid = false;
        alert('Please select your gender');
    }

    return isValid;
}

function validateField($field) {
    const value = $field.val().trim();
    const fieldId = $field.attr('id');
    const $error = $('#' + fieldId + 'Error');

    if (!value) {
        $field.addClass('error');
        $error.show();
        return false;
    } else {
        $field.removeClass('error');
        $error.hide();

        // Email validation
        if ($field.attr('type') === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                $field.addClass('error');
                $error.show();
                return false;
            }
        }

        return true;
    }
}

function displayResults(data) {
    let html = '';

    html += `<div class="result-row">
        <div class="result-label">Full Name:</div>
        <div class="result-value">${escapeHtml(data.fullName)}</div>
    </div>`;

    html += `<div class="result-row">
        <div class="result-label">Email Address:</div>
        <div class="result-value">${escapeHtml(data.email)}</div>
    </div>`;

    html += `<div class="result-row">
        <div class="result-label">Phone Number:</div>
        <div class="result-value">${escapeHtml(data.phone)}</div>
    </div>`;

    html += `<div class="result-row">
        <div class="result-label">Date of Birth:</div>
        <div class="result-value">${escapeHtml(data.dob)}</div>
    </div>`;

    html += `<div class="result-row">
        <div class="result-label">Gender:</div>
        <div class="result-value">${escapeHtml(data.gender)}</div>
    </div>`;

    html += `<div class="result-row">
        <div class="result-label">Address:</div>
        <div class="result-value">${escapeHtml(data.address)}</div>
    </div>`;

    html += `<div class="result-row">
        <div class="result-label">Country:</div>
        <div class="result-value">${escapeHtml(data.country)}</div>
    </div>`;

    html += `<div class="result-row">
        <div class="result-label">Course:</div>
        <div class="result-value">${escapeHtml(data.course)}</div>
    </div>`;

    if (data.interests && data.interests.length > 0) {
        html += `<div class="result-row">
            <div class="result-label">Interests:</div>
            <div class="result-value">${escapeHtml(data.interests)}</div>
        </div>`;
    }

    if (data.comments) {
        html += `<div class="result-row">
            <div class="result-label">Additional Comments:</div>
            <div class="result-value">${escapeHtml(data.comments)}</div>
        </div>`;
    }

    html += `<div class="result-row">
        <div class="result-label">Submission Date:</div>
        <div class="result-value">${escapeHtml(data.submissionDate)}</div>
    </div>`;

    $('#resultContent').html(html);
    $('#formContainer').hide();
    $('#resultContainer').show();
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}

function resetForm() {
    $('#registrationForm')[0].reset();
    $('.error').removeClass('error');
    $('.error-message').hide();
    $('#resultContainer').hide();
    $('#formContainer').show();
}