function apiLogout() {
    $.ajax({
        url: LOGOUT_URL,
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Authorization": "Bearer " + localStorage.getItem("auth_token")
        },
        success: function (response) {
            localStorage.removeItem("auth_token");

            showToast("success", "Logged out successfully!");

            setTimeout(() => {
                window.location.href = "/";
            }, 1000);
        },
        error: function (xhr) {
            showToast("error", "Logout failed. Try again.");
        }
    });
}
