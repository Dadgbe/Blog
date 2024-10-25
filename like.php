<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = mysqli_connect('localhost', "root", "", "LyaminBlog");

if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

// Получаем идентификатор статьи из запроса
$articleId = isset($_POST['articleId']) ? (int)$_POST['articleId'] : 0;

// Проверяем, авторизован ли пользователь (можете настроить это в соответствии с вашей системой авторизации)
if (isset($_COOKIE['authUser'])) {
    $userId = $_COOKIE['authUser'];

    // Проверяем, поставил ли пользователь лайк на эту статью
    $checkLikeQuery = "SELECT COUNT(*) as userLiked FROM articles_likes WHERE id_user = $userId AND id_art = $articleId";
    $checkLikeResult = mysqli_query($link, $checkLikeQuery);
    $checkLikeRow = mysqli_fetch_assoc($checkLikeResult);
    $userLiked = ($checkLikeRow['userLiked'] > 0);

    if ($userLiked) {
        // Пользователь уже поставил лайк, удаляем его
        $deleteLikeQuery = "DELETE FROM articles_likes WHERE id_user = $userId AND id_art = $articleId";
        mysqli_query($link, $deleteLikeQuery);

        $response = [
            'is_liked' => false,
            'likes_count' => getLikesCount($link, $articleId),
        ];
    } else {
        // Пользователь еще не ставил лайк, добавляем его
        $addLikeQuery = "INSERT INTO articles_likes (id_user, id_art) VALUES ($userId, $articleId)";
        mysqli_query($link, $addLikeQuery);

        $response = [
            'is_liked' => true,
            'likes_count' => getLikesCount($link, $articleId),
        ];
    }
} else {
    // Пользователь не авторизован
    $response = [
        'error' => 'Необходимо пройти процесс авторизации',
    ];
}

// Отправляем ответ в формате JSON
header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($link);

// Функция для получения количества лайков для статьи
function getLikesCount($link, $articleId)
{
    $likesQuery = "SELECT COUNT(*) as likesCount FROM articles_likes WHERE id_art = $articleId";
    $likesResult = mysqli_query($link, $likesQuery);
    $likesRow = mysqli_fetch_assoc($likesResult);
    return $likesRow['likesCount'];
}

?>
