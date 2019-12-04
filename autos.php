<?php // Do not put any HTML above this line

//In order to protect the database from being modified without the user properly logging in,
//the autos.php must first check the $_GET variable to see if the user's name is set and
//if the user's name is not present, the autos.php must stop immediately using the PHP die() function:
if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if (isset($_POST['logout'])) {
    header('Location: index.php');
    return;
}
$failure = false; // If we have no POST data
$ok = false;
require_once "pdo.php";
if (isset($_POST['make'])) {
    if (strlen($_POST['make']) < 1) { //make is not input
        $failure = "Make is required";
    } else if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) { //year and/or mileage are not numeric
        $failure = "Mileage and year must be numeric";
    } else {
        $sql = "INSERT INTO autos (make, year, mileage) VALUES (:make, :year, :mileage)";
        echo ("<pre>\n" . $sql . "\n</pre>\n");
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage'],
        ));
        $ok = "Record inserted";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ann Ignatenko's Automobile Tracker</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo ($_GET['name']) ?></h1><!--option: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="7637181817581f11181736111b171f1a5815191b" [email&#160;protected]></a>-->
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>
</br>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ($failure !== false) {
    // Look closely at the use of single and double quotes
    echo ('<p style="color: red;">' . htmlentities($failure) . "</p>\n");
} else {
    echo ('<p style="color: green;">' . htmlentities($ok) . "</p>\n");
}
?>
<h2>Automobiles</h2>
<?php
$stmt = $pdo->query("SELECT make, year, mileage, auto_id FROM autos");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<ul><p>";
    echo (htmlentities($row['year']) . ' ' . htmlentities($row['make']) . ' ' . '/' . htmlentities($row['mileage']));
    echo "</p></ul>";
}
?>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>