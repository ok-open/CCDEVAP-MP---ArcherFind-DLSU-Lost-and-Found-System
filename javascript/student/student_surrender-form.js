document.addEventListener("DOMContentLoaded", () => {
    const TOAST_DURATION = 6000;
    const form = document.querySelector(".form-wrapper");
    const nameInput = document.querySelector('input[name="name"]');

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
        message: () => `Are you sure you want to submit this item: ${getItemName()}?`
    });

    function getItemName() {
        const typedName = nameInput?.value?.trim();
        return typedName ? typedName : "this item";
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

    function resetLocationSelections() {
        room.selectedIndex = 0;
        area.selectedIndex = 0;
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

            // No category picked yet → show every brand.
            // Otherwise only show brands linked to the selected category.
            const visible =
                !selectedCategory || categoryIds.includes(selectedCategory);

            option.hidden = !visible;
            option.disabled = !visible;
        });
    }

    function resetBrandSelection() {
        if (brand) brand.selectedIndex = 0;
    }

    // ==========================================
    // BUILDING CHANGED
    // ==========================================

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

    // ==========================================
    // CATEGORY CHANGED
    // ==========================================

    if (category) {
        category.addEventListener("change", () => {
            resetBrandSelection();
            filterBrands();
        });
    }

    filterLocations();
    filterBrands();

    form.addEventListener("submit", (e) => {
        if (form.dataset.confirmed === "true") {
            form.dataset.confirmed = "";
            return;
        }

        e.preventDefault();

        const hasRoom = room.value !== "";
        const hasArea = area.value !== "";
        const hasLocation = hasRoom || hasArea;

        if (!building.value || !floor.value || !hasLocation) {
            showToast(
                "⚠ Please complete all required fields.",
                "var(--color-errorMsg)"
            );
            return;
        }

        if (!form.reportValidity()) {
            return;
        }

        modal.open();
    });

    const params = new URLSearchParams(window.location.search);

    if (params.get("success") === "submitted") {
        const itemName = params.get("item");
        const message = itemName
            ? `✓ Surrender form for "${itemName}" submitted successfully!`
            : "✓ Surrender form submitted successfully!";
        showToast(message, "var(--color-successMsg)", TOAST_DURATION);
    }

    if (params.get("error") === "failed") {
        showToast("⚠ Failed to submit surrender form. Please try again.", "var(--color-errorMsg)", TOAST_DURATION);
    }
});