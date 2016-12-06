<?php 
require_once('./SERVIDOR/lib/nusoap.php');
require_once("clases/AccesoDatos.php");
require_once("clases/Estacionamiento.php");
require_once("clases/Vehiculo.php");

$queHago = $_POST['queHacer'];

switch ($queHago) {
	case 'MostrarInicio':
		include("partes/inicio.php");
		break;
	case 'MostrarBotones':
		include("partes/botonesNav.php");
		break;
	case 'MostrarAlta':
		include("partes/alta.php");
		break;
	case 'MostrarGrilla':
		include("partes/grilla.php");
		//ImprimirTablas();
		break;
	case 'MostrarAdmin':
		include("partes/admin.php");
		break;
	case 'MostrarLogin':
		include("partes/login.php");
		break;
	case 'GuardarVehiculo':
		Estacionamiento::Guardar($_POST['patente']);
		break;
	case 'SacarVehiculo':
		Estacionamiento::Sacar($_POST['patente']);
		break;
	case 'BorrarVehiculo':
		Vehiculo::Borrar($_POST['patente']);
		break;
	/*
	case 'TraerVehiculo':
		$veh = Vehiculo::TraerPorPatente($_POST['id']);		
		echo json_encode($veh);
		break;
	*/

	default:
		# code...
		break;
}

function TraerEstacionadosWS($host) {
	//$host = 'http://localhost/TP-Laurenzano/SERVIDOR/ws.php';
	$client = new nusoap_client($host . '?wsdl');

	$err = $client->getError();
	if ($err) {
		echo '<h2>ERROR EN LA CONSTRUCCION DEL WS:</h2><pre>' . $err . '</pre>';
		die();
	}

	$vehiculos = $client->call('TraerTodosLosEstacionados');

	if ($client->fault) {
		echo '<h2>ERROR AL INVOCAR METODO:</h2><pre>';
		print_r($vehiculos);
		echo '</pre>';
	} else {
		$err = $client->getError();
		if ($err) {
			echo '<h2>ERROR EN EL CLIENTE:</h2><pre>' . $err . '</pre>';
		} 
		else {
			return $vehiculos;
		}
	}
}

function TraerCobradosWS($host) {
	//$host = 'http://localhost/TP-Laurenzano/SERVIDOR/ws.php';
	$client = new nusoap_client($host . '?wsdl');

	$err = $client->getError();
	if ($err) {
		echo '<h2>ERROR EN LA CONSTRUCCION DEL WS:</h2><pre>' . $err . '</pre>';
		die();
	}

	//INVOCO AL METODO DE MI WS		
	$vehiculos = $client->call('TraerTodosLosCobrados');

	if ($client->fault) {
		echo '<h2>ERROR AL INVOCAR METODO:</h2><pre>';
		print_r($vehiculos);
		echo '</pre>';
	} else {
		$err = $client->getError();
		if ($err) {
			echo '<h2>ERROR EN EL CLIENTE:</h2><pre>' . $err . '</pre>';
		} 
		else {
			return $vehiculos;
		}
	}
}

function ImprimirTablas() {
	$host = 'http://localhost:8080/TP-Laurenzano/SERVIDOR/ws.php';
	//$host = 'http://www.tplaurenzano.esy.es/SERVIDOR/ws.php';
	$estacionados = TraerEstacionadosWS($host);
	$cobrados = TraerCobradosWS($host);
/*
	$tipoUsuario = '{"tipoUsuario:"';
	if($_SESSION['rol']=='admin') {
		$tipoUsuario .= '"admin"}';
	} else {
		$tipoUsuario .= '"user"}';
	}
*/
	// Tabla de estacionados
	$retorno = '{"estacionados":[';

	foreach ($estacionados as $veh) {
		if ($retorno != '{"estacionados":[') {$retorno .= ",";}
		$retorno .= '{"patente":"'.$veh['patente'].'","entrada":"'.$veh['entrada'].'"}';
	}

	// Tabla de tickets
	$retorno2 = '],"tickets":[';

	foreach ($cobrados as $ticket) {
		if ($retorno2 != '],"tickets":[') {$retorno2 .= ",";}
		$retorno2 .= '{"patente":"'.$ticket['patente'].'",';
		$retorno2 .= '"entrada":"'.$ticket['entrada'].'",';
		$retorno2 .= '"salida":"'.$ticket['salida'].'",';
		$retorno2 .= '"importe":"'.$ticket['importe'].'"}';
	}
	
	$retorno2 .= ']}';
	
	//echo $tipoUsuario . $retorno . $retorno2;
	echo $retorno . $retorno2;
}

?>