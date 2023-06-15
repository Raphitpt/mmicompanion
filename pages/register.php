<?php
session_start();
require '../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['edu_number']) && isset($_POST['edu_mail']) && isset($_POST['edu_group'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $edu_number = $_POST['edu_number'];
    $edu_mail = $_POST['edu_mail'];
    $edu_group = $_POST['edu_group'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        echo 'Password and Confirm Password should match!';
        exit;
    }
    
    $user = new User();
    $user->setUsername($username);
    $user->setPassword($password);
    $user->setEduNumber($edu_number);
    $user->setEduMail($edu_mail);
    $user->setEduGroup($edu_group);


    $entityManager->persist($user);
    $entityManager->flush();
    $sql = "INSERT INTO users (username, password, edu_number, edu_mail, edu_group) VALUES (:username, :password, :edu_number, :edu_mail, :edu_group)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $password,
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