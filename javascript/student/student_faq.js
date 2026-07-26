// FAQ
document.querySelectorAll(".question-box").forEach(box => {
    const btn = box.querySelector(".question button");
    const question = box.querySelector(".question");
    const answerWrapper = box.querySelector(".answer-wrapper");

    btn.addEventListener("click", () => {
        question.classList.toggle("toggle");
        answerWrapper.classList.toggle("open");
    });
});