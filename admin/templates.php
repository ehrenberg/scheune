<?php
require_once('../db_connect.php');
include_once('../Functions.inc.php');
require_once('../class/Template.class.php');

$inhalt		= null;
$sere		= array();

if(isset($_GET['edit_ok'])) {
	$inhalt .= '<div class="alert-box success">Das Template wurde erfolgreich geändert</div>';
}
/*
 * Bearbeiten Template
 */
else if(isset($_GET['edit'])) {
	$ID = $_GET['edit'];
	
	$cols			= array("ID", "FileName", "Name");
	$db->where("ID", $ID);
	$db->orderBy("Name", "DESC");
	$templates	= $db->get(T_TEMPLATES, null, $cols);

	$Template	= new tpl($templates[0]['FileName']);
	$inhalt		= '<a href="templates.php" class="btn">Zurück zur Template-Übersicht</a>
	<form method="POST">
			<textarea name="template" style="width:100%;height:300px;" class="richtext">'.$Template->fill_tpl("start", $sere).'</textarea>
			<input type="hidden" name="template_name" value="'.$templates[0]["FileName"].'">
			<input type="submit" name="edit_template" value="Bearbeiten">
		</form>';
} else {
	$cols			= array("ID", "FileName", "Name");
	$db->orderBy("Name", "DESC");
	$templates	= $db->get(T_TEMPLATES, null, $cols);
	foreach($templates as $template) {
			$inhalt		.= '<a href="templates.php?edit='.$template['ID'].'" class="btn" style="margin-bottom:10px;">'.$template['FileName'].' bearbeiten</a><br />';
	}
}

/*
 * Template mit neuem Text abspeichern
 */
if(isset($_POST['edit_template'])) {
	$edit_template	= $_POST['template'];		//Text
	$template_name	= $_POST['template_name'];	//Dateiname
	$Template->save_tpl($template_name, 'start', $edit_template);
	header("Location:templates.php?edit_ok");
}

?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="../js/protoplasm/protoplasm_full.js"></script>
	<script language="javascript">
		Protoplasm.use('rte').transform('textarea.richtext');
	</script>
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