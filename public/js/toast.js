function showToast(type, message) {
    let toastEl;
    if (type === "success") {
        $("#successToast .toast-body").text(message);
        toastEl = document.getElementById('successToast');
    } else {
        $("#errorToast .toast-body").text(message);
        toastEl = document.getElementById('errorToast');
    }

    let toast = new bootstrap.Toast(toastEl);
    toast.show();
}
