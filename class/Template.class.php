<?php
header("Content-Type: text/html; charset=utf-8");
class tpl {
	var $ws = '!'; //Zeichen für geschützte Leerzeile
    var $tpl;
    var $lbr; //Linebreak-options for easy table-handling
	

    function tpl($tplurl) {
        $this->tpl = array();
        $line_arr = file($_SERVER['DOCUMENT_ROOT']."scheune/templates/".$tplurl);
        $aktindex = ""; //Index am Anfang leer
        foreach ($line_arr as $line) {
            $templine = trim($line);
            $first = substr($templine, 0, 1);
            //In maskierte Zeilen werden # und [ und ! ignoriert
            //Maskierung wird entfernt.
            if ($first == '\\' and $aktindex) {
            	$this->tpl[$aktindex] .= substr(strstr($line, '\\'), 1);
            } else if  ($first == '[' and substr($templine, -1) == ']') {
                $aktindex = substr($templine, 1, -1);
                $this->tpl[$aktindex] = ""; //Templatebuffer initalisieren
            } else if ($first == '#' or $first == ';' or $first == '') {
                ; //Zeile überspringen
            } else {
                if ($aktindex) {
                    if ($templine == $this->ws) $line = str_replace($this->ws, '', $line);
                    $this->tpl[$aktindex] .= $line;
                }
            }
        }
    }

	/*
	 * Save Template
	 */
	function save_tpl($name,$tag, $new) {
		$on			= false;
		
		$line_arr	= file($_SERVER['DOCUMENT_ROOT']."scheune/templates/".$name);
		$line_count	= count($line_arr);
		$new_arr	= preg_split("/\r\n|\n|\r/", $new); //Text zu Array
		array_unshift($new_arr, "[".$tag."]\n\r");
		$new_count	= count($new_arr);
		
		//Zeilenumbrüche entfernen
		for($a=0;$a<$new_count;$a++) {
			$new_arr[$a] = trim($new_arr[$a]);
		}
		for($b=0;$b<$line_count;$b++) {
			$line_arr[$b] = trim($line_arr[$b]);
		}

		//Zeilen löschen
        for($i = 0;$i < $line_count;$i++) {
            $templine	= $line_arr[$i];
            $first		= substr($templine, 0, 1);
			$last		= substr($templine, -1);
			
			//Wenn anderer Bereich
			if ($first == '[' AND $last == ']') {
				//Wenn [start]
				if ($templine == '['.$tag.']') {
					$on = true;
				} else {
					$on = false;
				}
			}
			//Wenn in [start] lösche Zeile
			if($on == true) {
				unset($line_arr[$i]);
            }
        }
		//Arrays zusammenführen
		$line_arr	= array_merge($new_arr, $line_arr);
		//Datei neu schreiben
		$fp			= fopen($_SERVER['DOCUMENT_ROOT']."scheune/templates/".$name, 'w');
		foreach($line_arr as $line) {
			fputs($fp, $line.PHP_EOL);
		}
		fclose($fp);
		
		//Template neu laden
		$this->tpl($name);
		
		return true;
    }
	
    function fill_tpl($name, $se, $re = "", $cmd = true) {
		//Übergabe von zwei Scalaren. Werden einfach in eine Array-Struktur
		//gebracht, um die nächstfolgende Bedingung zu erfüllen
		if ((is_string($se) and is_string($re)) and ($se)) {
			 $se = array($se => $re);
			 $re = "";
		}

		if ($re === "" and $cmd == true) {
			$search = array();
			foreach (array_keys($se) as $sg) {
				$search[] = '{' . strtoupper($sg) . '}';
			}
			return str_replace($search, array_values($se), $this->tpl[$name]);
		} else if ($re === "" and $cmd == false) {
			return str_replace(array_keys($se), array_values($se), $this->tpl[$name]);
		} else if (is_array($re) and $cmd == true) {
			$search = array();
			foreach ($se as $sg) {
				$search[] = '{' . strtoupper($sg) . '}';
			}
			return str_replace($search, $re, $this->tpl[$name]);
		} else if ($re !== "" and $cmd == true) {
			$se = '{' . strtoupper($se) . '}';
		}
		
		return str_replace($se, $re, $this->tpl[$name]);
    }

    function fill_rowtpl($name, $se, $re = "", $cmd = true) {
        $buffer = "";
        $counter = 0;
        //Übergabe von zwei Scalaren. Werden einfach in eine Array-Struktur
        //gebracht, um die nächstfolgende Bedingung zu erfüllen
        if ((is_string($se) and is_string($re)) and ($se and $re)) {
             $se = array($se => $re);
             $re = "";
        }
        if ($re === "") { // Hash
            foreach($se as $line_arr) {
                $buffer .= $this->fill_tpl($name, $line_arr, $re, $cmd);

                if (isset($this->lbr[$name])) {
                    $counter++;
                    // Hier muss das erste Array gezählt werden, weil das zweite leer ist
                    if ($counter % $this->lbr[$name]['cells_per_row'] == 0 and $counter < count($se)) {
                        $buffer .= $this->tpl[$this->lbr[$name]['lbr_pattern']];
                    }
                }
            }
        } else { // zwei Arrays
            foreach($re as $line_arr) {
                $buffer .= $this->fill_tpl($name, $se, $line_arr, $cmd);
                if (isset($this->lbr[$name])) {
                    $counter++;
                    // Hier muss das zweite Array gezählt werden, weil das erste möglicherweise
                    // kürzer ist. Das Sucharray braucht ja nur einmal übergeben zu werden und gilt dann
                    // für jede Reihe.
                    if ($counter % $this->lbr[$name]['cells_per_row'] == 0 and $counter < count($re)) {
                        $buffer .= $this->tpl[$this->lbr[$name]['lbr_pattern']];
                    }
                }
            }
        }
        // leere Zellen =============
        if (!empty($this->lbr[$name]['empty_cells']) and $counter > 0) {
            while ($counter % $this->lbr[$name]['cells_per_row'] != 0) {
                $counter++;
                $buffer .= $this->tpl[$this->lbr[$name]['empty_cells']];
            }
        }
        return $buffer;
    }

    // Helps to create tables
    function set_lbr_options ($name, $lbp, $cpr, $ecp = "") {
        $this->lbr = array($name => array());
        $this->lbr[$name]['lbr_pattern'] = $lbp;
        $this->lbr[$name]['cells_per_row'] = $cpr;
        $this->lbr[$name]['empty_cells'] = $ecp;
    }
	
	//Template-Funktion
	function tpl_func($func_name) {
        $func_file = DIR_PLUGINS.$func_name.'.php';
		
        if(!is_file($func_file)) {
			return('Nicht Vorhanden: ' . $func_name.'<br />');
		}

        include_once($func_file);
 
        $code = "return $func_name(";
        if(func_num_args() > 1) {
            $args = array_slice(func_get_args(), 1);
            for($x=0; $x<count($args); $x++) {
                if($x>0) $code .= ',';
                $code .= '$args[' . $x . ']';
            }
        }
        $code .= ");";
 
        return eval($code);
    }
	
}

?>