<?php
session_start();
require_once "../helpers/checkers.php";
require_once "../helpers/getters.php";
require_once "../db/Database.php";
include "../parse.php";
setLang();
require_once "../helpers/" . $_SESSION["lang"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("location: ../sign_in/sign_in.php");
}

require_once "../db/Database.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../sign_in/sign_in.css">
    <link rel="stylesheet" href="https://unpkg.com/mathlive@0.93.0/dist/mathlive-fonts.min.css">
    <link rel="stylesheet" href="https://unpkg.com/mathlive@0.93.0/dist/mathlive-static.min.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <title>Submit answer</title>
</head>
<style>
    mjx-container[jax="CHTML"][display="true"] {
        display: contents !important;
    }
</style>
<body>
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <form method="post">
            <input type="hidden" name="lan" value="1">
            <button type="submit" id="lan"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
        </form>
        <h1 class="big-heading"><?php echo $_SESSION['name']?></h1>
        <a href="../sign_in/sign_out.php"><i class="bi bi-door-closed" style="font-size: 2rem;"></i></a>
    </div>
</div>
<div class="container">
    <div class="row">
        <?php
        if (isset($_GET["id"])) {
            $question_id = $_GET["id"];
            $db = new Database();
            $db = $db->getConnection();
            $answer = printQuestionById($db, $question_id);
            $answer = str_replace("\\", "\\\\", $answer);
            $answer = str_replace(array("\r\n", "\n", "\r"), '\n', $answer);
            $input =  str_replace("\n++++\\begin{equation*}\n++++++++", "", $answer);
            $input = str_replace("\n++++\\end{equation*}\n", "", $input);
        }
        ?>
    </div>
    <div class="row">
        <math-field id="equation-input" style="width: 20rem"></math-field>
        <button id="submit"><?php echo $lan['submit'] ?></button>
        <script src="https://unpkg.com/mathlive@0.93.0/dist/mathlive.min.js"></script>
        <script>
        const input = document.getElementById('equation-input');
        const expectedLatex = "<?php echo $input; ?>";
        console.log(expectedLatex);
        input.value = expectedLatex;

        document.getElementById('submit').onclick = async function() {
            let latex = input.value;
            latex = latex.replace("\\begin{equation*}", "").replace("\\end{equation*}", "").trim();
            let correct = expectedLatex.replace("\\begin{equation*}", "").replace("\\end{equation*}", "").trim();
            console.log(latex);
            console.log(correct);
            let formData = new URLSearchParams();
            formData.append("input", latex);
            formData.append("answer", correct);
            formData.append("student_id", <?php echo $_SESSION['id']?>);
            formData.append("question_id", <?php echo $question_id ?>);
            let equal;
            await fetch('latexEval.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
                .then(data => {
                    equal = data;
                })
                .catch(error => console.error('Error:', error));
            console.log(equal);
            if (equal === "1") {
                console.log("Correct");
            } else {
                console.log("Incorrect");
            }
        };
        </script>
    </div>
</div>
</body>