document.addEventListener("DOMContentLoaded", () => {
    const TOAST_DURATION = 6000;
    const form = document.querySelector(".form-wrapper");

    if (!form) return;

    const building = document.getElementById("building_id");
    const floor = document.getElementById("floor_number");
    const room = document.getElementById("room_id");
    const area = document.getElementById("area_id");

    const category = document.getElementById("category");
    const brand = document.getElementById("brand-id");

    const modal = window.createModal({
        modalId: "confirm-modal",
        textId: "confirm-modal-text",
        cancelId: "confirm-modal-cancel",
        confirmId: "confirm-modal-yes",
        confirmText: "Yes",
        message: () => "Save changes to this report?"
    });

    modal.onConfirm(() => {
        form.dataset.confirmed = "true";
        form.submit();
    });

    // ==========================================
    // FILTER ROOMS AND AREAS
    // ==========================================
    function filterLocations() {
        if (!building || !floor || !room || !area) return;

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

    function resetLocationSelections() {
        room.selectedIndex = 0;
        area.selectedIndex = 0;
    }

    if (building) {
        building.addEventListener("change", () => {
            floor.value = "";
            resetLocationSelections();

            const selected = building.options[building.selectedIndex];
            floor.max = selected.dataset.maxLevel || "";

            filterLocations();
        });

        floor.addEventListener("input", () => {
            resetLocationSelections();
            filterLocations();
        });

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

        // Run once on load so the pre-filled Room/Area from the existing
        // report stays visible under the pre-filled Building/Floor
        filterLocations();
    }

    // ==========================================
    // FILTER BRANDS BY CATEGORY
    // ==========================================
    function filterBrands() {
        if (!category || !brand) return;

        const selectedCategory = category.value;

        Array.from(brand.options).forEach(option => {
            if (option.value === "") {
                option.hidden = false;
                option.disabled = false;
                return;
            }

            const categoryIds = (option.dataset.categories || "").split(",");

            const visible =
                !selectedCategory || categoryIds.includes(selectedCategory);

            option.hidden = !visible;
            option.disabled = !visible;
        });
    }

    if (category) {
        category.addEventListener("change", filterBrands);
        filterBrands();
    }

    // ==========================================
    // FORM SUBMISSION
    // ==========================================
    form.addEventListener("submit", (e) => {
        if (form.dataset.confirmed === "true") {
            form.dataset.confirmed = "";
            return;
        }

        e.preventDefault();

        if (!form.reportValidity()) {
            return;
        }

        modal.open();
    });

    // ==========================================
    // TOASTS AFTER REDIRECT
    // ==========================================
    const params = new URLSearchParams(window.location.search);

    if (params.get("error") === "not_found") {
        showToast("⚠ Report not found.", "var(--color-errorMsg)", TOAST_DURATION);
    }

    if (params.get("error") === "unauthorized") {
        showToast("⚠ You don't have permission to edit that report.", "var(--color-errorMsg)", TOAST_DURATION);
    }

    if (params.get("error") === "locked") {
        showToast("⚠ This report can no longer be edited — staff has already reviewed it.", "var(--color-errorMsg)", TOAST_DURATION);
    }
});