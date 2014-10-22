<?php
require_once('../db_connect.php');
include_once('../Functions.inc.php');
require_once('../class/Template.class.php');

$inhalt		= null;
$sere		= array();

if(isset($_POST['edit_template'])) {
	$edit_template = $_POST['template'];
	$Template->save_tpl("start.tpl", $edit_template);
}

/*
 * Bearbeiten Template
 */
if(isset($_GET['edit'])) {
	$ID = $_GET['edit'];
	
	$cols			= array("ID", "FileName", "Name");
	$db->where("ID", $ID);
	$db->orderBy("Name", "DESC");
	$templates	= $db->get(T_TEMPLATES, null, $cols);
	foreach($templates as $template) {
		$Template	= new tpl($template['FileName']);
		$inhalt		= '<form method="POST">
				<textarea name="template" style="width:100%;height:300px;">'.$Template->fill_tpl("start", $sere).'</textarea>
				<input type="submit" name="edit_template" value="Bearbeiten">
			</form>';
	}
} else {
	$cols			= array("ID", "FileName", "Name");
	$db->orderBy("Name", "DESC");
	$templates	= $db->get(T_TEMPLATES, null, $cols);
	foreach($templates as $template) {
			$inhalt		.= '<a href="templates.php?edit='.$template['ID'].'">'.$template['FileName'].' bearbeiten</a><br />';
	}
}

?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="../css/tcal.css">
	<script type="text/javascript" src="../js/simpletcal.js"></script>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Administratorbereich</h1>
		</div>
		<div id="navi">
			<ul>
				<li><a href="../">Zurück zur Webseite</a></li>
				<li><a href="index.php">Startseite</a></li>
				<li><a href="index.php?p=termine">Terminkalendar</a></li>
				<li><a href="abstimmung.php">Abstimmungen</a></li>
				<li><a href="vorschlaege.php">Abstimmungen - Vorschläge</a></li>
				<li><a href="templates.php">Templates</a></li>
			</ul>
		</div>
		<div id="content">
		<?php
			echo $inhalt;
		?>
		</div>
		<div class="clear"></div>
	</div>
</body>
</html>