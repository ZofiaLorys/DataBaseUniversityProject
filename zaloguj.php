<?php 
    session_start();

    if((!isset($_POST['email'])) || (!isset($_POST['haslo'])))
    {
        header('Location: index.php');
        exit();
    }

    require_once "connect.php";

    $polaczenie = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if($polaczenie->connect_errno!=0){
        echo "Error:".$polaczenie->connect_errno;

    }
    else
    {
        $email = $_POST['email'];
        $haslo = $_POST['haslo'];
        
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");

        $sql = "SELECT * FROM sekcjowicze WHERE email='$email' AND haslo='$haslo'";
        
        if ($rezultat = @$polaczenie->query($sql))
        {
            $ilu_userow = $rezultat->num_rows;
            if($ilu_userow>0)
            {
                $_SESSION['zalogowany'] = true;
                $wiersz = $rezultat->fetch_assoc();
                $_SESSION['id_sekcjowicza'] = $wiersz['id_sekcjowicza'];
                $_SESSION['imie_sekcjowicza'] = $wiersz['imie'];
                $_SESSION['nazwisko_sekcjowicza'] = $wiersz['nazwisko'];

                unset($_SESSION['blad']);
                $rezultat->free_result();

                $sql = "SELECT * FROM sekcjowicze NATURAL JOIN zawodnicy WHERE email='$email' AND haslo='$haslo'";
                
                if ($rezultat = @$polaczenie->query($sql)){
                    $ilu_zawodnikow = $rezultat->num_rows;
                    
                    if($ilu_zawodnikow>0){
                        $_SESSION['grupa_zawodnika'] = $wiersz['grupa'];
                        $_SESSION['zawodnik'] = true;
                    }
                    $rezultat->free_result();
                }

                header('Location: zawody.php');
            
            } else {
                
                $_SESSION['blad'] = '<span style="color: red">Nieprawidlowy login lub haslo</span>';
                header('Location: index.php');

            }
        }

        $polaczenie->close();
    }

?>