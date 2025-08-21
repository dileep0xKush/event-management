$(document).ready(function () {

    function validateEmail() {
        let email = $("#email").val().trim();
        if (email === "") {
            $("#emailError").text("Email is required.");
            return false;
        } else if (!/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
            $("#emailError").text("Please enter a valid email address.");
            return false;
        } else {
            $("#emailError").text("");
            return true;
        }
    }

    function validatePassword() {
        let password = $("#password").val().trim();
        if (password === "") {
            $("#passwordError").text("Password is required.");
            return false;
        } else if (password.length < 6) {
            $("#passwordError").text("Password must be at least 6 characters.");
            return false;
        } else {
            $("#passwordError").text("");
            return true;
        }
    }

    $("#email").on("input blur", validateEmail);
    $("#password").on("input blur", validatePassword);
    $("#loginForm").on("submit", function (e) {
        e.preventDefault();

        let isEmailValid = validateEmail();
        let isPasswordValid = validatePassword();

        if (!isEmailValid || !isPasswordValid) return;

        $.ajax({
            url: URL,
            method: "POST",
            data: JSON.stringify({
                email: $("#email").val().trim(),
                password: $("#password").val().trim()
            }),
            contentType: "application/json",
            headers: {
                "Accept": "application/json"
            },
            success: function (response) {
                localStorage.setItem("auth_token", response.token);
                showToast("success", "Login successful! Redirecting...");
                setTimeout(() => {
                    window.location.href = "/dashboard";
                }, 1500);
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    showToast("error", xhr.responseJSON.message);
                } else {
                    showToast("error", "Something went wrong. Please try again.");
                }
            }
        });
    });
});
