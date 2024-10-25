<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/myarticlestyle.css">
    <meta charset="UTF-8">
    <title>Мои статьи</title>

    <header>
        <h1 class="headertitle">Мои статьи</h1>
        <a href="index.php">На главную</a>
        <a href="personalac.html">Назад</a>
    </header>

</head>
<body>

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
                    echo '<div class="user-articles">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $imagePath = 'assets/images/' . $row['Img'];
                        echo '<h2 class="article_title">' . $row['Name'] . '</h2>';
                        echo '<div class="article_image">';
                        echo '<img width="250" src="' . $imagePath . '" alt="' . $row['Name'] . '">';
                        echo '</div>';

                        
                        echo '<a href="details.php?id=' . $row['ID'] . '">Подробнее</a>';
                        echo '<hr>';
                    }
                    echo '</div>';
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

</body>
</html>
