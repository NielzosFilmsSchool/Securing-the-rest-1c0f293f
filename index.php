<?php

if(!isset($_COOKIE["loggedInUser"])) {
    header("Location: login.php");
}

echo "<h1>Welkom op het netland beheerderspaneel</h1>";

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
    $stmt;
    if(isset($_GET["sort"])) {
        if($_GET["sort"] == "title") {
            $stmt = $pdo->query('SELECT type, title, rating FROM media ORDER BY title ASC');
        } else if($_GET["sort"] == "rating") {
            $stmt = $pdo->query('SELECT type, title, rating FROM media ORDER BY rating DESC');
        }
    } else {
        $stmt = $pdo->query('SELECT type, title, rating FROM media');
    }
    
    if(isset($_GET["sortFilms"])) {
        $table = "<table><tr><th><a href='?sort=title&sortFilms=".$_GET["sortFilms"]."' >Titel</a></th><th><a href='?sort=rating&sortFilms=".$_GET["sortFilms"]
            ."' >Rating</a></th><th>Details</th></tr>";
    } else {
        $table = "<table><tr><th><a href='?sort=title' >Titel</a></th><th><a href='?sort=rating' >Rating</a></th><th>Details</th></tr>";
    }

    while($row = $stmt->fetch()) {
        if($row["type"] == "serie") {
            $table .= "<tr><td>".$row['title']."</td><td>".$row['rating']."</td><td><a href='series.php?title=".$row['title']."'>Meer Details</a></td></tr>";
        }
    }
    $table .= "</table>";
    echo "<h2>Series</h2>";
    echo "<a href='toevoegen.php?type=serie'>Serie toevoegen</a>";
    echo $table;

    $stmt;
    if(isset($_GET["sortFilms"])) {
        if($_GET["sortFilms"] == "title") {
            $stmt = $pdo->query('SELECT type, title, duur FROM media ORDER BY title ASC');
        } else if($_GET["sortFilms"] == "duur") {
            $stmt = $pdo->query('SELECT type, title, duur FROM media ORDER BY duur DESC');
        }
    }else {
        $stmt = $pdo->query('SELECT type, title, duur FROM media');
    }

    if(isset($_GET["sort"])) {
        $table = "<table><tr><th><a href='?sort=".$_GET["sort"]."&sortFilms=title' >Titel</a></th><th><a href='?sort=".$_GET["sort"]."&sortFilms=duur' >Duur</a></th><th>Details</th></tr>";
    } else {
        $table = "<table><tr><th><a href='?sortFilms=title' >Titel</a></th><th><a href='?sortFilms=duur' >Duur</a></th><th>Details</th></tr>";
    }

    while($row = $stmt->fetch()) {
        if($row["type"] == "film") {
            $table .= "<tr><td>".$row['title']."</td><td>".$row['duur']."</td><td><a href='films.php?title=".$row['title']."'>Meer Details</a></td></tr>";
        }
    }
    $table .= "</table>";
    echo "<h2>Films</h2>";
    echo "<a href='toevoegen.php?type=film'>Film toevoegen</a>";
    echo $table;
} catch(\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>