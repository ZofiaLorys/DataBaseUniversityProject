<?php 
    session_start();


    if((!isset($SESSION['zalogowany'])) && (!isset($_POST['grupa'])))
    {
        header('Location: index.php');
        exit();
    }
    

    require_once "connect.php";

    $polaczenie = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if($polaczenie->connect_errno!=0){
        echo "Error:".$polaczenie->connect_errno;

    }
    else // zapisywanie do grupy
    {
        $grupa = $_POST['grupa']; 
    
        $sql = "INSERT INTO zawodnicy (id_sekcjowicza, id_grupy) VALUES (".$_SESSION['id_sekcjowicza'].", $grupa);";
        $polaczenie->query($sql);

                $_SESSION['zawodnik'] = true;
                $_SESSION['grupa_zawodnika'] = $grupa;
                
                header('Location: zawody.php');
            
            
    }
        unset($_POST['grupa']);
        $polaczenie->close();


?>