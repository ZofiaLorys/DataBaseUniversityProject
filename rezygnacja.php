<?php 
    session_start();


    if((!isset($SESSION['zalogowany'])) && (isset($SESSION['zawodnik'])))
    {
        header('Location: index.php');
        exit();
    }
    

    require_once "connect.php";

    $polaczenie = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if($polaczenie->connect_errno!=0){
        echo "Error:".$polaczenie->connect_errno;

    }
    else // zmiana grupy
    {
    
        $sql = "DELETE FROM zawodnicy WHERE id_sekcjowicza=".$_SESSION['id_sekcjowicza'].";";
        $polaczenie->query($sql);

                unset($_SESSION['zawodnik']);
                unset($_SESSION['grupa_zawodnika']);
                
                header('Location: zawody.php');
            
            
    }
        $polaczenie->close();


?>