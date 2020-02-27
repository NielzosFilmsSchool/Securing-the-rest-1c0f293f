<h1>Welkom op het netland beheerderspaneel</h1>
<h3>Inloggen</h3>
<form action="login.php" method="post">
    <label>Username</label><br>
    <input type="text" name="username" /><br>
    <label>Password</label><br>
    <input type="password" name="password" /><br>
    <input type="submit" name="submit" />
</form>

<?php

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

if(isset($_POST["submit"])) {
    if(($_POST["username"] != "") && ($_POST["password"] != "")) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            $stmt = $pdo->query('SELECT * FROM gebruikers WHERE username LIKE "'.$_POST["username"].'"');
            if($stmt->rowCount() == 0) {
                echo "Geen gebruiker gevonden";
            } else {
                while ($row = $stmt->fetch())
                {
                    if($row["wachtwoord"] == $_POST["password"]) {
                        setcookie("loggedInUser", $row["id"], time() + 86400, "/"); //cookie for 1 day 86400
                        header("Location: index.php");
                    } else {
                        echo "Wachtwoord is niet goed.";
                    }
                }
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    } else {
        echo "Je hebt geen username en of password ingevoerd!";
    }
}

?>