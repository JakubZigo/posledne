# posledne zadanie
## Pridanie textu
Ked chces pridat text na stranku musis

1. dopisat text do sk.php, en.php: 
```php 
<?php $lan = array("nazov stringu" => "hodnota v danom jazyku");?>
```
2 text vypisat cez echo:
```php
<?php echo $lan['nazov stringu']?>
```
3 Stranka musi obsahovat:
```php
<?php
session_start();
require_once "../helpers/checkers.php";
setLang();
require_once "../helpers/" . $_SESSION["lang"];
?>
```
4. Stranka by mala mat moznost prepnut jayzk, mozete pouzit:
```php
...
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
...
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <form method="post">
            <input type="hidden" name="lan" value="1">
            <button type="submit" id="lan"><i class="bi bi-translate m-lg-1" style="font-size: 2rem;"></i></button>
        </form>
        <a href="../sign_in/sign_out.php"><i class="bi bi-door-closed" style="font-size: 2rem;"></i></a>
    </div>
</div>
```
