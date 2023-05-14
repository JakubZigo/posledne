<?php
require_once "../db/config.php";
require_once "../helpers/checkers.php";

session_start();

setLang();
require_once "../helpers/" . $_SESSION["lang"];
 $errmsg = "";
if (isset($_POST['login']) && isset($_POST['password'])){

    if (checkEmpty($_POST['login']) === true) {
        $errmsg .= "<p>Zadajte login.</p>";
    } if (checkEmpty($_POST['password']) === true) {
        $errmsg .= "<p>Zadajte login.</p>";
    }

    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id, username, password, role, lan FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":username", $_POST["login"]);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $hashed_password = $row["password"];

            if (password_verify($_POST['password'], $hashed_password)) {
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["id"] = $row['id'];
                    $_SESSION["username"] = $row['username'];
                    $_SESSION["role"] = $row['role'];
                    $_SESSION["lan"] = $row['lan'];

                    header("location: ../index.php");

            } else {
                $errmsg = "Nespravne meno alebo heslo.";
            }
        } else {
            $errmsg = "Nespravne meno alebo heslo alebo nieco ine kludne.";
        }
    } else {
        $errmsg = "Ups. Nieco sa pokazilo!";
    }

    unset($stmt);
    unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>STU SvF Test</title>
    <link rel="shortcut icon" href="../images/favicon_svf.ico" type="image/x-icon ">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/css/bootstrap.min.css">
    <link href="sign_in.css" rel="stylesheet" />
</head>

<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <a href="../index.php"><i class="bi bi-arrow-left-circle m-lg-1" style="font-size: 2rem;"></i></a>
        <form method="post">
            <input type="hidden" name="lan" value="1">
            <button type="submit" id="lan"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
        </form>
    </div>
</div>

<section class="gradient-custom">
    <div class="container py-5 ">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold mb-2 text-uppercase"><?php echo $lan['signingInH1']?></h2>
                        <form method="post">
                            <div class="form-outline form-white mb-4">
                                <input type="text" name="login" value="" id="login" class="form-control form-control-lg" required/>
                                <label class="form-label" for="login"><?php echo $lan['login']?></label>
                            </div>
                            <div class="form-outline form-white mb-4 orangeColor">
                                <input type="password" name="password" value="" id="password" class="form-control form-control-lg" required/>
                                <label class="orangeColor form-label" for="password"><?php echo $lan['password']?></label>
                            </div>
                            <button class="btn-lg px-5 m-1 submitButton" type="submit"><?php echo $lan['signInButton']?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
