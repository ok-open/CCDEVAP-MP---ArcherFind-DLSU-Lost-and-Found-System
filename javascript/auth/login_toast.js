document.addEventListener("DOMContentLoaded", () => {

    const error = document.body.dataset.error;
    const params = new URLSearchParams(window.location.search);
    const success = params.get("success");

    // SUCCESS MESSAGES
    if (success === "account_disabled") {
        showToast(
            "✓ Your account has been disabled successfully.",
            "var(--color-successMsg)"
        );
        return;
    }

    if (success === "registered") {
        showToast(
            "✓ Account created successfully. You can now log in.",
            "var(--color-successMsg)"
        );
        return;
    }

    if (!error) return;

    switch (error) {

        case "empty_fields":
            showToast(
                "⚠ Please fill in all fields.",
                "var(--color-errorMsg)"
            );
            break;

        case "invalid_email":
            showToast(
                "⚠ Invalid Credentials.",
                "var(--color-errorMsg)"
            );
            break;

        case "account_not_found":
            showToast(
                "⚠ Invalid Credentials.",
                "var(--color-errorMsg)"
            );
            break;

        case "wrong_password":
            showToast(
                "⚠ Invalid Credentials.",
                "var(--color-errorMsg)"
            );
            break;

        case "unauthorized":
            showToast(
                "⚠ Unauthorized access.",
                "var(--color-errorMsg)"
            );
            break;
    }

});