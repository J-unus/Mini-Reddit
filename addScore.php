<?php
// Connect to database
$con = mysqli_connect("localhost", "st2014", "progress", "st2014");
if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}

$newsid = $_REQUEST['newsid'];
$username = $_REQUEST['username'];
$score = $_REQUEST['score'];

$result = mysqli_query($con,"SELECT * FROM 164011_score_v5");
while($row = mysqli_fetch_array($result)) {
    if ($newsid == $row['newsid'] && $username == $row['username']) {
        $sql = "DELETE FROM 164011_score_v5 WHERE username='$username' AND newsid='$newsid'";
        mysqli_query($con, $sql);
    }
}

$sql = "INSERT INTO 164011_score_v5(username, newsid, score) 
          VALUES ('$username', '$newsid', '$score')";
if (mysqli_query($con, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($con);
}

mysqli_close($con);
header("Location: http://dijkstra.cs.ttu.ee/~krjunu/prax4/");