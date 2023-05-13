<?php

function checkEmpty($field) {
    if (empty(trim($field))) {
        return true;
    }
    return false;
}
function setLang() {
    if (isset($_SESSION["lang"])) {
        if (isset($_POST["lan"])) {
            $_SESSION["lang"] = ($_SESSION["lang"] == "sk.php") ? "en.php" : "sk.php";
        }
    }else{
        $_SESSION["lang"] = "sk.php";
    }
}
