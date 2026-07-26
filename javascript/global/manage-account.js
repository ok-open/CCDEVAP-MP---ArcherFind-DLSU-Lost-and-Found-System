const toast = document.getElementById("toast");

window.showToast = function(message, color = "var(--color-successMsg)") {

    if (!toast) return;

    toast.textContent = message;
    toast.style.backgroundColor = color;

    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
};