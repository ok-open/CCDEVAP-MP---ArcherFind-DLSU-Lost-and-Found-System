// DLSU EMAIL VALIDATION
const emailInput = document.getElementById("email");
if (emailInput) {
    emailInput.addEventListener("blur", () => {
        const email = emailInput.value.trim().toLowerCase();

        if (
            email !== "" &&
            !email.endsWith("@dlsu.edu.ph")
        ) {
            showToast(
                "⚠ Please use your DLSU email address.",
                "var(--color-errorMsg)"
            );
            emailInput.style.border = "1px solid var(--color-errorMsg)";
            clearTimeout(emailInput._errorTimeout);
            emailInput._errorTimeout = setTimeout(() => {
                emailInput.style.border = "";
            }, 1500);
        }
    });
}

// PASSWORD: Toggle Visibility
function togglePassVisibility() {
    const buttons = document.querySelectorAll(".see-pass");

    buttons.forEach((button) => {
        const field = button.previousElementSibling; // the <input> right before this button

        const showPassword = () => {
            field.type = "text";
            button.classList.add("active");
        };

        const hidePassword = () => {
            field.type = "password";
            button.classList.remove("active");
        };

        // Mouse events (desktop)
        button.addEventListener("mousedown", showPassword);
        button.addEventListener("mouseup", hidePassword);
        button.addEventListener("mouseleave", hidePassword);

        // Touch events (mobile)
        button.addEventListener("touchstart", (e) => {
            e.preventDefault();
            showPassword();
        });
        button.addEventListener("touchend", hidePassword);
        button.addEventListener("touchcancel", hidePassword);
    });
}

togglePassVisibility();