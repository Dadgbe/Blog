<?php
		$link = mysqli_connect('localhost', "root", "", "LyaminBlog");
		if ( mysqli_connect_errno() )
		{
				printf("Не удалось подключиться: %s\n", mysqli_connect_error());
				exit();
		}
			else {
				printf("Удалось подключиться: %s\n", mysqli_get_host_info($link));

			 }

		if(!empty($_POST["user-name"])&&!empty($_POST["email"])&&!empty($_POST["pass"])){
			$user_name = $_POST["user-name"];
			$user_email = $_POST["email"];
			$user_pass = md5($_POST["pass"]);
			$user_role = 0;
			$user_img = 'assets/images/Noneimg.jpg';
			$query = "SELECT*FROM users where email ='$user_email'";
			$result=mysqli_query($link,$query);

			if(mysqli_num_rows($result)>0){
				echo "<br>","Такой пользователь уже существует";
			}
			else{
			$query = "INSERT INTO users (name,email,pass,role, img) VALUE ('$user_name','$user_email','$user_pass', '$user_role', '$user_img')";
				if(mysqli_query($link,$query) )echo "Регистрация прошла успешно!";
				else echo "Процесс регистрации завершился ошибкой" . mysqli_error($link);
			}

		}
		mysqli_close($link);

?>