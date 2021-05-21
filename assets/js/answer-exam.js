$(document).ready(function () {
    $('.carousel').carousel({
        interval: false,
    });
    $(".carousel-item").first().addClass("active");
    document.addEventListener("keydown", function (event) {
        const key = event.key; // Or const {key} = event; in ES6+
        if (key === "Escape") {
            alert("Escape is not allowed!");
            return false;
        }
    });
});

function updateQuestionButtonStatus(questionNumber) {
    $("#questionLink-" + questionNumber).removeClass('btn-primary');
    $("#questionLink-" + questionNumber).addClass('btn-success');
}