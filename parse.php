<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'db/config.php';


function parseLatexFileToDb($db, $name) {

    $texContent = file_get_contents('zadanie99/'.$name);

    preg_match_all('/\\\\section\*\{(.*?)\}\s*\\\\begin\{task\}(.*?)\\\\end\{task\}\s*\\\\begin\{solution\}(.*?)\\\\end\{solution\}/s', $texContent, $matches, PREG_SET_ORDER);


    $sql2 = "INSERT INTO latex (name, latex, enabled, max_points) VALUES (:name, :latex, :enabled, :max_points)";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt2->bindValue(":latex", $texContent, PDO::PARAM_STR);
    $stmt2->bindValue(":enabled", true, PDO::PARAM_BOOL);
    $stmt2->bindValue(":max_points", sizeof($matches), PDO::PARAM_INT);
    if ($stmt2->execute()) {
//        echo "Stranka ulozena.";
    } else {
        echo "Ups. Nieco sa pokazilo";
    }
    unset($stmt2);

    $latexId = findLatestRow($db, $name);

    foreach ($matches as $match) {

        $sql = "INSERT INTO question (name, question, solution, file_name, latex_id) VALUES (:name, :html, :solution, :file_name, :latex_id)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":name", $match[1], PDO::PARAM_STR);
        $stmt->bindParam(":html", $match[2], PDO::PARAM_STR);
        $stmt->bindParam(":solution", $match[3], PDO::PARAM_STR);
        $stmt->bindParam(":file_name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":latex_id", $latexId, PDO::PARAM_INT);

        if ($stmt->execute()) {
//        echo "Stranka ulozena.";
        } else {
            echo "Ups. Nieco sa pokazilo";
        }
        unset($stmt);

    }
}

function findLatestRow($db, $name) {
    // Funkcia ziska id z databazy podla mena.
    $id = -1;

    $sql = "SELECT id FROM latex WHERE name = :name ORDER BY id DESC LIMIT 1";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        // Uzivatel existuje, skontroluj heslo.
        $row = $stmt->fetch();
        $id = $row["id"];
    } else {
        echo "Nenachadza sa v tabulke alebo je duplicitne.";
    }
    return $id;
}

//function getQuestionsFromDbByFileName($db, $name) {
//    $tasks = array();
//
//    $sql = "SELECT id, name, question, solution, file_name FROM question WHERE file_name = :file_name";
//    $stmt = $db->prepare($sql);
//    $stmt->bindParam(":file_name", $name, PDO::PARAM_STR);
//    $stmt->execute();
//
//    if ($stmt->rowCount() > 0) {
//        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach ($rows as $row) {
//            $task = array();
//            $task['id'] = $row["id"];
//            $task['name'] = $row["name"];
//            $task['question'] = $row["question"];
//            $task['solution'] = $row["solution"];
//
//            $tasks[] = $task;
//        }
//    } else {
//        echo "Nenachadza sa v tabulke alebo je duplicitne.";
//    }
//
//    return $tasks;
//}
function getQuestionFromDbById($db, $id) {

    $sql = "SELECT id, name, question, solution, file_name FROM question WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();

        return $row;
    } else {
        echo "Nenachadza sa v tabulke alebo je duplicitne.";
    }
}




//function printRandomQuestionFromFileName($db, $fileName) {
////    need to include these libraries in html
////    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
////    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
//
//    $questions = getQuestionsFromDbByFileName($db, $fileName);
//
//    $q = $questions[array_rand($questions)];
//
//    $question = $q["question"];
//    $solution = $q['solution'];
//    $name = $q['name'];
//
//    // Replace \includegraphics with an img tag, assuming the path is correct
//    $question = preg_replace('/\\\\includegraphics\{(.*?)\}/', '<img src="$1" style="width: 50%"/>', $question);
//
//    // Find and modify LaTeX expressions in $question and $solution
//    $question = preg_replace_callback('/\$\$?(.*?)\$\$?/', function($matches) {
//        if (substr($matches[0], 0, 2) === '$$') {
//            return $matches[0];
//        } else {
//            return '$$' . $matches[1] . '$$';
//        }
//    }, $question);
//
//    $solution = preg_replace_callback('/\$\$?(.*?)\$\$?/', function($matches) {
//        if (substr($matches[0], 0, 2) === '$$') {
//            return $matches[0];
//        } else {
//            return '$$' . $matches[1] . '$$';
//        }
//    }, $solution);
//
//    echo '<div>';
//    echo '<h2>' . $name . '</h2>';
//    echo $question;
//    echo '<div>'."Správná odpověď: ".$solution.'<div>';
//    echo '</div>';
//}


function printQuestionById($db, $qId) {
//    need to include these libraries in html
//    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
//    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    $q = getQuestionFromDbById($db, $qId);

    $question = $q["question"];
    $solution = $q['solution'];
    $name = $q['name'];

    // Replace \includegraphics with an img tag, assuming the path is correct
    $question = preg_replace('/\\\\includegraphics\{(.*?)\}/', '<img src="../$1" style="width: 50%"/>', $question);

    // Find and modify LaTeX expressions in $question and $solution
    $question = preg_replace_callback('/\$\$?(.*?)\$\$?/', function($matches) {
        if (substr($matches[0], 0, 2) === '$$') {
            return $matches[0];
        } else {
            return '$$' . $matches[1] . '$$';
        }
    }, $question);

    $solution = preg_replace_callback('/\$\$?(.*?)\$\$?/', function($matches) {
        if (substr($matches[0], 0, 2) === '$$') {
            return $matches[0];
        } else {
            return '$$' . $matches[1] . '$$';
        }
    }, $solution);

    echo '<div>';
    echo '<h2>' . $name . '</h2>';
    echo $question;
//    echo '<div>'."Správná odpověď: ".$solution.'<div>';
    echo '</div>';

    return $solution;
}

?>

<!--<!DOCTYPE html>-->
<!--<html>-->
<!--<head>-->
<!--    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>-->
<!--    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>-->
<!--</head>-->
<!--<style>-->
<!--    mjx-container[jax="CHTML"][display="true"] {display: contents !important;}-->
<!--</style>-->
<!--<body>-->
<!---->
<!---->
<!---->
<!--    --><?php
//
//    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//
////    parseLatexFileToDb($pdo, "blokovka01pr.tex");
////    parseLatexFileToDb($pdo, "blokovka02pr.tex");
////    parseLatexFileToDb($pdo, "odozva01pr.tex");
////    parseLatexFileToDb($pdo, "odozva02pr.tex");
//
////    printRandomQuestionFromFileName($pdo, "blokovka01pr.tex");
////    printRandomQuestionFromFileName($pdo, "blokovka02pr.tex");
////    printRandomQuestionFromFileName($pdo, "odozva01pr.tex");
////    printRandomQuestionFromFileName($pdo, "odozva02pr.tex");
//
//    printQuestionById($pdo, 35);
//    ?>
<!---->
<!--</body>-->
<!--</html>-->



