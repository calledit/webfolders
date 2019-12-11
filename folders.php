<?php
//echo($_SERVER["REQUEST_URI"]);
$path_to_show = false;
$parts = explode('?', $_SERVER["REQUEST_URI"]);
$public_path = rawurldecode($parts[0]);

//Seccuring to make sure we dont who paths that are outside of public folders need to be disbaled if we want to follow symlinks
//$absolute_folder_unsecure = $_SERVER["DOCUMENT_ROOT"].$public_path;
$absolute_folder_unsecure = realpath($_SERVER["DOCUMENT_ROOT"].$public_path);
if($absolute_folder_unsecure){
	//if(substr($absolute_folder_unsecure, 0, strlen($_SERVER["DOCUMENT_ROOT"])) == $_SERVER["DOCUMENT_ROOT"]){
		$path_to_show = $absolute_folder_unsecure;
	//}
}

function human_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

$scan_files = scandir($path_to_show);
$files = array();
foreach($scan_files AS $file){
	//if(substr($file, 0, 1) != '.' || ($file != '.' && $file != '..')){
	if(substr($file, 0, 1) != '.' || $file == '.' || $file == '..'){
		$dat = stat($path_to_show.DIRECTORY_SEPARATOR.$file);
		$dat['name'] = $file;
		$dat['full_pathname'] = $path_to_show.DIRECTORY_SEPARATOR.$file;
		$dat['href'] = $file;
		$dat['human_size'] = human_filesize($dat['size']);
		$dat['human_mdate'] = date("Y-m-d H:i:s", $dat['mtime']);
		//var_dump($dat);
		if($dat['mode'] == 16877){
			$dat['href'] .= '/';
		}
		$files[] = $dat;
	}
}
?>
<link rel="stylesheet" href="/blueprint/screen.css" type="text/css" media="screen, projection">
<body>
<h1><?= htmlspecialchars($public_path) ?></h1>
<table>
	<tr>
		<th>name</th>
		<th>modified</th>
		<th>file size</th>
	</tr>
<?php
foreach($files AS $file){
?>
	<tr>
		<td><a href="<?= $file['href'] ?>"><?= $file['href'] ?></a></td>
		<td><?= $file['human_mdate'] ?></td>
		<td><?= $file['human_size'] ?></td>
	</tr>
<?php
}
?>
</table>

