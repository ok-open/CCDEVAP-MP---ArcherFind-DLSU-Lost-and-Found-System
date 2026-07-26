document.addEventListener("DOMContentLoaded", () => {

    const registerBtn = document.getElementById("register-submit");
    const registerForm = registerBtn ? registerBtn.closest("form") : null;

    /*
    |--------------------------------------------------------------------------
    | Register Form Validation
    |--------------------------------------------------------------------------
    */

    if (registerForm) {

        registerForm.addEventListener("submit", (e) => {

            const firstName = document.getElementById("firstname");
            const lastName = document.getElementById("lastname");
            const email = document.getElementById("email");
            const password = document.getElementById("pass");
            const confirmPassword = document.getElementById("confirm-pass");

            const fields = [
                firstName,
                lastName,
                email,
                password,
                confirmPassword
            ];

            // Empty fields
            const emptyFields = fields.filter(field => !field.value.trim());

            if (emptyFields.length > 0) {
                e.preventDefault();

                showToast(
                    "⚠ Please complete all required fields.",
                    "var(--color-errorMsg)"
                );

                flagFields(emptyFields);
                return;
            }

            // DLSU email validation
            if (!email.value.trim().toLowerCase().endsWith("@dlsu.edu.ph")) {
                e.preventDefault();

                showToast(
                    "⚠ Please use your DLSU email address.",
                    "var(--color-errorMsg)"
                );

                flagFields([email]);
                return;
            }

            // Password confirmation
            if (password.value !== confirmPassword.value) {
                e.preventDefault();

                showToast(
                    "⚠ Passwords do not match.",
                    "var(--color-errorMsg)"
                );

                flagFields([password, confirmPassword]);
                return;
            }

            // Prevent multiple submissions
            registerBtn.disabled = true;
            registerBtn.textContent = "Registering...";

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Helper: Highlight Invalid Fields
    |--------------------------------------------------------------------------
    */

    function flagFields(fields) {

        fields.forEach((field) => {

            field.style.border = "1px solid var(--color-errorMsg)";

            clearTimeout(field._errorTimeout);

            field._errorTimeout = setTimeout(() => {
                field.style.border = "";
            }, 1500);

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Toast Messages After Redirect
    |--------------------------------------------------------------------------
    */

    const params = new URLSearchParams(window.location.search);

    // NOTE: "success=registered" is no longer handled here — the controller
    // redirects to index.php after a successful registration, not back to
    // register.php, so this page never reloads with that param. That toast
    // needs to move to whatever script index.php uses (e.g. login_toast.js).

    if (params.get("error") === "empty_fields") {
        showToast(
            "⚠ Please complete all required fields.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "invalid_email") {
        showToast(
            "⚠ Please use your DLSU email address.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "password_mismatch") {
        showToast(
            "⚠ Passwords do not match.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "email_exists") {
        showToast(
            "⚠ An account with this email already exists.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "registration_failed") {
        showToast(
            "⚠ Registration failed. Please try again.",
            "var(--color-errorMsg)"
        );
    }

});