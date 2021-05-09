<!DOCTYPE html>
<html>
<head>
    <title>exam.online</title>
    <link rel="stylesheet" type="text/css" href="assets/css/view-exam.css" />
    <script type="text/javascript" src="lib/js/jquery.min.js"></script>
</head>
<body>
    <div>
        <input type="button" value="SELECT" id="viewExam" />
    </div>
    <?php include("view-exam.php") ?>
    <script>
        // $(document).on("contextmenu", function (e) {        
        //     e.preventDefault();
        // });
        // $(document).keydown(function (event) {
        //     if (event.keyCode === 123 ||  // Prevent F12
        //         (event.ctrlKey && event.shiftKey && event.keyCode === 73)  // Prevent Ctrl+Shift+I  
        //     ) { // Prevent F12
        //         return false;
        //     }
        // });
        $(document).ready(function(){
            $("#viewExamContainer").hide();
            $("#viewExam").click(function(e){
                e.preventDefault();
                var elem = document.getElementById("viewExamContainer");
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) { /* Safari */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE11 */
                    elem.msRequestFullscreen();
                }
                $("#viewExamContainer").show();
            });

            document.addEventListener("keydown", function(event) {
                const key = event.key; // Or const {key} = event; in ES6+
                if (key === "Escape") {
                    alert("Escape is not allowed!");
                    return false;
                }
            });
        });
    </script>
</body>
</html>