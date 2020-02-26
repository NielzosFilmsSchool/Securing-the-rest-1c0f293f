<?php

if(!isset($_COOKIE["loggedInUser"])) {
    header("Location: login.php");
}

echo "<a href='index.php'>Terug</a>";
echo "<a style='margin-left:20px;' href='films_edit.php?title=".$_GET['title']."'>Bewerk</a>";

$host = '127.0.0.1';
$db   = 'netland';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query('SELECT * FROM media WHERE title LIKE "'.$_GET["title"].'"');
    while($row = $stmt->fetch()) {
        echo "<h1>".$row["title"]." - ".$row["duur"]." minuten</h1>";
        echo "<b>Datum van uitkomst</b> ".$row["datum_uitkomst"]."<br>";
        echo "<b>Land van uitkomst</b> ".$row["land_uitkomst"]."<br>";
        echo "<p>".$row["description"]."</p>";  
    }
     
} catch(\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>