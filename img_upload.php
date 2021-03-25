<?php
 
function resize_photo($path, $filename, $type, $tmp_name){
    $quality = 85; //Качество в процентах. В данном случае будет сохранено 60% от начального качества.
    $size = 1048576; //Максимальный размер файла в байтах. В данном случае приблизительно 1 МБ.
    switch($type){
        case 'jpg': $source = imagecreatefromjpeg($tmp_name); break;
		case 'jpeg': $source = imagecreatefromjpeg($tmp_name); break;		//Создаём изображения по
        case 'png': $source = imagecreatefrompng($tmp_name); break;  //образцу загруженного  
        //case 'image/gif': $source = imagecreatefromgif($tmp_name); break; //исходя из его формата
        default: return false;
    }
    imagejpeg($source, $path.$filename, $quality); //Сохраняем созданное изображение по указанному пути в формате jpg
    imagedestroy($source);//Чистим память
    return true;
}
 
 
// Название <input type="file">
$input_name = 'file';
 
// Разрешенные расширения файлов.
$allow = array('jpg', 'jpeg', 'png', 'mp4');
 
// Запрещенные расширения файлов.
$deny = array(
	'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
	'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
	'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi', 'exe'
);
 
// Директория куда будут загружаться файлы.
$path = __DIR__ . '/uploads/';
 
 
$error = $success = '';
$url = $url_original = $ratio = '';
if (!isset($_FILES[$input_name])) {
	$error = 'Файл не загружен.';
} else {
	$file = $_FILES[$input_name];
 
	// Проверим на ошибки загрузки.
	if (!empty($file['error']) || empty($file['tmp_name'])) {
		$error = 'Не удалось загрузить файл.';
	} elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
		$error = 'Не удалось загрузить файл.';
	} else {
		// Оставляем в имени файла только буквы, цифры и некоторые символы.
		$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
		$name = mb_eregi_replace($pattern, '-', $file['name']);
		$name = mb_ereg_replace('[-]+', '-', $name);
		$parts = pathinfo($name);
 
		if (empty($name) || empty($parts['extension'])) {
			$error = 'Недопустимый тип файла';
		} elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
			$error = 'Недопустимый тип файла';
		} elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
			$error = 'Недопустимый тип файла';
		} else {
			$namebase = md5(time() + rand());
			$name = $namebase . "_orig." . strtolower($parts['extension']);
			// Перемещаем файл в директорию.
			if (move_uploaded_file($file['tmp_name'], $path . $name)) {
				// Далее можно сохранить название файла в БД и т.п.
				//$success = '<p style="color: green">Файл «' . $name . '» успешно загружен.</p>';
				$success = $name . "";
				$url_original = $name;
				if (resize_photo($path, $namebase.".jpg", strtolower($parts['extension']), $path.$name)) {
					$url = $namebase.".jpg";
					$imgsize = getimagesize($path.$name);
					$ratio = $imgsize[1] / $imgsize[0];
					$ratio = ($ratio > 1) ? 1 : $ratio;
				} else {
					$error = 'Ошибка при обработке фото';
				}
			} else {
				$error = 'Не удалось загрузить файл.';
			}
		}
	}
}
 
// Вывод сообщения о результате загрузки.
if (!empty($error)) {
	$error = '<p style="color: red">' . $error . '</p>';  
}
 
$data = array(
	'error'   => $error,
	'success' => $success,
	'url'	  => $url,
	'url_original' => $url_original,
	'ratio' => $ratio
);
 
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
exit();