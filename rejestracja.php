<?php
    session_start();
    
    if (isset($_POST['email']))
    {
        require_once "connect.php";
        
        $polaczenie = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        if ($polaczenie->connect_errno != 0){
            echo "Error:".$polaczenie->connect_errno;
        } else {
            $sql = "INSERT INTO sekcjowicze (email, haslo, imie, nazwisko, plec, rok_urodzenia) VALUES ('".$_POST['email']."', '".$_POST['haslo']."', '".$_POST['imie']."', '".$_POST['nazwisko']."', '".$_POST['plec']."', '".$_POST['rok_urodzenia']."');";
            $polaczenie->query($sql);
            $_SESSION['powodzenie_rejestracji'] = true;
            header('Location: index.php');
        }
        
        unset($_SESSION['blad']);

        $polaczenie->close();
        
    }
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <?php include('head.php'); ?>
</head>

<body class="text-center signin-page register-page">

    <form class="form-signin" method="post">
        <h1 class="h2 mb-4 font-weight-normal">Rejestracja</h1>
        <input type="text" name="imie" class="form-control name-input" placeholder="Imię" required>
        <input type="text" name="nazwisko" class="form-control lastname-input" placeholder="Nazwisko" required>
        <input type="email" name="email" class="form-control email-input" placeholder="Adres email" required>
        <input type="password" name="haslo" class="form-control" placeholder="Hasło" required>
        <div class="d-flex align-items-center gender-year">
            <div class="gender text-left">
                <span class="font-weight-bold mr-3">Płeć</span>
                <input type="radio" name="plec" value="mezczyzna"> Mężczyzna
                <input type="radio" name="plec" class="ml-2" value="kobieta"> Kobieta
            </div>
            <div class="birth-year">
                <input type="text" name="rok_urodzenia" class="form-control year-input" placeholder="Rok urodzenia" required>
            </div>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Zarejestruj się</button>
        <p class="mt-2 mb-2 text-muted">
            <a href="index.php">Zaloguj się!</a>
        </p>

    </form>

</body>
</html>
