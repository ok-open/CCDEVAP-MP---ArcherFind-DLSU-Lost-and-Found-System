(function () {
    function createModal(options) {
        const {
            modalId,
            textId,
            cancelId,
            confirmId,
            confirmText = "Yes",
            message = ""
        } = options || {};

        const modal = document.getElementById(modalId);
        const modalText = document.getElementById(textId);
        const cancelBtn = document.getElementById(cancelId);
        const confirmBtn = document.getElementById(confirmId);

        if (!modal || !modalText || !cancelBtn || !confirmBtn) {
            return {
                open() {},
                close() {},
                onConfirm() {}
            };
        }

        function setMessage(text) {
            modalText.textContent = text;
        }

        function open() {
            if (typeof message === "function") {
                setMessage(message());
            } else {
                setMessage(message);
            }
            modal.hidden = false;
        }

        function close() {
            modal.hidden = true;
        }

        function onConfirm(handler) {
            confirmBtn.addEventListener("click", () => {
                close();
                handler();
            });
        }

        cancelBtn.addEventListener("click", close);
        confirmBtn.textContent = confirmText;

        return { open, close, onConfirm };
    }

    window.createModal = createModal;
})();
