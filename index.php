<?php
session_start();
require_once "helpers/checkers.php";

setLang();

require_once "helpers/" . $_SESSION["lang"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_SESSION["loggedIn"])){
    if ($_SESSION["role"] == "student")
        header("location: student/student.php");
    else
        header("location: teacher/teacher.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="base.css">

    <title>Matematiky</title>
</head>

<body>
<div class="container center">
    <h1 class="big-heading"><?php echo $lan['indexH1']?></h1>
    <a class="big-button rounded" href="sign_in/sign_in.php"><?php echo $lan['signInButton']?></a>

    <div class="d-flex justify-content-end w-100">
        <div>
            <button onclick="goToTutorialPage()" id="tut"><i class="bi bi-book m-lg-1" style="font-size: 2rem;"></i></button>
        </div>
        <script>
            function goToTutorialPage() {window.location.href = "tutorial_<?php print_r($_SESSION["lang"])?>";}
        </script>
        <div>
            <form method="post">
                <input type="hidden" name="lan" value="1">
                <button type="submit" id="lan"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
            </form>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
