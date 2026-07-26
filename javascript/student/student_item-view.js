document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".controls");

    if (!form) return;

    const search = form.querySelector("[name='search']");
    const sort = form.querySelector("[name='sort']");
    const category = form.querySelector("[name='category']");
    let searchTimeout;

    if (search) {
        search.addEventListener("input", () => {
            window.clearTimeout(searchTimeout);
            searchTimeout = window.setTimeout(() => form.requestSubmit(), 250);
        });
    }

    sort.addEventListener("change", () => form.requestSubmit());
    category.addEventListener("change", () => form.requestSubmit());
});