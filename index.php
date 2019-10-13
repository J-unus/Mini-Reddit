<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reddit</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <?php
    // Connect to database
    $con = mysqli_connect("localhost","st2014","progress","st2014");
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
    }

    // User login, register, log out
    if (isset($_SESSION['user'])) {
        $site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/logout.php";
        echo "<button type='button' onclick='location.href=\"$site\"' >Log out</button>";
        $site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/newPost.php";
        echo "<button type='button' id='rButton' onclick='location.href=\"$site\"'>New Post</button>";
    } else {
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Name: <input type="text" name="username" required="required">
        Password: <input type="password" name="password" required="required">
        <input type="submit" value="Log in">

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = mysqli_query($con, "SELECT * FROM 164011_users_v5");
            $usr = test_input($_POST['username']);
            $psw = test_input($_POST['password']);

            while ($row = mysqli_fetch_array($result)) {
                if ($usr == $row['username'] && $psw == $row['password']) {
                    $_SESSION['user'] = $usr;
                    header("Location: http://dijkstra.cs.ttu.ee/~krjunu/prax4/");
                }
            }
        }

        $site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/register.php";
        echo "<button type='button' id='registerButton' onclick='location.href=\"$site\"'>Register</button>";
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function calculatePoints($newsid) {
        global $con;
        $points = mysqli_fetch_array(mysqli_query($con,
            "SELECT SUM(score) AS total FROM 164011_score_v5 WHERE newsid = $newsid"))[0];
        if ($points == null) {
            return 0;
        }
        $sql = "UPDATE 164011_news_v5 SET score='$points' WHERE id=$newsid";
        mysqli_query($con, $sql);
        return $points;
    }

    function calculateRanking() {
        global $con;
        $sql = "SELECT TIMESTAMPDIFF(SECOND, ts, now()) FROM 164011_news_v5";
        $diff = mysqli_query($con, $sql);

        $count = 1;
        while($row = mysqli_fetch_array($diff)) {
            $sql = "SELECT * FROM 164011_news_v5";
            $result = mysqli_fetch_array(mysqli_query($con, $sql));
            $score = $result['score'];

            $ranking = $score / pow((($row[0] / 3600) + 0.1), 1.8);
            $sql = "UPDATE 164011_news_v5 SET ranking='$ranking' WHERE id=$count";

            mysqli_query($con, $sql);
            $count++;
        }
    }

    // Display news
    $result = mysqli_query($con,"SELECT * FROM 164011_news_v5 ORDER BY ranking ASC");
    calculateRanking();
    while($row = mysqli_fetch_array($result)) {
        echo "<div id='news'>";
        echo "<img class='indexImage' src=".$row['image'].">";
        echo $row['content'];
        echo "<br><hr>";
        echo "Submitted by: ".$row['username'];
        echo "<div id='newsBottom'>";
        if (isset($_SESSION['user'])) {
            $site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/addScore.php/?newsid=".$row['id']."&username=".
                $_SESSION['user']."&score=1";
            echo "<a href='$site' class='plusminus'>+</a>";
            $site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/addScore.php/?newsid=".$row['id']."&username=".
                $_SESSION['user']."&score=-1";
            echo "<a href='$site' class='plusminus'>&ndash;</a>";
        }
        echo "Points: ".calculatePoints($row['id']);
        $site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/commentSection.php/?id=" . $row['id'];
        echo "<button type='button' onclick='location.href=\"$site\"'>Comments</button>";
        echo "</div></div>";
    }
    mysqli_close($con);
    ?>

</body>
</html>