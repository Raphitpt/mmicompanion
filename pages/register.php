<?php
session_start();
require '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['edu_number']) && isset($_POST['edu_mail']) && isset($_POST['edu_group1']) && isset($_POST['edu_group2'])) {
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
    $edu_group = strip_tags($_POST['edu_group1']) ."-" . strip_tags($_POST['edu_group2']);
    $confirm_password = strip_tags($_POST['confirm_password']);

    if ($password != $confirm_password) {
        echo 'Password and Confirm Password should match!';
        exit;
    }
    // check if email or edu_num or username already exist in database
    $sql_check = "SELECT * FROM users WHERE username = :username OR edu_number = :edu_number OR edu_mail = :edu_mail";
    $stmt = $dbh->prepare($sql_check);
    $stmt->execute([
        ':username' => $username,
        ':edu_number' => $edu_number,
        ':edu_mail' => $edu_mail,
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo 'User already exists!';
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
echo head('Register');
?>
<style>
    body {
        background-color: #f1f1f1;
    }
    form {
        background-color: #ffffff;
        width: 300px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #f1f1f1;
    }
    input[type=text], input[type=password] {
        width: 100%;
        padding: 10px;
        margin: 5px 0 20px 0;
        border: 1px solid #f1f1f1;
    }
    input[type=submit] {
        background-color: #4CAF50;
        color: #ffffff;
        padding: 10px;
        margin: 5px 0 20px 0;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    input[type=submit]:hover {
        opacity: 0.8;
    }
    span {
        color: red;
    }
    label {
        color: #999;
        text-shadow: 0 1px 0 #fff;
        font-size: 14px;
        font-weight: bold;
    }
    select {
        width: 100%;
        padding: 10px;
        margin: 5px 0 20px 0;
        border: 1px solid #f1f1f1;
    }
</style>
<body>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="edu_number" placeholder="Edu Number" required>
        <input type="text" name="edu_mail" placeholder="Edu Mail" pattern=".+@etu\.univ-poitiers\.fr" required>
        <label for="edu_group">Choisissez votre classe:</label>
        <select name="edu_group1">
            <option value="BUT1">BUT1</option>
            <option value="BUT2">BUT2</option>
            <option value="BUT3">BUT3</option>
        </select>
        <select name="edu_group2">
            <option value="TP1">TP1</option>
            <option value="TP2">TP2</option>
            <option value="TP3">TP3</option>
            <option value="TP4">TP4</option>

        </select>
        <input type="submit" value="Register">
</body>
