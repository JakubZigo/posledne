<?php
session_start();
require_once "../helpers/checkers.php";
require_once "../helpers/getters.php";
require_once "../db/Database.php";
setLang();
require_once "../helpers/" . $_SESSION["lang"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("location: ../sign_in/sign_in.php");
}

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT id, name, max_points FROM latex WHERE enabled = 1");
$stmt->execute();
$rows = $stmt->fetchAll();

$stmt = $conn->prepare("SELECT * FROM answer WHERE student_id = (?)");
$stmt->execute([$_SESSION['id']]);
$tasks = $stmt->fetchAll();

if (isset($_POST['gen'])){
    if (!empty($_POST['checkboxes'])){
        $checked = $_POST['checkboxes'];
        $data = [];
        foreach ($checked as $check) {
            $stmt = $conn->prepare("SELECT id, name, question FROM question WHERE latex_id = ?");
            $stmt->execute([$check]);
            $data[] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if (!empty($data)) {
            $flatData = array_merge(...$data);

            $randomRow = $flatData[array_rand($flatData)];
            $sql = "INSERT INTO answer (student_id,	question_id, submitted) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_SESSION['id'], $randomRow['id'], 0]);
            header("Refresh:0");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../sign_in/sign_in.css">

    <title>Student</title>
</head>
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
    <form method="post" class="row mt-5 justify-content-between align-items-center" name="gen">
        <input type="hidden" name="gen" value="1">
        <div class="col">
            <button type="submit" class="rounded"><?php echo $lan['generate']?></button>
        </div>
        <div class="col">
            <?php
            foreach ($rows as $latex) {
                echo '
                    <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" name="checkboxes[]" id='.$latex['name'].' value="'.$latex['id'].'">
                          <label class="form-check-label" for='.$latex['name'].'>'.$latex['name'].'</label>
                    </div>
                    ';
            }
            ?>
        </div>
    </form>
</div>
<div class="container mt-4">
    <div class="row">
        <?php foreach ($tasks as $item){
            getTask($item['answer_id'], $lan);
        }?>
    </div>
</div>
</body>
