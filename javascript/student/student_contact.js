document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector(".contact-form-details");

    if (!form) return;

    form.addEventListener("submit", (e) => {

        const inquiry = form.querySelector("[name='inquiry_type']");
        const message = form.querySelector("[name='message']");

        if (!inquiry.value.trim()) {
            e.preventDefault();

            showToast(
                "⚠ Please select an inquiry.",
                "var(--color-errorMsg)"
            );

            inquiry.focus();
            return;
        }

        if (!message.value.trim()) {
            e.preventDefault();

            showToast(
                "⚠ Please enter your message.",
                "var(--color-errorMsg)"
            );

            message.focus();
            return;
        }

    });

    // Toasts after redirect
    const params = new URLSearchParams(window.location.search);

    if (params.get("success") === "message_sent") {
        showToast(
            "✓ Your message has been sent successfully.",
            "var(--color-successMsg)"
        );
    }

    if (params.get("error") === "empty_fields") {
        showToast(
            "⚠ Please complete all fields.",
            "var(--color-errorMsg)"
        );
    }

    if (params.get("error") === "send_failed") {
        showToast(
            "⚠ Unable to send your message. Please try again.",
            "var(--color-errorMsg)"
        );
    }

});