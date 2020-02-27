<?php

if(!isset($_COOKIE["loggedInUser"])) {
    header("Location: login.php");
}

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
    $title = $_GET["title"];
    if(isset($_POST["submit"])) {
        $stmt = $pdo->prepare(\
            "UPDATE media
            SET
                title = '".$_POST["title"]."',
                rating = ".$_POST["rating"].",
                has_won_awards = ".$_POST["awards"].",
                seasons = ".$_POST["seasons"].",
                land_uitkomst = '".$_POST["country"]."',
                taal = '".$_POST["lan"]."',
                description = '".addslashes($_POST["desc"])."'
            WHERE
                title = '".$_GET["title"]."';"
        );
        $stmt->execute();
        $title = $_POST["title"];
    }
    echo "<a href='series.php?title=".$title."'>Terug</a>";
    $stmt = $pdo->query('SELECT * FROM media WHERE title LIKE "'.$title.'"');
    $form = "<form id='series_form' action='series_edit.php?title=".$title."' method='post'><table>";
    while ($row = $stmt->fetch())
    {
        echo "<h1>".$row["title"]." - ".$row["rating"]."</h1>";

        $form .= "<tr><td><b>Title</b></td><td><input type='text' name='title' value='".$row["title"]."'></td><tr>";
        $form .= "<tr><td><b>Rating</b></td><td><input type='number' name='rating' step='0.1' min='0' max='5' value='".$row["rating"]."'></td><tr>";
        $form .= "<tr><td><b>Awards</b></td><td><select name='awards'>";
        if($row["has_won_awards"] == 1) {
            $form .= "<option value='1' selected>Ja</option>";
        } else {
            $form .= "<option value='1'>Ja</option>";
        }
        if($row["has_won_awards"] == 0) {
            $form .= "<option value='0'selected>Nee</option>";
        } else {
            $form .= "<option value='0'>Nee</option>";
        }
        $form .= "</select></td><tr>";

        $form .= "<tr><td><b>Seasons</b></td><td><input type='number' name='seasons' value='".$row["seasons"]."'></td><tr>";
        $form .= "<tr><td><b>Country</b></td><td><input type='text' name='country' value='".$row["land_uitkomst"]."'></td><tr>";
        $form .= "<tr><td><b>Language</b></td><td><input type='text' name='lan' value='".$row["taal"]."'></td><tr>";
        $form .= "<tr><td><b>Description</b></td><td><textarea name='desc' form='series_form'>".$row["description"]."</textarea></td><tr>";
        
        $form .= "<tr><td></td><td><input type='submit' name='submit'></input></td><tr>";
        
    }
    $form .= "</table></form>";
    echo $form;
    
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>

<style>
    td {
        width: 100px;
    }
</style>