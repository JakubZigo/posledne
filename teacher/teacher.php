<?php
session_start();
require_once "../helpers/checkers.php";
require_once "../db/config.php";
require_once "../parse.php";
setLang();
require_once "../helpers/" . $_SESSION["lang"];


$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Table 1
$query = "SELECT name, enabled, max_points FROM latex";
$stmt = $db->query($query);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Table 2
$names = "SELECT id, firstname, surname FROM users WHERE role='student'";
$stmt1 = $db->query($names);
$nameResults = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$grades = "SELECT student_id, COUNT(student_id) as 'generated', SUM(submitted) as 'submitted',  SUM(points) as pointsSum FROM answer GROUP BY student_id";
$stmt2 = $db->query($grades);
$gradesResults = $stmt2->fetchAll(PDO::FETCH_ASSOC);


$combinedData = [];
foreach ($nameResults as $nameResult) {
    if (empty($gradesResults)) {
        $combinedData[] = array_merge($nameResult, ["generated"=> 0, "submitted"=> 0, "pointsSum"=> 0]);
        continue;
    }
    foreach ($gradesResults as $gradeResult) {
        if ($nameResult['id'] === $gradeResult['student_id']) {
            $combinedData[] = array_merge($nameResult, $gradeResult);
        } else {
            $combinedData[] = array_merge($nameResult, [0, 0, 0]);
        }
    }
}

// Table 3
$table2 = "SELECT users.firstname, users.surname, question.name as 'question', answer.submitted as 'submitted', answer.answer, answer.points as 'points' FROM users JOIN answer ON answer.student_id = users.id JOIN question ON question.id = answer.question_id";
$stmt = $db->query($table2);
$table2Results = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['parse'])) {
        parseFiles($db);
    }
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

    if (isset($_POST['deadlineId']) && isset($_POST['input1']) && isset($_POST['input2'])) {
        $name = $results[$_POST["deadlineId"]]["name"];
        $from = $_POST["input1"];
        $until = $_POST["input2"];

        if ($from <= $until) {
            $stmt = $db->prepare("UPDATE latex SET `enabled`= :enabled, `from` = :from, `until` = :until WHERE name = :name");
            $stmt->bindParam(':name', $name);

            $dateNow = date("Y-m-d");
            if ($dateNow < $from || $dateNow > $until) {
                $enabledVal1 = 0;
            } else {
                $enabledVal1 = 1;
            }
            $stmt->bindParam(':enabled', $enabledVal1);
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':until', $until);
            $stmt->execute();
        }
    }

    if (isset($_POST['nullId'])) {
        $name = $results[$_POST["nullId"]]["name"];
        $stmt = $db->prepare("UPDATE latex SET `enabled`= :enabled, `from` = :from, `until` = :until WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $enabledVal1 = 1;
        $stmt->bindParam(':enabled', $enabledVal1);
        $from = null;
        $stmt->bindParam(':from', $from);
        $until = null;
        $stmt->bindParam(':until', $until);
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

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

    <div>
        <h2>Parse</h2>
        <?php
        echo '<form action="teacher.php" method="post">
                <input type="hidden" name="parse" value="parse">
                <input type="submit" value="Parse">
              </form>';
        ?>
    </div>

    <h2><br>
        <th><?php echo $lan['files'] ?></th>:</br></h2>
    <table id="enableTable">
        <thead>
        <tr>
            <th><?php echo $lan['file'] ?></th>
            <th><?php echo $lan['status'] ?></th>
            <th><?php echo $lan['enable'] ?></th>
            <th><?php echo $lan['disable'] ?></th>
            <th></th>
            <th><?php echo $lan['points'] ?></th>
            <th></th>
            <th><?php echo $lan['from'] ?></th>
            <th><?php echo $lan['until'] ?></th>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php
        //        table 1
        for ($i = 0;
             $i < count($results);
             $i++) {
            echo '<tr>';
            echo '<td>' . $results[$i]["name"] . '</td>';
            if ($results[$i]["enabled"] == 1) {
                echo '<td>' . $lan['enabled'] . '</td>';
            } else {
                echo '<td>' . $lan['disabled'] . '</td>';
            }
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
            echo '<form action="teacher.php" method="post">
                <input type="hidden" name="deadlineId" value="' . $i . '">
                <td><input type="date" name="input1"></td>
                <td><input type="date" name="input2"></td>
                <td><input type="submit" value=' . $lan['submitDate'] . '></td>
              </form>';
            echo '<td><form action="teacher.php" method="post">
                    <input type="hidden" name="nullId" value="' . $i . '">
                    <input type="submit" value=' . $lan['free'] . '>
                </form></td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>

    <h2><br>
        <th><?php echo $lan['students'] ?></th>
        :</br></h2>

    <div style="background-color: #FAC898; color: #2F4858; margin-bottom: 4rem;">
        <?php
        //        table 2
        echo '<table id="myTable" class="display nowrap"">';
        echo '<thead>';
        echo '<tr><th>ID</th><th>' . $lan['firstname'] . '</th><th>' . $lan['surname'] . '</th><th>' . $lan['generated'] . '</th><th>' . $lan['submitted'] . '</th><th>' . $lan['points'] . '</th></tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($combinedData as $row) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['firstname'] . '</td>';
            echo '<td>' . $row['surname'] . '</td>';
            if ($row['generated'] == 0) {
                echo '<td>0</td>';
            } else {
                echo '<td>' . $row['generated'] . '</td>';
            }
            if ($row['submitted'] == 0) {
                echo '<td>0</td>';
            } else {
                echo '<td>' . $row['submitted'] . '</td>';
            }

            if ($row['pointsSum'] == 0) {
                echo '<td>0</td>';
            } else {
                echo '<td>' . $row['pointsSum'] . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        ?>
    </div>

    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>

    <h2><br>
        <th><?php echo $lan['exercises'] ?></th>
        :</br></h2>
    <div style="background-color: #FAC898; color: #2F4858;">
        <?php
        echo '<table id="myTable2" class="display nowrap"">';
        echo '<thead>';
        echo '<tr><th>' . $lan['student'] . '</th><th>' . $lan['exercise'] . '</th><th>' . $lan['submitted'] . '</th><th>' . $lan['correct'] . '</th><th>' . $lan['answer'] . '</th><th>' . $lan['points'] . '</th></tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($table2Results as $row) {
            echo '<tr>';
            echo '<td>' . $row['firstname'] . " " . $row['surname'] . '</td>';
            echo '<td>' . $row['question'] . '</td>';

            if ($row['submitted'] == 1) {
                echo '<td>✓</td>';
            } else {
                echo '<td>✕</td>';
            }


            if ($row['points'] == null) {
                echo '<td>' . $lan['empty'] . '</td>';
            } else if ($row["points"] == 0) {
                echo '<td>✕</td>';
            } else {
                echo '<td>✓</td>';
            }

            if ($row['answer'] == null) {
                echo '<td>' . $lan['empty'] . '</td>';
            } else {
                echo '<td>' . $row['answer'] . '</td>';
            }
            if ($row['points'] < 1) {
                echo '<td>0</td>';
            } else {
                echo '<td>' . $row['points'] . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        ?>
    </div>

    <script>
        $(document).ready(function () {
            $('#myTable2').DataTable({});
        });
    </script>

</body>