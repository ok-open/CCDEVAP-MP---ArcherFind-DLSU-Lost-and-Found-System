
// MANAGE ACCOUNT: Password Change + Disable Account
document.addEventListener("DOMContentLoaded", () => {
    const currentPass = document.getElementById("current-pass");
    const newPass = document.getElementById("new-pass");
    const confirmPass = document.getElementById("confirm-pass");
    const saveBtn = document.getElementById("saveBtn");
    const disableBtn = document.getElementById("disableBtn");
    const passwordForm = document.getElementById("passwordForm");

    // CONFIRM NEW PASSWORD
    if (passwordForm) {
        passwordForm.addEventListener("submit", (e) => {

            const current = currentPass.value.trim();
            const newVal = newPass.value.trim();
            const confirmVal = confirmPass.value.trim();

            // All fields required
            if (!current || !newVal || !confirmVal) {
                e.preventDefault();

                showToast(
                    "⚠ Please fill out all password fields.",
                    "var(--color-errorMsg)"
                );

                flagFields(
                    [currentPass, newPass, confirmPass]
                        .filter(field => !field.value.trim())
                );

                return;
            }

            // New + Confirm must match
            if (newVal !== confirmVal) {
                e.preventDefault();

                showToast(
                    "⚠ New password and confirmation do not match.",
                    "var(--color-errorMsg)"
                );

                flagFields([newPass, confirmPass]);

                return;
            }

            // New must differ from Current
            if (newVal === current) {
                e.preventDefault();

                showToast(
                    "⚠ New password must be different from current password.",
                    "var(--color-errorMsg)"
                );

                flagFields([newPass]);

                return;
            }

            saveBtn.disabled = true;
            saveBtn.textContent = "Saving...";
        });
    }

    // DISABLE ACCOUNT
    if (disableBtn) {

        disableBtn.addEventListener("click", () => {

            const confirmed = confirm(
                "Are you sure you want to disable your ArcherFind account?\n\nThis action cannot be undone."
            );

            if (confirmed) {
                document
                    .getElementById("disableAccountForm")
                    .submit();
            }

        });

    }

    // Helper: flash red border on invalid fields
    function flagFields(fields) {
        fields.forEach((field) => {
            field.style.border = "1px solid var(--color-errorMsg)";
            clearTimeout(field._errorTimeout);
            field._errorTimeout = setTimeout(() => {
                field.style.border = "";
            }, 1500);
        });
    }
});

// SHOW TOASTS AFTER REDIRECT
document.addEventListener("DOMContentLoaded", () => {

    const params = new URLSearchParams(window.location.search);

    if (params.get("success") === "password_updated") {
        showToast("✓ Password updated successfully.");
    }

    if (params.get("error") === "empty_fields") {
        showToast(
            "⚠ Please fill out all password fields.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "password_mismatch") {
        showToast(
            "⚠ New password and confirmation do not match.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "wrong_password") {
        showToast(
            "⚠ Current password is incorrect.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "same_password") {
        showToast(
            "⚠ New password must be different from your current password.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "disable_failed") {
    showToast(
        "⚠ Unable to disable your account.",
        "var(--color-errorMsg)"
        );
    }

});