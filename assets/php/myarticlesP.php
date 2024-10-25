<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$link = mysqli_connect('localhost', "root", "", "LyaminBlog");
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

// Получаем электронную почту пользователя из куки
$email = $_COOKIE["authUser"];

// Получаем ID пользователя на основе электронной почты
$queryUserId = "SELECT ID FROM users WHERE email = '$email'";
$resultUserId = mysqli_query($link, $queryUserId);

if ($resultUserId) {
    // Получаем результат запроса
    $row = mysqli_fetch_assoc($resultUserId);

    // Проверяем, найден ли пользователь
    if ($row) {
        $userId = $row['ID'];

        // Получаем все статьи пользователя из таблицы articles
        $query = "SELECT * FROM articles WHERE ID_user = '$userId'";
        $result = mysqli_query($link, $query);

        if ($result) {
            // Выводим статьи в HTML
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<hr>';
                $base64Image = base64_encode($row['Img']);
                echo '<img src="$base64Image">';
                echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Изображение статьи">';
                echo '<h2>' . $row['Name'] . '</h2>';
                echo '<a href="details.php?id=' . $row['ID'] . '">Подробнее</a>';
                echo '<hr>';
            }
        } else {
            echo "Ошибка при выполнении запроса: " . mysqli_error($link);
        }
    } else {
        echo "Пользователь не найден.";
    }
} else {
    echo "Ошибка при выполнении запроса: " . mysqli_error($link);
}

mysqli_close($link);
?>
