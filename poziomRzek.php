<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "rzeki";
function table($server, $username, $password, $database)
{
    $conn = mysqli_connect($server, $username, $password, $database);

    $option = $_POST["option"];
    if ($option == "all") {
        $sql = "SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody FROM wodowskazy, pomiary WHERE pomiary.wodowskazy_id = wodowskazy.id AND pomiary.dataPomiaru = '2022-05-05';";
    }
    if ($option == "warning") {

        $sql = "SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody FROM wodowskazy, pomiary WHERE pomiary.wodowskazy_id = wodowskazy.id AND pomiary.dataPomiaru = '2022-05-05' AND pomiary.stanWody > wodowskazy.stanOstrzegawczy;";
    }
    if ($option == "alert") {
        $sql = "SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody FROM wodowskazy, pomiary WHERE pomiary.wodowskazy_id = wodowskazy.id AND pomiary.dataPomiaru = '2022-05-05' AND pomiary.stanWody > wodowskazy.stanAlarmowy;";
    }

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td><?= $row["nazwa"] ?></td>
            <td><?= $row["rzeka"] ?></td>
            <td><?= $row["stanOstrzegawczy"] ?></td>
            <td><?= $row["stanAlarmowy"] ?></td>
            <td><?= $row["stanWody"] ?></td>
        </tr>
<?php
    }
    mysqli_close($conn);
}
function statistics($server, $username, $password, $database)
{
    $conn = mysqli_connect($server, $username, $password, $database);

    $sql = "SELECT dataPomiaru, AVG(stanWody) as srednia FROM pomiary GROUP BY dataPomiaru;";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row["dataPomiaru"] . " " . $row["srednia"];
        ?>
        <br>
        <br>
        <?php
    }

    mysqli_close($conn);
}

?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styl.css">
    <title>Poziom rzek</title>
</head>

<body>
    <header>
        <section id="leftHeader">
            <img src="obraz1.png" alt="Mapa Polski">
        </section>
        <section id="rightHeader">
            <h1>Rzeki w województwie dolnośląskim</h1>
        </section>
    </header>
    <nav id="menu">
        <form action="poziomRzek.php" method="POST">

            <input type="radio" name="option" value="all">
            <label for="option1" class="format">Wszystkie</label>
            <input type="radio" name="option" value="warning">
            <label for="option2" class="format">Ponad stan ostrzegawczy</label>
            <input type="radio" name="option" value="alert">
            <label for="option3" class="format">Ponad stan alarmowy</label>
            <input type="submit" value="Pokaż">
        </form>
    </nav>
    <main>
        <section id="left">
            <h3>Stany na dzień 2022-05-05</h3>
            <table>
                <tr>
                    <th>Wodomierz</th>
                    <th>Rzeka</th>
                    <th>Ostrzegawczy</th>
                    <th>Alarmowy</th>
                    <th>Aklualny</th>
                </tr>
                <?php
                table($server, $username, $password, $database);
                ?>
            </table>
        </section>
        <section id="right">
            <h3>Informacje</h3>
            <ul>
                <li>Brak ostrzeżeń o burzach z gradem</li>
                <li>Smog w mieście Wrocław</li>
                <li>Silny wiatr w Karkonoszach</li>
            </ul>
            <h3>Średnie stany wód</h3>
            <?php
            statistics($server, $username, $password, $database);
            ?>
            <a href="https://komunikaty.pl">Dowiedz się więcej</a>
            <img src="obraz2.jpg" alt="rzeka" id="image2">
        </section>
    </main>
    <footer>Stronę wykonał: 00000000000</footer>
</body>

</html>