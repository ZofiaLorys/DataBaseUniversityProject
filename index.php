<?php
    session_start();

    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
    {
        header('Location:zawody.php');
        exit();
    }
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <?php include 'head.php'; ?>
</head>

<body class="text-center signin-page">

    <form class="form-signin" action="zaloguj.php" method="post">
        <h1 class="h2 mb-4 font-weight-normal">Strona sekcji wspinaczkowej klubu KS Korona</h1>

        <?php // komunikaty dot rejestracji lub bledu
            if (isset($_SESSION['blad'])) {
                echo "<div class='alert alert-danger' role='alert'>".$_SESSION['blad']."</div>";
            }

            if (isset($_SESSION['powodzenie_rejestracji']) && $_SESSION['powodzenie_rejestracji'] == true ) {
                echo "<div class='alert alert-success' role='alert'>Rejestracja się powiodła, zaloguj się.</div>";
                unset($_SESSION['powodzenie_rejestracji']);
            }
        ?>
        
        <input type="email" name="email" class="form-control" placeholder="Adres email" required autofocus>
        <input type="password" name="haslo" class="form-control" placeholder="Hasło" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj się</button>
        <p class="mt-2 mb-2 text-muted">
            <a href="rejestracja.php">Zarejestruj się!</a>
        </p>

    </form>

</body>
</html>