$(document).ready(function() {

    $("#regForm").submit(function(event) {

        let phone = $("input[name='phone']").val();

        // Simple phone validation
        if (!/^[0-9]{10}$/.test(phone)) {
            alert("Phone number must be 10 digits.");
            event.preventDefault();
        }
    });

});
