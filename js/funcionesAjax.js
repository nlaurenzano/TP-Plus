function Mostrar(queMostrar)
{
	$("#principal").html('<img style="padding-top:10%;" src="imagenes/preloader.gif">');

	var funcionAjax=$.ajax({
		url:"nexo.php",
		type:"post",
		data:{queHacer:queMostrar}
	});
	funcionAjax.done(function(retorno){
		$("#principal").html(retorno);
		/*
		if (queMostrar=='MostrarGrilla') {
			alert(retorno);
			$("#principal").html(MostrarJSON(JSON.parse(retorno)));
		} else {
			$("#principal").html(retorno);
		}
		*/
	});
	funcionAjax.fail(function(retorno){
		$("#principal").html(retorno.responseText);	
	});
	funcionAjax.always(function(retorno){
		//alert("siempre "+retorno.statusText);

	});
}

function MostrarLogin() {
	var funcionAjax=$.ajax({
		url:"nexo.php",
		type:"post",
		data:{queHacer:"MostrarLogin"}
	});
	funcionAjax.done(function(retorno) {
		$("#formLogin").html(retorno);
	});
	funcionAjax.fail(function(retorno) {
		$("#mensajesLogin").html("Error en login.");
	});
	funcionAjax.always(function(retorno) {
		//alert("siempre "+retorno.statusText);
	});
}

function MostrarJSON(tablas) {

	var retorno;

	retorno = '<div style="padding:10px;">';
	
	// Tabla de estacionados
	retorno += '<div style="float:left;">';
	retorno += "<h3>Estacionados</h3>";
	retorno += "<table><tr><th>Patente</th><th>Entrada</th></tr>";

	for (var i = 0; i <= tablas.estacionados.length - 1; i++) {
		retorno +=  "<tr><td>" + tablas.estacionados[i].patente + "</td>";
		retorno +=  "<td>" + tablas.estacionados[i].entrada + "</td>";
/*
		if (tablas.tipoUsuario=='admin') {
			retorno +=  '<td><div class="btn-group-xs"><button class="btn btn-success" name="Salir" onclick="Sacar(\'' + tablas.estacionados[i].patente + '\')">Salir</button>';
			retorno +=  '<button class="btn btn-danger" style="margin:0 1px 0 1px;" name="Borrar" onclick="Borrar(\'' + tablas.estacionados[i].patente + '\')">Borrar</button>';
			retorno +=  '<button class="btn btn-danger" name="Modificar" onclick="Modificar(\'' + tablas.estacionados[i].patente + '\')">Modificar</button></div></td>';
		}
*/
		retorno +=  '</tr>';
	}

	retorno += '</table></div>';

	// Tabla de tickets
	retorno += '<div style="float:right;">';
	retorno += "<h3>Cobrados</h3>";
	retorno += "<table><tr><th>Patente</th><th>Entrada</th><th>Salida</th><th>Importe</th></tr>";

	for (var i = 0; i <= tablas.tickets.length - 1; i++) {
		retorno +=  "<tr><td>" + tablas.tickets[i].patente + "</td>";
		retorno +=  "<td>" + tablas.tickets[i].entrada + "</td>";
		retorno +=  "<td>" + tablas.tickets[i].salida + "</td>";
		retorno +=  "<td class=\"moneda\">$ " + tablas.tickets[i].importe + "</td></tr>";
	}

	retorno += '</table></div>';

	return retorno;
}