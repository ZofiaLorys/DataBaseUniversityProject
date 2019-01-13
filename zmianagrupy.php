<?php 
    session_start();


    if((!isset($SESSION['zalogowany'])) && (!isset($_POST['grupa_zmiana'])))
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
        $grupa = $_POST['grupa_zmiana']; 
    
        $sql = "UPDATE zawodnicy SET id_grupy=$grupa WHERE id_sekcjowicza=".$_SESSION['id_sekcjowicza'].";";
        $polaczenie->query($sql);

                $_SESSION['zawodnik'] = true;
                $_SESSION['grupa_zawodnika'] = $grupa;
                
                header('Location: zawody.php');
            
            
    }
        unset($_POST['grupa_zmiana']);
        $polaczenie->close();


?>