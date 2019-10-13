<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reddit - submit new post</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>

<?php
// Back button
$site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/";
echo "<button type='button' onclick='location.href=\"$site\"'>Back to index</button><br><br>";
?>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    Max 500 chars<br> <textarea name="content" rows="5" cols="40" maxlength="500" required="required"></textarea><br>
    <input type="submit" value="Submit post">
</form>

<?php
if (isset($_POST['error'])) {
    echo $_POST['error'];
}
?>
</body>
</html>