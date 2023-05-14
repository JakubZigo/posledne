<?php
session_start();
require_once "../helpers/checkers.php";
require_once "../db/config.php";
setLang();
require_once "../helpers/" . $_SESSION["lang"];


$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "SELECT name, enabled, max_points FROM latex";
$stmt = $db->query($query);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

//       id, name, question, solution, file_name, latex_id

$names = "SELECT id, firstname, surname FROM users WHERE role='student'";
$stmt = $db->query($names);
$nameResults = $stmt->fetchAll(PDO::FETCH_ASSOC);


//$studies = "SELECT  FROM users WHERE role='student'";
//$stmt = $db->query($studies);
//$studiesResults= $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['action'])) {
        $name = $results[$_POST["id"]]["name"];
        $stmt = $db->prepare("UPDATE latex SET enabled = :enabled WHERE name = :name");
        $stmt->bindParam(':name', $name);
        if ($_POST['action'] == "enable") {
            $enableVal = 1;
        } else if ($_POST['action'] == "disable") {
            $enableVal = 0;
        }
        $stmt->bindParam(':enabled', $enableVal);
        $stmt->execute();
    }

    if (isset($_POST['pointsId']) && isset($_POST['pointsChange'])) {
        $name = $results[$_POST["pointsId"]]["name"];
        $points = $results[$_POST["pointsId"]]["max_points"];
        $stmt = $db->prepare("UPDATE latex SET max_points = :max_points WHERE name = :name");
        $stmt->bindParam(':name', $name);

        if ($_POST['pointsChange'] == "plus") {
            $max_points = $points + 1;
        } else if ($_POST['pointsChange'] == "minus") {
            if ($points > 0) {
                $max_points = $points - 1;
            } else {
                $max_points = 0;
            }
        }
        $stmt->bindParam(':max_points', $max_points);
        $stmt->execute();
    }


    header("Refresh:0");
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
    <link rel="stylesheet" href="table.css">
    <title>Teacher</title>
</head>

<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <form method="post">
            <input type="hidden" name="lan" value="1">
            <button type="submit" id="lan"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
        </form>
        <a href="../sign_in/sign_out.php"><i class="bi bi-door-closed" style="font-size: 2rem;"></i></a>

    </div>
</div>
<div class="container center">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="big-heading"><?php echo $lan['teacher'] ?></h1>
    </div>
    <h2><br>
        <th><?php echo $lan['files'] ?></th>
        :</br></h2>
    <table id="enableTable">
        <thead>
        <tr>
            <th><?php echo $lan['file'] ?></th>
            <th><?php echo $lan['status'] ?></th>
            <th><?php echo $lan['enable'] ?></th>
            <th><?php echo $lan['disable'] ?></th>
            <th></th>
            <th><?php echo $lan['points'] ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i = 0; $i < count($results); $i++) {
            echo '<tr>';
            echo '<td>' . $results[$i]["name"] . '</td>';
            echo '<td>' . $results[$i]["enabled"] . '</td>';
            echo '<td><form action="teacher.php" method="post">
                    <input type="hidden" name="id" value="' . $i . '">
                    <input type="hidden" name="action" value="enable">
                    <input type="submit" value="   ✓   ">
                </form></td>';
            echo '<td><form action="teacher.php" method="post">
                    <input type="hidden" name="id" value="' . $i . '">
                    <input type="hidden" name="action" value="disable">
                    <input type="submit" value="   ✕   ">
                </form></td>';

            echo '<td><form action="teacher.php" method="post">
                    <input type="hidden" name="pointsId" value="' . $i . '">
                    <input type="hidden" name="pointsChange" value="minus">
                    <input type="submit" value="<--">
                </form></td>';
            echo '<td>' . $results[$i]["max_points"] . '</td>';
            echo '<td><form action="teacher.php" method="post">
                    <input type="hidden" name="pointsId" value="' . $i . '">
                    <input type="hidden" name="pointsChange" value="plus">
                    <input type="submit" value="-->">
                </form></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>



    <h2><br><th><?php echo $lan['students'] ?></th>:</br></h2>
    <div style="flex-direction: row;">Sort by:
    <?php
    echo '<form action="teacher.php" method="post">
            <input type="hidden" name="sort" value="id">
            <input type="submit" value="ID">
        </form>';

    echo '<form action="teacher.php" method="post">
            <input type="hidden" name="sort" value="name">
            <input type="submit" value="Name">
        </form>';

    echo '<form action="teacher.php" method="post">
              <input type="hidden" name="sort" value="surname">
              <input type="submit" value="Surname">
        </form>';

    echo '<form action="teacher.php" method="post">
            <input type="hidden" name="sort" value="surname">
            <input type="submit" value="Surname">
        </form>';
    ?>



    </div>
    <table id="enableTable">
        <thead>
        <tr>
            <th>ID</th>
            <th><?php echo $lan['firstname'] ?></th>
            <th><?php echo $lan['surname'] ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        for ($i = 0; $i < count($nameResults); $i++) {
            echo '<tr>';
            echo '<td>' . $nameResults[$i]["id"] . '</td>';
            echo '<td>' . $nameResults[$i]["firstname"] . '</td>';
            echo '<td>' . $nameResults[$i]["surname"] . '</td>';
            echo '</tr>';
        }
        echo '</tr>';
        ?>
        </tbody>
    </table>


</div>
</body>