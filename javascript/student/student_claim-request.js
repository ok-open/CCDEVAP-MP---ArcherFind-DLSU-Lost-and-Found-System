document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector(".form-wrapper");

    if (!form) return;

    const building = document.getElementById("building_id");
    const floor = document.getElementById("floor_number");
    const room = document.getElementById("room_id");
    const area = document.getElementById("area_id");

    function showToast(message, color = "var(--color-successMsg)", duration = 6000) {
        if (typeof window.showToast === "function") {
            window.showToast(message, color, duration);
            return;
        }

        const toast = document.getElementById("toast");
        if (!toast) return;
        toast.textContent = message;
        toast.style.backgroundColor = color;
        toast.classList.add("show-toast", "show");
        setTimeout(() => toast.classList.remove("show-toast", "show"), duration);
    }

    const modal = window.createModal({
        modalId: "confirm-modal",
        textId: "confirm-modal-text",
        cancelId: "confirm-modal-cancel",
        confirmId: "confirm-modal-yes",
        confirmText: "Yes",
        message: () => `Are you sure you want to submit this item: ${getItemName()}?`
    });

    function getItemName() {
        const title = document.querySelector(".student-claim-request-title");
        const itemName = title?.textContent?.trim();
        return itemName ? itemName : "this item";
    }

    modal.onConfirm(() => {
        form.dataset.confirmed = "true";
        form.submit();
    });

    // ==========================================
    // FILTER ROOMS AND AREAS
    // ==========================================

    function filterLocations() {

        const selectedBuilding = building.value;
        const selectedFloor = floor.value;

        Array.from(room.options).forEach(option => {

            if (option.value === "") {
                option.hidden = false;
                option.disabled = false;
                return;
            }

            const visible =
                option.dataset.building === selectedBuilding &&
                option.dataset.level === selectedFloor;

            option.hidden = !visible;
            option.disabled = !visible;

        });

        Array.from(area.options).forEach(option => {

            if (option.value === "") {
                option.hidden = false;
                option.disabled = false;
                return;
            }

            const visible =
                option.dataset.building === selectedBuilding &&
                option.dataset.level === selectedFloor;

            option.hidden = !visible;
            option.disabled = !visible;

        });

    }

    // ==========================================
    // BUILDING CHANGED
    // ==========================================

    building.addEventListener("change", () => {

        floor.value = "";

        room.selectedIndex = 0;
        area.selectedIndex = 0;

        const selected =
            building.options[building.selectedIndex];

        floor.max = selected.dataset.maxLevel || "";

        filterLocations();

    });

    // ==========================================
    // FLOOR CHANGED
    // ==========================================

    floor.addEventListener("input", () => {

        room.selectedIndex = 0;
        area.selectedIndex = 0;

        filterLocations();

    });

    // Hide everything initially
    filterLocations();

    // ==========================================
    // ALLOW ONLY ONE LOCATION
    // ==========================================

    room.addEventListener("change", () => {

        if (room.value !== "") {
            area.value = "";
            area.disabled = true;
        } else {
            area.disabled = false;
        }

    });

    area.addEventListener("change", () => {

        if (area.value !== "") {
            room.value = "";
            room.disabled = true;
        } else {
            room.disabled = false;
        }

    });

    // ==========================================
    // FORM VALIDATION
    // ==========================================

    form.addEventListener("submit", (e) => {
        if (form.dataset.confirmed === "true") {
            form.dataset.confirmed = "";
            return;
        }

        const date = document.querySelector("[name='date_lost']");
        const time = document.querySelector("[name='time_lost']");
        const description = document.querySelector("[name='description']");

        let valid = true;

        if (building.value === "") {
            valid = false;
        }

        if (floor.value === "") {
            valid = false;
        }

        if (room.value === "" && area.value === "") {
            valid = false;
        }

        if (date.value === "") {
            valid = false;
        }

        if (time.value === "") {
            valid = false;
        }

        if (description.value.trim() === "") {
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            showToast(
                "⚠ Please complete all required fields.",
                "var(--color-errorMsg)"
            );
            return;
        }

        e.preventDefault();
        modal.open();

    });

    // ==========================================
    // SUCCESS / ERROR TOASTS
    // ==========================================
    const TOAST_DURATION = 6000;
    const params = new URLSearchParams(window.location.search);

    if (params.get("success") === "submitted") {
        const itemName = params.get("item");
        const message = itemName
            ? `✓ Claim request for "${itemName}" submitted successfully!`
            : "✓ Claim request submitted successfully!";
        showToast(message, "var(--color-successMsg)", TOAST_DURATION);
    }

    if (params.get("error") === "failed") {
        showToast(
            "⚠ Failed to submit claim request.",
            "var(--color-errorMsg)",
            TOAST_DURATION
        );
    }

});