/* Создание нового объекта XMLHttpRequest для общения с Web-сервером */
		var xmlHttp = new XMLHttpRequest();
		// Получить значение поля "login" и записать его в переменную login
		function callServer()
		{
			var login = document.getElementById("login").value;
			var pass = document.getElementById("pass").value;
			
			if ((login == null) || (login == "")) return;
			if ((pass == null) || (pass == "")) return;
			
			// Создать URL для подключения
			//var url = "login.php?user=" + escape(login) + "&pass=" + escape(pass);
			var url = "engine/login.php";

			// Открыть соединение с сервером
			//xmlHttp.open("GET", url, true);
			xmlHttp.open("POST", url, true);
			xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			// Установить функцию для сервера, которая выполнится после его ответа
			xmlHttp.onreadystatechange = updatePage;

			// Передать запрос
			//xmlHttp.send(null);
			var sendpost = "user=" + encodeURIComponent(escape(login)) + "&pass=" + encodeURIComponent(escape(pass)) + "&wlsec=Продолжить";
			xmlHttp.send(sendpost);
		}
		
		function updatePage()
		{
			if (xmlHttp.readyState < 4)
				document.getElementById("imgload").innerHTML = '<img src="img/loading.gif">';
			if (xmlHttp.readyState == 4)
			{
				var response = xmlHttp.responseText;
				if (response == "1")
				{
					document.getElementById("imgload").innerHTML = '<span style="color: #00FF00;">Вы успешно вошли!</span>';
					location.reload();
				}
				else if (response == "2")
				{
					document.getElementById("imgload").innerHTML = '<span style="color: #FF0000;">Неверный логин или пароль!</span>';
				}
				else
				{	
					document.getElementById("imgload").innerHTML = 'Случилась неведомая вещь!' + response;
					callServer();
				}
			}
		}