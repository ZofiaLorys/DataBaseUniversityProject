<?php
    session_start();

    if (!isset($_SESSION['zalogowany'])){
        header('Location: index.php');
        exit();
    }

    require_once "connect.php";

    $polaczenie = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if($polaczenie->connect_errno!=0){
        echo "Error:".$polaczenie->connect_errno;
        die();
    }
    
    $imie = 'imie';
    $nazwisko = 'nazwisko';
    $grupa = 'id_grupy';
    $godzina = 'godzina';
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mb-5">

        <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
            <p class="lead mb-0">19 lutego w koroniarskiej piwnicy organizujemy towarzyskie zawody boulderowe.</p>
            <p class="lead">Zapraszamy wszystkich sekcjowiczów!</p>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php
                if ((!isset($_SESSION['zawodnik'])) && (!isset($_SESSION['grupa_zawodnika'])))
                {   /// Pierwsza mozliwość, zawodnik nie jest jeszcze zapisany na zawody
                ?> 

                    <form class="mb-5" action="zapisy.php" method="post">
                        <div class="row">
                            <div class="col-sm-8 col-12">
                                <select class="d-block w-100" style="height: 100%;" name="grupa">
                                    <?php // pobieranie rekordow z bazy + wyswietlanie grup
                                    $sql = "SELECT * FROM `grupy_zawody`";
                                    $rezultat = $polaczenie->query($sql);
                                    while($row = $rezultat->fetch_array()) {
                                        echo "<option value=".$row['id_grupy'].">Grupa ".$row['id_grupy'].", ".$row['godzina']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-12 mt-2 mt-sm-0">
                                <button class="btn btn-lg btn-primary btn-block" type="submit">Zapisz się</button>
                            </div>
                        </div>
                    </form>

                <?php
                } else { // Druga mozliwość, zawodnik jest juz zapisany na zawody i moze sie wypisać lub zmienić grupe
                ?> 
                    <div class="row mb-5">
                        <div class="col-sm-8 col-12">
                            <form action="zmianagrupy.php" method="post">
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <select class="d-block w-100" style="height: 100%;" name="grupa_zmiana">
                                            <?php // pobieranie rekordow z bazy + wyswietlanie grup
                                            $sql = "SELECT * FROM `grupy_zawody`";
                                            $rezultat = $polaczenie->query($sql);
                                            while($row = $rezultat->fetch_array()) {
                                                echo "<option value=".$row['id_grupy'].">Grupa ".$row['id_grupy'].", ".$row['godzina']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-12 mt-2 mt-sm-0">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Zmień swoja grupę</button> 
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-4 col-12 mt-2 mt-sm-0">
                            <form action="rezygnacja.php" method="post">
                                <button class="btn btn-lg btn-danger btn-block" type="submit">Zrezygnuj z udziału</button>
                            </form>
                        </div>
                    </div>

                <?php
                }
                ?>

                <div class="row mb-5">
                    <div class="col">

                        <h3 class="mb-3">Wyszukiwarka zawodnikow</h3>
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-sm-4 col-6">
                                    <p class="mb-2">Imię lub nazwisko</p>
                                </div>
                                <div class="col-sm-4 col-6">
                                    <p class="mb-2">Grupa</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-6">
                                    <input class="form-control" type="text" name="imie_nazwisko" style="height: 100%;" />
                                </div>
                                <div class="col-sm-4 col-6">
                                    <select class="d-block w-100" style="height: 100%;" name="wyszukiwarka_grupa">
                                        <option value="0">Wszystkie grupy</option>
                                        <?php // pobieranie rekordow z bazy + wyswietlanie grup
                                            $sql = "SELECT * FROM `grupy_zawody`";
                                            $rezultat = $polaczenie->query($sql);
                                            while($row = $rezultat->fetch_array()) {
                                                echo "<option value=".$row['id_grupy'].">".$row['godzina']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-4 col-12 mt-3 mt-sm-0">
                                    <button class="btn btn-lg btn-primary btn-block" type="submit">Wyszukaj</button>
                                </div>
                            </div>
                        </form>

                    </div><!-- col END -->
                </div><!-- row END -->

                <div class="row">
                    <div class="col">

                        <table class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th scope="col">Imię</th>
                                    <th scope="col">Nazwisko</th>
                                    <th scope="col">Grupa</th>
                                    <th scope="col">Czas</th>
                                </tr>
                            </thead>

                            <?php // pobieranie rekordow z bazy + wsywietlanie tabeli zawodnikow

                            if (isset($_POST['wyszukiwarka_grupa']) && $_POST['wyszukiwarka_grupa']==0) {
                                $sql = "SELECT * FROM `zawodnicy` NATURAL JOIN grupy_zawody NATURAL JOIN sekcjowicze";
                                if (isset($_POST['imie_nazwisko']) && strlen($_POST['imie_nazwisko']) > 0) {
                                    $sql .= " WHERE imie='".$_POST['imie_nazwisko']."' OR nazwisko='".$_POST['imie_nazwisko']."';";
                                }
                            } else if (isset($_POST['imie_nazwisko']) && isset($_POST['wyszukiwarka_grupa'])) {
                                $sql = "SELECT * FROM `zawodnicy` NATURAL JOIN grupy_zawody NATURAL JOIN sekcjowicze WHERE id_grupy=".$_POST['wyszukiwarka_grupa']."";
                                if (isset($_POST['imie_nazwisko']) && strlen($_POST['imie_nazwisko']) > 0) {
                                    $sql .= " AND imie='".$_POST['imie_nazwisko']."' OR nazwisko='".$_POST['imie_nazwisko']."';";
                                }
                            } else $sql = "SELECT * FROM `zawodnicy` NATURAL JOIN grupy_zawody NATURAL JOIN sekcjowicze;";


                            $rezultat = $polaczenie->query($sql);
                            $ilu_zawodnikow = $rezultat->num_rows;
                            if ($ilu_zawodnikow > 0) {
                                while($row = $rezultat->fetch_array()) {
                                    echo "<tr>";
                                    echo "<td>$row[$imie]</td><td>$row[$nazwisko]</td><td>$row[$grupa]</td><td>$row[$godzina]</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "Nie znaleziono takiego zawodnika";
                            }
                            $rezultat->free_result();
                            $polaczenie->close();
                            ?>
                        </table>

                    </div><!-- col END -->
                </div><!-- row END -->

            </div><!-- col END -->
        </div><!-- row END -->
    </div><!-- container END -->

</body>
</html>