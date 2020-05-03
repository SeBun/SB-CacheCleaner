<?php
/* Принудительная очистка кеша по CRON
   Автор: Сергей Бунин, г.Липецк
   Вызов: /usr/bin/wget -O /dev/null -q "https://вашсайт/cleancache.php?pass=cleanercache"
*/

function recursiveRemoveDir($dir) {

	$includes = new FilesystemIterator($dir);

	foreach ($includes as $include) {
		if(is_dir($include) && !is_link($include)) {
			recursiveRemoveDir($include);
		}

		else {
			unlink($include);
		}
	}

	rmdir($dir);
}


if(isset($_GET['pass']) && $_GET['pass'] == 'cleanercache')  {

	if (file_exists('cache')) {
		foreach (glob('cache/*') as $file) {
			if (is_dir($file)) {
				echo $file.'<br />';
				recursiveRemoveDir($file);
			}
			else {
				unlink($file);
			}
		}
	}

	$fp = fopen("cache/index.html", "w");
	fwrite($fp, '<!DOCTYPE html><title></title>');
	fclose($fp);
}

else {
	echo 'Access Denied';
}

?>