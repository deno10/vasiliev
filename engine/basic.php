<?php
function getbasics() {
	$link = $GLOBALS['mysql_oldstyle_link'];
	$basics = array();
	
	$result = mysqli_query($link, "SELECT * FROM `basicblocks`;") or die (mysqli_error($link));
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$name = $row['name'];
		$content = $row['content'];
		$basics[$name] = $content;
	}
	return $basics;
}
?>