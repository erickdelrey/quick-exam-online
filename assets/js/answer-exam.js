$(document).ready(function () {
    $("#answerExamBody").hide();
    $('#reminderModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#reminderModal').modal('show');
    $('.slider').slick({
        infinite: false,
        slideToShow: 1,
        slideToScroll: 1
    });
    document.addEventListener("keydown", function (event) {
        const key = event.key; // Or const {key} = event; in ES6+
        if (key === "Escape") {
            //alert("Escape is not allowed!");
            return false;
        }
    });
});

function updateQuestionButtonStatus(questionNumber) {
    $("#questionLink-" + questionNumber).removeClass('btn-secondary');
    $("#questionLink-" + questionNumber).addClass('btn-success');
}

function slideToQuestion(questionNumber) {
    $('.slider').slick('slickGoTo', questionNumber);
}