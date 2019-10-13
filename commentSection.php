<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reddit - comments</title>
    <link rel="stylesheet" type="text/css" href="../index.css">
</head>
<body>
<?php
// Back button
$site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/";
echo "<button type='button' onclick='location.href=\"$site\"'>Back to index</button><br><br>";

$news_id = $_REQUEST['id'];
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$con = mysqli_connect("localhost", "st2014", "progress", "st2014");
if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}

?>
    <div id="news">
        <?php
        $result = mysqli_query($con,"SELECT * FROM 164011_news_v5 WHERE id = $news_id");
        $row = mysqli_fetch_array($result);
        echo "<img class='commentsImage' src=../".$row['image']."><br>";
        echo $row['content']."<br><hr>";
        echo "Submitted by: ".$row['username'];
        ?>
    </div>

    <form method="post" action="<?php echo htmlspecialchars(
            "http://dijkstra.cs.ttu.ee/~krjunu/prax4/commentSection.php/?id=".$news_id);?>">
        <br> <textarea name="comment" rows="5" cols="40" required="required"></textarea><br>
    <input type="submit" value="Comment">

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = $_POST['comment'];
        $content = trim($content);
        $content = stripslashes($content);
        $content = htmlspecialchars($content);

        if (isset($_SESSION['user'])) {
            $sql = "INSERT INTO 164011_comments_v5(username, newsid, content) VALUES ('$user', '$news_id', '$content')";
            if (mysqli_query($con, $sql)) {
                echo "New record created successfully";
                header("Location: http://dijkstra.cs.ttu.ee/~krjunu/prax4/commentSection.php/?id=".$news_id);
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($con);
            }
        } else {
            echo "Not logged in";
        }
    }

    ?>

    <div id="news">
        Comments:
            <?php
            $result = mysqli_query($con,
                "SELECT * FROM 164011_comments_v5 WHERE newsid=$news_id ORDER BY ts DESC");
            if (mysqli_num_rows($result) == 0) {
                echo "Nothing here";
            }

            while($row = mysqli_fetch_array($result)) {
                echo "<div id='comment'>";
                echo "Submitted by: ".$row['username']."<br><hr>";
                echo $row['content'];
                echo "</div>";
            }
            mysqli_close($con);
            ?>
    </div>
</body>
</html>