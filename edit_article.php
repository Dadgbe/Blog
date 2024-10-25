<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = mysqli_connect('localhost', 'root', '', 'LyaminBlog');

if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

$articleId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$articleId) {
    echo "Неверный идентификатор статьи.";
    exit();
}

if (!empty($_POST["category-article"]) && !empty($_POST["title-article"]) && !empty($_POST["text-article"])) {
    $category = $_POST['category-article'];
    $title = $_POST['title-article'];
    $text = $_POST['text-article'];

    if (!empty($_FILES["image-article"]["name"])) {
        $uploadDirectory = "assets/images/";


        $fileName = basename($_FILES["image-article"]["name"]);
        $uploadPath = $uploadDirectory . $fileName;

        if (move_uploaded_file($_FILES["image-article"]["tmp_name"], $uploadPath)) {
            $query = "UPDATE articles SET ID_section='$category', Name='$title', Text='$text', Img='$fileName' WHERE ID='$articleId'";
            
            if (mysqli_query($link, $query)) {
                echo "Обновление статьи прошло успешно!";
                echo '<a href="personalac.html">Назад в Личный кабинет</a>';
            } else {
                echo "Обновление статьи завершилось ошибкой: " . mysqli_error($link);
            }
        } else {
            echo "Ошибка при загрузке изображения.";
        }
    } 
}

$query = "SELECT * FROM articles WHERE ID = '$articleId'";
$result = mysqli_query($link, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);

    $category = $row['ID_section'];
    $title = $row['Name'];
    $text = $row['Text'];
    $image = $row['Img'];

    echo '<!DOCTYPE html>';
    echo '<html lang="ru">';
    echo '<head>';
    echo '    <meta charset="UTF-8">';
    echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '    <link rel="stylesheet" href="assets/css/createarticlestyle.css">';
    echo '    <meta charset="UTF-8">';
    echo '    <title>Редактирование статьи</title>';
    echo '    <header>';
    echo '        <h1 class="headertitle">Редактирование статьи</h1>';
    echo '        <a href="personalac.html">Назад</a>';
    echo '    </header>';
    echo '</head>';
    echo '<body>';
    echo '    <div class="createarticlezone">';
    echo '        <div class="formarticle">';
    echo '            <form method="post" action="" enctype="multipart/form-data">';
    echo '                <br><br>';
    echo '                Раздел статьи:';
    echo '                <select name="category-article" required="">';
    echo '                    <option value="1" ' . ($category == 1 ? 'selected' : '') . '>Новости</option>';
    echo '                    <option value="2" ' . ($category == 2 ? 'selected' : '') . '>Рецепты</option>';
    echo '                    <option value="3" ' . ($category == 3 ? 'selected' : '') . '>Свои мысли</option>';
    echo '                </select>';
    echo '                <br><br>';
    echo '                Название статьи:';
    echo '                <input type="text" name="title-article" required="" value="' . $title . '"/>';
    echo '                <br><br>';
    echo '                Текст статьи:';
    echo '                <br><br>';
    echo '                <textarea name="text-article" required="">' . $text . '</textarea>';
    echo '                <br><br>';
    echo '                Изображение статьи:';
    echo '                <input type="file" name="image-article" accept="image/*"/>';
    echo '                <input type="hidden" name="old-image" value="' . $image . '"/>';
    echo '                <br><br>';
    echo '                <input type="hidden" name="article-id" value="' . $articleId . '"/>';
    echo '                <input type="submit" name="update-article" value="Сохранить"/>';
    echo '                <input type="submit" name="delete-article" value="Удалить"/>';
    echo '            </form>';
    echo '        </div>';
    echo '    </div>';
    echo '</body>';
    echo '</html>';
} else {
    echo "Ошибка при выполнении запроса: " . mysqli_error($link);
}

mysqli_close($link);
?>
