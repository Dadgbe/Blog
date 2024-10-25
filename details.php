<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/details2.css">
    <title>Детали статьи</title>
</head>

<body>

    <header>
        <h1>Статья</h1>
        <a href="index.php">Назад</a>;
    </header>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $link = mysqli_connect('localhost', "root", "", "LyaminBlog");

    if (mysqli_connect_errno()) {
        printf("Не удалось подключиться: %s\n", mysqli_connect_error());
        exit();
    }

    // Получаем идентификатор статьи из параметра id в URL
    $articleId = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$articleId) {
        echo "Неверный идентификатор статьи.";
        exit();
    }

    // Получаем информацию о статье из базы данных
    $query = "SELECT * FROM articles WHERE ID = '$articleId'";
    $result = mysqli_query($link, $query);

    if ($result) {
        // Выводим информацию о статье на странице
        $row = mysqli_fetch_assoc($result);

        $imagePath = 'assets/images/' . $row['Img'];

        echo '<div class="articles">';
        echo '    <div class="article-image">';
        echo '        <img src="' . $imagePath . '">';
        echo '    </div>';
        echo '    <div class="article">';
        echo '        <div class="article-info">';
        echo '            <div class="article-title">';
        echo '                <h1>' . $row['Name'] . '</h1>';
        echo '            </div>';
        echo '            <div class="article-date">';
        echo '                <h1>' . $row['Date'] . '</h1>';
        echo '            </div>';
        echo '        </div>';
        echo '        <hr class="hr-line">';
        echo '        <div class="article-content">';
        echo '            <p>' . $row['Text'] . '</p>';
        echo '        </div>';
        if (isset($_COOKIE["authUser"])) {
            $email = $_COOKIE["authUser"];
            $queryUserId = "SELECT ID FROM users WHERE email = '$email'";
            $resultUserId = mysqli_query($link, $queryUserId);
            $rowUserId = mysqli_fetch_assoc($resultUserId);

            if ($rowUserId && $rowUserId['ID'] == $row['ID_user'] || $email == 'admin@mail.ru') {
                // Если ID совпадают, выводим ссылку "Редактировать"
                echo '<a href="edit_article.php?id=' . $row['ID'] . '">Редактировать</a>';
            }
        }
        echo '    </div>';
        echo '    <div class="articles-comments">';
        
        // Вывод комментариев
        $commentsQuery = "SELECT comments.Text, comments.Date, users.name FROM comments INNER JOIN users ON comments.ID_user = users.ID WHERE ID_art = '$articleId'";

        $commentsResult = mysqli_query($link, $commentsQuery);

        if ($commentsResult) {
            echo '    <h2>Комментарии:</h2>';
            while ($commentRow = mysqli_fetch_assoc($commentsResult)) {
                echo '    <div class="comment">';
                echo '        <p class="comment-date">Автор: ' . $commentRow['name'] . ' | Дата: ' . $commentRow['Date'] . '</p>';

                echo '        <p>' . $commentRow['Text'] . '</p>';
                echo '    </div>';
            }
        } else {
            echo "Ошибка при получении комментариев: " . mysqli_error($link);
        }

        // Форма для добавления комментария
        if (isset($_COOKIE["authUser"])) {
            echo '    <div class="comment-form">';
            echo '        <h2>Добавить комментарий:</h2>';
            echo '        <form method="post" action="">';
            echo '            <textarea name="new-comment" required></textarea>';
            echo '            <input type="submit" name="submit-comment" value="Отправить">';
            echo '        </form>';
            echo '    </div>';
        }

        echo '    </div>';
        echo '</div>';
    } else {
        echo "Ошибка при выполнении запроса: " . mysqli_error($link);
    }

    // Обработка отправки нового комментария
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-comment'])) {
        $newCommentText = $_POST['new-comment'];
        $userId = $rowUserId['ID'];

        // Вставка нового комментария в базу данных
        $insertCommentQuery = "INSERT INTO comments (ID_art, ID_user, Date, Text) VALUES ('$articleId', '$userId', NOW(), '$newCommentText')";

        if (mysqli_query($link, $insertCommentQuery)) {
            echo "Комментарий успешно добавлен!";
            // Перенаправление пользователя на ту же страницу
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        } else {
            echo "Ошибка при добавлении комментария: " . mysqli_error($link);
        }
    }

    mysqli_close($link);
    ?>
    
</body>

</html>
