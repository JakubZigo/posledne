
<?php
require_once "../db/Database.php";

function getTask($id, $lan){
    $db = new Database();
    $db = $db->getConnection();
    $sql = "SELECT * FROM answer WHERE answer_id = (?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    $sql = "SELECT * FROM question WHERE id = (?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$row['question_id']]);
    $question = $stmt->fetch();

    $sql = "SELECT * FROM latex WHERE id = (?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$question['latex_id']]);
    $latex = $stmt->fetch();

    echo '
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body h-50 rounded">
                <div class="container text-center">
                    <div class="row">
                        <div class="col-9">
                            <h5 class="card-title">' . $question['name'] . '</h5>
                        </div>
                        <div class="col-3">';

                        if ($row['submitted'] == 1) {
                            echo '<i class="bi bi-check-circle-fill darkIcon"></i></div></div><div class="row">';
                            if ($row['points'] == NULL) {
                                echo'<p class="card-text">' . $lan['score'] . '? / ' . $latex['max_points'] . '</p>';
                            } else {
                                echo'<p class="card-text">' . $lan['score'] . $row['points'] . ' / ' . $latex['max_points'] . '</p>';
                            }
                        echo '</div>';
                        } else {
                            echo '<i class="bi bi-x-circle-fill darkIcon"></i>
                                <a href="../student/submitTask.php?id=' . $row['question_id'] . '"><i class="bi bi-pencil-square darkIcon"></i></a>
                            </div>
                        </div>
                        <div class="row">
                            <p class="card-text">' . $row['date'] . '</p>
                        </div>';
                        }

            echo '
                    </div>
                </div>
            </div>
        </div>';

}
