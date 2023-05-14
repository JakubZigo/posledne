<?php
require_once "../db/config.php";
try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    return $e->getMessage();
}

$userInput = $_POST['input'];
$correctAnswer = $_POST['answer'];
$studentId = $_POST['student_id'];
$questionId = $_POST['question_id'];
$command = "python3 /var/www/site250.webte.fei.stuba.sk/posledne/student/compare.py $userInput $correctAnswer";
$result = shell_exec($command);

//$userInput = $_POST['input'];
//$correctAnswer = $_POST['answer'];
//$command = "python3 /var/www/site250.webte.fei.stuba.sk/posledne/student/compare.py";
//$descriptorspec = array(
//    0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//    1 => array("pipe", "w")   // stdout is a pipe that the child will write to
//);
//$process = proc_open($command, $descriptorspec, $pipes);
//if (is_resource($process)) {
//    fwrite($pipes[0], $userInput . "\n" . $correctAnswer);
//    fclose($pipes[0]);
//    $result = stream_get_contents($pipes[1]);
//    fclose($pipes[1]);
//    $return_value = proc_close($process);
//}

if (trim($result) == 'True') {
    $pointQuery = $db->prepare("SELECT * FROM latex JOIN question ON latex.id = question.latex_id WHERE question.id = :question_id;");
    $pointQuery->bindParam("question_id", $questionId, PDO::PARAM_INT);
    $pointQuery->execute();
    $row = $pointQuery->fetch();
    $max_points = $row['max_points'];
    $query = $db->prepare("UPDATE answer SET answer = :user_input, points = :max_points, submitted = true WHERE student_id = :student_id AND question_id = :question_id");
    $query->bindParam(":student_id", $studentId);
    $query->bindParam(":question_id", $questionId);
    $query->bindParam(":user_input", $userInput);
    $query->bindParam(":max_points", $max_points, PDO::PARAM_INT);
    $query->execute();
    echo 1;
} else {
    $query = $db->prepare("UPDATE answer SET answer = :user_input, points = 0, submitted = true WHERE student_id = :student_id AND question_id = :question_id");
    $query->bindParam(":student_id", $studentId);
    $query->bindParam(":question_id", $questionId);
    $query->bindParam(":user_input", $userInput);
    $query->execute();
    echo 0;
}