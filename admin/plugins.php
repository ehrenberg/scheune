<?php
include '../include/db_connect.php';
include '../include/functions.inc.php';
require_once('../class/Template.class.php');
require_once( '../class/Plugin.class.php');
sec_session_start($mysqli);
if (!login_check($mysqli) OR !admin_check($mysqli)) header('Location: ../member.php');

$sere			= array ();
$inhalt			= null;
$strPluginList	= null;
$pid			= null;
$Template		= new tpl("admin.tpl");

if(isset($_POST['save_plugin'])) {
	$pid	= cleanInput($_POST['pid']);
	if(isset($_POST['active']))$active = true;
	else $active = false;
	$prio	= cleanInput($_POST['prio']);
	
	$data = array("Prio"	=> $prio,
					"Active"=> $active);
	$db->where("ID", $pid);
	if(!$db->update(T_PLUGIN, $data)) {
		$inhalt .= '<div class="alert-box error">Fehler beim speichern des Plugins</div>';
	}
}


if(isset($_GET['edit'])) {
	$pid = cleanInput($_GET['edit']);
	$cols = array("ID", "Name", "Active", "Prio");
	$db->where("ID", $pid);
	$plugin = $db->getOne(T_PLUGIN, $cols);
	
	$inhalt .= '<a href="plugins.php" class="btn">Zurück</a>
	<h2>'.$plugin['Name'].'</h2>
				<form method="POST" action="plugins.php">
					<table>
					<tr>
						<td><label for="prio">Priorität:</label></td>
						<td><input type="text" name="prio" id="prio" value="'.$plugin['Prio'].'" size="2"></td>
					</tr>
					<tr>
						<td><label for="active">Aktiv:</label></td>
						<td><input type="checkbox" name="active" id="active" value="1" ';if($plugin['Active'] == 1)$inhalt .= 'checked';$inhalt .= '></td>
					</tr>
					<tr>
						<td colspan="2"><input type="hidden" name="pid" value="'.$plugin['ID'].'"><input type="submit" name="save_plugin" value="Speichern"></td>
					</tr>
					</table>
				</form>';
	
}
//Plugin deaktivieren
else if(isset($_GET['deact'])) {
	$pid = cleanInput($_GET['deact']);
	$db->where("ID", $pid);
	$db->update(T_PLUGIN, array("Active" => 0));
	header("Location:plugins.php");
}
//Plugin aktivieren
else if(isset($_GET['act'])) {
	$pid = cleanInput($_GET['act']);
	$db->where("ID", $pid);
	$db->update(T_PLUGIN, array("Active" => 1));
	header("Location:plugins.php");
}
else {
	foreach($pluginClass::$plugins as $key => $PluginName) {
		$Plugin = new $PluginName;
		$sere = $PluginName::getInfo();
		
		if($sere['ACTIVE'] == 1) {
			$sere['ICO_ACTIVE'] = '<a href="plugins.php?deact='.$sere['ID'].'"><img src="../img/ico/delete.png"></a>';
		} else {
			$sere['ICO_ACTIVE'] = '<a href="plugins.php?act='.$sere['ID'].'"><img src="../img/ico/success.png"></a>';
		}
		
		$strPluginList .= $Template->fill_tpl("plugin_bit", $sere);
	}

	$sere	= array ("PLUGINLIST"	=> $strPluginList);
	$inhalt = $Template->fill_tpl("start_plugins", $sere);
}



$Template = new tpl("admin.tpl");
$sere = array (
		"title"			=> WEBSITE_NAME,
		"inhalt"		=> $inhalt
);
echo $Template->fill_tpl("main", $sere);
?>