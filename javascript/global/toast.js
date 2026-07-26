function showToast(message, color, duration = 4000) {
    const toast = document.getElementById("toast");
    if (!toast) {
        console.warn("No #toast element found on this page.");
        return;
    }

    toast.textContent = message;
    toast.style.backgroundColor = color;
    toast.classList.add("show-toast", "show");

    clearTimeout(toast._hideTimeout);

    toast._hideTimeout = setTimeout(() => {
        toast.classList.remove("show-toast", "show");
    }, duration);
}
