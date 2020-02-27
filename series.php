<?php

if(!isset($_COOKIE["loggedInUser"])) {
    header("Location: login.php");
}

echo "<a href='index.php'>Terug</a>";
echo "<a style='margin-left:20px;' href='series_edit.php?title=".$_GET['title']."'>Bewerk</a>";

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
    while ($row = $stmt->fetch())
    {
        echo "<h1>".$row["title"]." - ".$row["rating"]."</h1>";
        if($row["has_won_awards"] == 1) {
            echo "<b>Awards</b> Ja<br>";
        } else {
            echo "<b>Awards</b> Nee<br>";
        }
        echo "<b>Seasons</b> ".$row["seasons"]."<br>";
        echo "<b>Country</b> ".$row["land_uitkomst"]."<br>";
        echo "<b>Language</b> ".$row["taal"]."<br>";
        echo "<p>".$row["description"]."</p>";
        
    }
    
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>