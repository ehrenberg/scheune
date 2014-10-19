<?php
function getWochenTag($timestamp) {
	$Nr = date("w",$timestamp);
	switch($Nr) {
		case 0:
			return 'Sonntag';
		break;
		case 1:
			return 'Montag';
		break;
		case 2:
			return 'Dienstag';
		break;
		case 3:
			return 'Mittwoch';
		break;
		case 4:
			return 'Donnerstag';
		break;
		case 5:
			return 'Freitag';
		break;
		case 6:
			return 'Samstag';
		break;
	}
}

?>