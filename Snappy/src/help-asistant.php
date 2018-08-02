<?php
	function help_fn($desc, $db){
		switch ($desc) {
			case 'query':
				$msg = "query(-url, -user, -pass, -name)";
				$str = "<br>url: Direccón donde se aloja tu DB<br>user: Usuario de acceso a MySQL<br>pass: Contraseña de acceso a MySQL <br>name: Nombre de la base de datos a acceder";
				_HELP($msg, 'Asistente de ayuda', $db, $str);
				break;
			case 'to':
				$msg = "to(-str)<br>str: Cadena de texto 'algo'";
				_HELP($msg, 'Asistente de ayuda', $db, "$var->".$desc."()");
				break;
			case 'add':
				$msg = "add(-add_param)";
				$str = "<br>add_param: Array [campo => valor] ";
				_HELP($msg, 'Asistente de ayuda', $db, $str);
				break;
			case 'get':
				$msg = "get(-when, -condition)";
				$str = "when: array [campo => valor], retornara el valor que se le asigno a campo<br>condition: str('>', '<', <>, '=', '!='), signa una condicion a la consulta";
				_HELP($msg, 'Asistente de ayuda', $db, $str);
				break;
			default:
				$msg = "Has usado el asistente de ayuda, por el momento no podemos ayudarte con esta opcion <br>";
				_HELP($msg, 'Asistente de ayuda', $db, $desc);
				break;
		}			
	}
?>