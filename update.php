<?php
// Voeg de database-gegevens
require('config.php');

// Maak de $dsn oftewel de data sourcename string
$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=UTF8";

try {
    // Maak een nieuw PDO object zodat je verbinding hebt met de mysql database
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    if ($pdo) {
        // echo "Verbinding is gelukt";
    } else {
        echo "Interne server-error";
    }
} catch (PDOException $e) {
    $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // var_dump($_POST);
        // var_dump($pdo);
        // echo "We zijn binnen";
        $sql = "UPDATE Persoon
                SET    Voornaam = :Firstname,
                       Tussenvoegsel = :Infix,
                       Achternaam = :Lastname
                WHERE  Id = :Id";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':Id', $_POST['Id'], PDO::PARAM_INT);
        $statement->bindValue(':Firstname', $_POST['firstname'], PDO::PARAM_STR);
        $statement->bindValue(':Infix', $_POST['infix'], PDO::PARAM_STR);
        $statement->bindValue(':Lastname', $_POST['lastname'], PDO::PARAM_STR);
        $statement->execute();

        header("Refresh:3; read.php");
        echo "Het updaten is gelukt";
        exit();

    } catch(PDOException $e) {
        header("Refresh:3; read.php");
        echo "Het updaten is niet gelukt";
    }
    
} else {
    // Maak een select-query
    $sql = "SELECT * FROM Persoon 
    WHERE Id = :Id";

    // Voorbereiden van de query
    $statement = $pdo->prepare($sql);

    $statement->bindValue(':Id', $_GET['Id'], PDO::PARAM_INT);

    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_OBJ);

    // var_dump($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <title>PDO CRUD</title>
</head>
<body>
    <h1>PDO CRUD</h1>

    <form action="update.php" method="post">
        <label for="firstname">Voornaam:</label><br>
        <input type="text" name="firstname" id="firstname" value="<?php echo $result->Voornaam; ?>">
        <br><br>

        <label for="infix">Tussenvoegsel:</label><br>
        <input type="text" name="infix" id="infix" value="<?php echo $result->Tussenvoegsel; ?>">
        <br><br>

        <label for="lastname">Achternaam:</label><br>
        <input type="text" name="lastname" id="lastname" value="<?php echo $result->Achternaam; ?>">
        <br><br>

        <input type="hidden" name="Id" value=<?= $result->Id; ?>>

        <input type="submit" value="Verstuur">
    </form>
</body>
</html>