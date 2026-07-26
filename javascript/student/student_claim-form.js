let slideIndices = {};

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".controls");

    if (!form) {
        return;
    }

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

    if (sort) {
        sort.addEventListener("change", () => form.requestSubmit());
    }

    if (category) {
        category.addEventListener("change", () => form.requestSubmit());
    }
});

function plusSlides(step, group) {
    if (slideIndices[group] === undefined) {
        slideIndices[group] = 1;
    }

    showSlides(slideIndices[group] += step, group);
}

function currentSlide(index, group) {
    showSlides(slideIndices[group] = index, group);
}

function showSlides(index, group) {
    const slides = document.getElementsByClassName("slide-group-" + group);
    const dots = document.getElementsByClassName("dot-group-" + group);

    if (slides.length === 0) {
        return;
    }

    if (slideIndices[group] === undefined) {
        slideIndices[group] = 1;
    }

    if (index > slides.length) {
        slideIndices[group] = 1;
    }

    if (index < 1) {
        slideIndices[group] = slides.length;
    }

    for (let i = 0; i < slides.length; i += 1) {
        slides[i].style.display = "none";
    }

    for (let i = 0; i < dots.length; i += 1) {
        dots[i].className = dots[i].className.replace(" active-img", "");
    }

    slides[slideIndices[group] - 1].style.display = "block";

    if (dots[slideIndices[group] - 1]) {
        dots[slideIndices[group] - 1].className += " active-img";
    }
}

window.plusSlides = plusSlides;
window.currentSlide = currentSlide;
