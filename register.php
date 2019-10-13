<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reddit - register</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
<?php
$con = mysqli_connect("localhost","st2014","progress","st2014");

// Back button
$site = "http://dijkstra.cs.ttu.ee/~krjunu/prax4/";
echo "<button type='button' onclick='location.href=\"$site\"'>Back to index</button>";

$userErr = $emailErr = $passwordErr = $nameErr = "";
$user = $email = $password = $name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["user"])) {
        $userErr = "Enter name";
    } else {
        $user = test_input($_POST["user"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$user)) {
            $userErr = "Only letters and white space allowed";
        }
        $result = mysqli_query($con,"SELECT * FROM 164011_users_v5");
        while($row = mysqli_fetch_array($result)) {
            if ($user == $row['username']) $userErr = "Username taken";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Enter email";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Enter password";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($_POST["name"])) {
        $nameErr = "Enter name";
    } else {
        $name = test_input($_POST["name"]);
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>Register new account</h2>
<p><span class="error">* required field.</span></p>
<form name="register" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Username: <input type="text" name="user" value="<?php echo $user;?>">
    <span class="error">* <?php echo $userErr;?></span>
    <br><br>
    E-mail: <input type="text" name="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailErr;?></span>
    <br><br>
    Password: <input type="password" name="password" value="<?php echo $password;?>">
    <span class="error">* <?php echo $passwordErr;?></span>
    <br><br>
    Full name: <input type="text" name="name" value="<?php echo $name;?>">
    <span class="error">* <?php echo $nameErr;?></span>
    <br><br>
    <input type="submit" name="submit" value="Register">
</form>

<?php
if ($userErr == "" && $emailErr == "" && $passwordErr == "" && $nameErr == "" && $user != "") {
    $sql = "INSERT INTO 164011_users_v5(username, password, email, fullname) 
              VALUES ('$user', '$password', '$email', '$name')";
    if (mysqli_query($con, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
    mysqli_close($con);
}
?>
</body>
</html>