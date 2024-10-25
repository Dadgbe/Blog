<?php
$link = mysqli_connect('localhost', "root", "", "LyaminBlog");

if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
} else {
    printf("Удалось подключиться: %s\n", mysqli_get_host_info($link));
}

if (!empty($_POST["category-article"]) && !empty($_POST["title-article"]) && !empty($_POST["text-article"])) {
    $ca = $_POST["category-article"];
    $ta = $_POST["title-article"];
    $tea = $_POST["text-article"];
    
    // Проверяем, был ли выбран файл
    if (!empty($_FILES["image-article"]["name"])) {
        // Директория для сохранения изображений
        $uploadDirectory = "../images/";
        
        // Имя файла
        $fileName = basename($_FILES["image-article"]["name"]);
        
        // Полный путь к файлу
        $uploadPath = $uploadDirectory . $fileName;
        
        // Загрузка изображения в указанную директорию
        if (move_uploaded_file($_FILES["image-article"]["tmp_name"], $uploadPath)) {
            $d = date("Y-m-d");
            $email = $_COOKIE["authUser"];
            
            $queryUserId = "SELECT ID FROM users WHERE email = '$email'";
            $resultUserId = mysqli_query($link, $queryUserId);
            
            if ($resultUserId) {
                // Получаем результат запроса
                $row = mysqli_fetch_assoc($resultUserId);
                
                // Проверяем, найден ли пользователь
                if ($row) {
                    $userId = $row['ID'];
                    
                    // Теперь у вас есть ID_user, который вы можете использовать для вставки в таблицу articles
                    $query = "INSERT INTO articles (ID_section, ID_user, Name, Text, Date, Img) VALUES ('$ca', '$userId', '$ta', '$tea', '$d', '$fileName')";
                    
                    if (mysqli_query($link, $query)) {
                        echo "Создание статьи прошло успешно!";
                        echo '<a href="../../personalac.html">Назад в Личный кабинет</a>';
                    } else {
                        echo "Создание статьи завершилось ошибкой" . mysqli_error($link);
                    }
                } else {
                    echo "Пользователь не найден.";
                }
            } else {
                echo "Ошибка при выполнении запроса: " . mysqli_error($link);
            }
        } else {
            echo "Ошибка при загрузке изображения.";
        }
    } else {
        echo "Файл изображения не был выбран.";
    }
} else {
    echo "Не все обязательные поля заполнены.";
}

mysqli_close($link);
?>
