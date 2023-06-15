<?php
session_start();
require '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['edu_number']) && isset($_POST['edu_mail']) && isset($_POST['edu_group'])) {
    if(filter_var($_POST['edu_mail'], FILTER_VALIDATE_EMAIL) === false) {
        echo 'Email is not valid!';
        exit;
    }
    if(filter_var($_POST['edu_number'], FILTER_VALIDATE_INT) === false) {
        echo 'Edu Number is not valid!';
        exit;
    }
    
    $username = strip_tags($_POST['username']);
    $password = strip_tags($_POST['password']);
    $edu_number = strip_tags($_POST['edu_number']);
    $edu_mail = strip_tags($_POST['edu_mail']);
    $edu_group = strip_tags($_POST['edu_group']);
    $confirm_password = strip_tags($_POST['confirm_password']);

    if ($password != $confirm_password) {
        echo 'Password and Confirm Password should match!';
        exit;
    }
    
    $sql_register="INSERT INTO users (username, password, edu_number, edu_mail, edu_group) VALUES (:username, :pass, :edu_number, :edu_mail, :edu_group)";
    $stmt = $dbh->prepare($sql_register);
    $stmt->execute([
        ':username' => $username,
        ':pass' => md5($password),
        ':edu_number' => $edu_number,
        ':edu_mail' => $edu_mail,
        ':edu_group' => $edu_group,
    ]);


    echo 'User created!';
    exit;
}
head('Register');
?>
<body>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="edu_number" placeholder="Edu Number" required>
        <input type="text" name="edu_mail" placeholder="Edu Mail" required>
        <input type="text" name="edu_group" placeholder="Edu Group" required>
        <input type="submit" value="Register">
</body>
