<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200&family=Playpen+Sans:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
$(document).ready(function () {
    $(".like-btn").on("click", function () {
        var articleId = $(this).data("article-id");

        // Проверяем наличие куки с именем "authUser"
        if (getCookie("authUser")) {
            // Отправляем AJAX-запрос на сервер для обработки лайка
            $.ajax({
                type: "POST",
                url: "like.php", // Замените на путь к вашему обработчику лайков
                data: { articleId: articleId },
                success: function (response) {
                    // Обновляем количество лайков на странице
                    $(".like-count").text(response.likes_count);
                    // Заменяем иконку лайка в зависимости от состояния (нажат/не нажат)
                    if (response.is_liked) {
                        $(this).removeClass("fa-thumbs-up").addClass("fa-solid fa-thumbs-up");
                    } else {
                        $(this).removeClass("fa-solid fa-thumbs-up").addClass("fa-thumbs-up");
                    }
                }
            });
        } else {
            // Иначе выводим сообщение о необходимости авторизации
            alert("Необходимо пройти процесс авторизации");
        }
    });

    // Функция для получения значения куки по имени
    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        // Возвращаем true, если куки существует, иначе false
        return matches ? true : false;
    }
});
</script>



    <title>Сайт "МойБлог"</title>

    <script type="text/javascript">
    $(document).ready(function () {
        checkUser();

        $(".personal-akk a").on("click", function (e) {
            // Проверяем наличие куки с именем "authUser"
            if (getCookie("authUser")) {
                // Если куки существует, переходим на personalac.html
                window.location.href = "personalac.html";
            } else {
                // Иначе выводим сообщение о необходимости авторизации
                alert("Необходимо пройти процесс авторизации");
            }
            e.preventDefault();
        });

        // Функция для получения значения куки по имени
        function getCookie(name) {
            var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            // Возвращаем true, если куки существует, иначе false
            return matches ? true : false;
        }

        function checkUser() {
            if (getCookie("authUser")) {
                $(".signin").hide();
                $(".signup").hide();
            } else {
                $(".signin").show();
                $(".signup").show();
            }
        }
    });
</script>


    <header>
        <h1>Сайт "МойБлог"</h1>
        <nav class="horizontal-menu">
            <ul>
                <li><a href="index.php">Главное</a></li>
                <li>
                    <div class="dropdown">
                        <a href="/разделы">Разделы</a>
                        <div class="dropdown-content">
                            <a href="?category=1">Новости</a>
                            <a href="?category=2">Рецепты</a>
                            <a href="?category=3">Свои мысли</a>
                        </div>
                    </div>
                </li>
                <li class="signin"><a href="avtor.html">Авторизация</a></li>
                <li class="signup"><a href="reg.html">Регистрация</a></li>
                <li onclick="" class="personal-akk"><a href="">Личный кабинет</a></li>
            </ul>
        </nav>

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

$itemsPerPage = 5; // Количество статей на странице
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Проверяем, выбрана ли какая-то категория
if (isset($_GET['category'])) {
    // Если выбрана категория, используем ее для фильтрации статей
    $selectedCategory = $_GET['category'];
    $query = "SELECT * FROM articles WHERE ID_section = $selectedCategory ORDER BY Date DESC LIMIT $offset, $itemsPerPage";
} else {
    // Если категория не выбрана, выводим все статьи
    $query = "SELECT * FROM articles ORDER BY Date DESC LIMIT $offset, $itemsPerPage";
}

$result = mysqli_query($link, $query);
$imagePaths = array();

if ($result) {
    echo '<div class="slider-container">';
    echo '  <div class="slider">';
    echo '            <div class="slide"><img src="assets/images/картинкатест2.jpg" alt="Slide 1" class="slide-image"></div>';
    echo '            <div class="slide"><img src="assets/images/картинкатест.jpg" alt="Slide 2" class="slide-image"></div>';
    echo '            <div class="slide"><img src="assets/images/рибай.jpg" alt="Slide 3" class="slide-image"></div>';
    echo '  </div>';
    echo '</div>';
    echo '    <script src="script.js"></script>';
    echo '<div class="articlestree">';
    while ($row = mysqli_fetch_assoc($result)) {
        $imagePath = 'assets/images/' . $row['Img'];
        $shortText = mb_strimwidth($row['Text'], 0, 250, '...');
        $imagePaths[] = $imagePath;

        echo '<div class="articles">';
        echo '    <div class="article-image">';
        echo '        <img src="' . $imagePath . '">';
        echo '    </div>';
        echo '    <div class="article">';
        echo '        <div class="article-info">';
        echo '            <div class="article-title">';
        echo '                <h1>' . mb_strimwidth($row['Name'], 0, 80, '...') . '</h1>';
        echo '            </div>';
        echo '            <div class="article-date">';
        echo '                <h1>' . $row['Date'] . '</h1>';
        echo '            </div>';
        echo '        </div>';
        echo '        <hr class="hr-line">';
        echo '        <div class="article-content">';
        echo '            <p>' . $shortText . '</p>';
        echo '        </div>';
        echo '        <hr class="hr-line">';
        echo '<a class="morearticle" href="details.php?id=' . $row['ID'] . '">Подробнее</a>';

        echo '    </div>';
        echo '    <div class="comments">';
        echo '        <h1 class="comments-title">Комментарии:</h1>';
        echo '        <hr class="hr-line">';
        echo '        <div class="comments-content">';
        
        // Дополнительный запрос для получения последних пяти комментариев
        $commentsQuery = "SELECT * FROM comments WHERE ID_art = {$row['ID']} ORDER BY Date DESC LIMIT 5";

        $commentsResult = mysqli_query($link, $commentsQuery);

        $commentUserName = "SELECT comments.*, users.Name AS UserName 
                 FROM comments 
                 LEFT JOIN users ON comments.ID_user = users.ID
                 WHERE comments.ID_art = {$row['ID']} 
                 ORDER BY comments.Date DESC 
                 LIMIT 5";
        $commentsResult = mysqli_query($link, $commentUserName);
        
        while ($commentRow = mysqli_fetch_assoc($commentsResult)) {
                echo '<p><strong>' . $commentRow['UserName'] . ':</strong> ' . $commentRow['Text'] . '</p>';

        }
        
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo "Ошибка при выполнении запроса: " . mysqli_error($link);
}

// Получаем общее количество статей
if (isset($selectedCategory)) {
    // Если выбрана категория, учитываем ее для подсчета общего числа статей
    $totalQuery = "SELECT COUNT(*) as total FROM articles WHERE ID_section = $selectedCategory";
} else {
    // Если категория не выбрана, подсчитываем общее число всех статей
    $totalQuery = "SELECT COUNT(*) as total FROM articles";
}

$totalResult = mysqli_query($link, $totalQuery);

if ($totalResult) {
    $totalRow = mysqli_fetch_assoc($totalResult);
    $totalArticles = $totalRow['total'];

    $totalPages = ceil($totalArticles / $itemsPerPage);

    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<a href="?page=' . $i . (isset($selectedCategory) ? '&category=' . $selectedCategory : '') . '">' . $i . '</a>';
    }
    echo '</div>';
} else {
    echo "Ошибка при получении общего числа статей: " . mysqli_error($link);
}

mysqli_close($link);
?>



</body>

<footer>
    <p>Лямин Юрий Алексеевич 4ПКС-320</p>
</footer>

</html>