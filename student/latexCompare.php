<?php
$userInput = $_POST['user']; // replace with actual user input
$correctAnswer = $_POST['answer']; // replace with actual correct answer
$command = "python compare.py \"$userInput\" \"$correctAnswer\"";
$result = shell_exec($command);
if (trim($result) == 'True') {
    echo 1;
} else {
    echo 0;
}
