<?php
$link = mysqli_connect('localhost', "root", "", "LyaminBlog");
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
} else {
    printf("Удалось подключиться: %s\n", mysqli_get_host_info($link));
}

if (!empty($_POST["email"]) && !empty($_POST["pass"])) {
    $w = $_POST["email"];
    $e = md5($_POST["pass"]);
    $query = "SELECT * FROM users WHERE email ='$w'";
    $result = mysqli_query($link, $query);
    if (mysqli_num_rows($result) > 0) {
        $query = "SELECT * FROM users WHERE pass ='$e'";
        $result = mysqli_query($link, $query);
        if (mysqli_num_rows($result) > 0) {
            echo "<br>", "Поздравляю, вы авторизованы";
            
            // Создаем куку с логином пользователя
            setcookie("authUser", $w, time() + (86400 * 30), "/"); // хранится 30 дней

            echo '<a href="../../index.php">На главную</a>';
        } else {
            echo "<br>", "Неправильная почта или пароль";
        }
    } else {
        echo "<br>", "Неправильная почта или пароль";
    }
}

mysqli_close($link);
?>
