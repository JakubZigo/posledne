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

        }
        ?>
    </div>
    <div class="row">
        <math-field id="equation-input" style="width: 20rem"></math-field>
        <button id="submit">Submit</button>
        <script src="https://unpkg.com/mathlive@0.93.0/dist/mathlive.min.js"></script>
        <script>
        const input = document.getElementById('equation-input');
        const expectedLatex = "<?php echo $answer; ?>";
        console.log(expectedLatex);
        input.value = expectedLatex;

        document.getElementById('submit').onclick = function() {
            let latex = input.value;
            let formData = new URLSearchParams();
            formData.append("user", latex);
            formData.append("answer", expectedLatex);
            let equal = fetch('latexCompare.php', {
                method: 'POST',
                body: formData
            });
            if (equal) {
                console.log("Correct");
            } else {
                console.log("Incorrect");
            }
        };
        </script>
    </div>
</div>
</body>