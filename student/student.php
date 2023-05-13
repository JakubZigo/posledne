<?php
session_start();
require_once "../helpers/checkers.php";

setLang();

require_once "../helpers/" . $_SESSION["lang"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../base.css">

    <title>Student</title>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <a href="../sign_in/sign_out.php"><i class="bi bi-door-closed" style="font-size: 2rem;"></i></a>
        <form method="post">
            <input type="hidden" name="lan" value="1">
            <button type="submit" id="lan"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
        </form>
    </div>
</div>

<!--<div class="position-absolute top-0 start-50">-->
<!--    <form method="post">-->
<!--        <input type="hidden" name="lan" value="1">-->
<!--        <button type="submit" id="lan"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>-->
<!--        <a class="rounded" href="../sign_in/sign_out.php"><i class="bi bi-door-closed" style="font-size: 4rem;"></i></a>-->
<!--    </form>-->
<!--</div>-->
<div class="container center">
    <h1 class="big-heading"><?php echo $lan['student']?></h1>
</div>
</body>
