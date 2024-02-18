<!-- Agrega el enlace al CSS de Bootstrap -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<?php
class recetas
{
	private $IdReceta;
	private $IdConsulta;
	private $IdMedicamento;
	private $Cantidad;
	private $con;

	function __construct($cn)
	{
		$this->con = $cn;
	}


	//*********************** 3.1 METODO update_() *****marca*********************************************	

	public function update_receta()
	{
		$this->IdReceta = $_POST['IdReceta'];
		$this->IdConsulta = $_POST['IdConsulta'];
		$this->IdMedicamento = $_POST['IdMedicamento'];
		$this->Cantidad = $_POST['Cantidad'];


		$sql = "UPDATE recetas SET 
									IdConsulta='$this->IdConsulta',
									IdMedicamento='$this->IdMedicamento',
									Cantidad='$this->Cantidad'
								
				WHERE IdReceta=$this->IdReceta;";
		//echo $sql;
		//exit;
		if ($this->con->query($sql)) {
			echo $this->_message_ok("modificó");
		} else {
			echo $this->_message_error("al modificar");
		}

	}


	//*********************** 3.2 METODO save_vehiculo() **************************************************	

	public function save_receta()
	{

		$this->IdConsulta = $_POST['IdConsulta'];
		$this->IdMedicamento = $_POST['IdMedicamento'];
		$this->Cantidad = $_POST['Cantidad'];


		/*
					  echo "<br> FILES <br>";
					  echo "<pre>";
						  print_r($_FILES);
					  echo "</pre>";
				  */
/*
		//exit;
		if (!move_uploaded_file($_FILES['foto']['tmp_name'], $path)) {
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}
*/
		$sql = "INSERT INTO recetas VALUES(NULL,
											'$this->IdConsulta',
											'$this->IdMedicamento',
											'$this->Cantidad');";
						

		//echo $sql;
		//exit;
		if ($this->con->query($sql)) {
			echo $this->_message_ok("guardó");
		} else {
			echo $this->_message_error("guardar");
		}

	}

	//*************************************** PARTE I ************************************************************


	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla, $valor, $etiqueta, $nombre, $defecto)
	{
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while ($row = $res->fetch_assoc()) {
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor]) ? '<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}


	/*Ordena los IdUsuarios en orden ASC*/
	private function _get_combo_db_unique_ordered($table, $field, $label, $orderField, $selectedValue = "")
{
    $html = '<select name="' . $field . '" class="form-control" >';

    $sql = "SELECT $field, $label FROM $table ORDER BY $orderField ASC;"; // Se agrega "ORDER BY $orderField ASC" para ordenar de forma ascendente
    $result = $this->con->query($sql);

    while ($row = $result->fetch_assoc()) {
        $selected = ($row[$field] == $selectedValue) ? 'selected' : '';
        $html .= '<option value="' . $row[$field] . '" ' . $selected . '>' . $row[$label] . '</option>';
    }

    $html .= '</select>';

    return $html;
}


	    // Función para obtener el nombre del medicamento por su ID
		private function _get_nombre_medicamento($IdMedicamento)
		{
			$sql = "SELECT Nombre FROM medicamentos WHERE IdMedicamento = $IdMedicamento;";
			$res = $this->con->query($sql);
	
			if ($res && $res->num_rows > 0) {
				$row = $res->fetch_assoc();
				return $row['Nombre'];
			} else {
				return "Medicamento Desconocido";
			}
		}


	//************************************* PARTE II ****************************************************	

	public function get_form($IdReceta = NULL)
	{

		if ($IdReceta == NULL) {
		
			$this->IdConsulta = NULL;
			$this->IdMedicamento = NULL;
			$this->Cantidad = NULL;

			$flag = "enabled";
			$op = "new";

		} else {

			$sql = "SELECT * FROM recetas WHERE IdReceta=$IdReceta;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();

			$num = $res->num_rows;
			if ($num == 0) {
				$mensaje = "tratar de actualizar la agencia con id= " . $IdReceta;
				echo $this->_message_error($mensaje);
			} else {

				// ***** TUPLA ENCONTRADA *****
				/* echo "<br>TUPLA <br>";
				echo "<pre>";
				print_r($row);
				echo "</pre>";
 */

		
				$this->IdConsulta = $row['IdConsulta'];
				$this->IdMedicamento = $row['IdMedicamento'];
				$this->Cantidad = $row['Cantidad'];
	
				$flag = "enabled";
				$op = "update";
			}
		}


		$html = '
			<div class="container">
				<form name="recetas" method="POST" action="index.php" enctype="multipart/form-data">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
					<input type="hidden" name="IdReceta" value="' . $IdReceta . '">
					<input type="hidden" name="op" value="' . $op . '">
					<br>		
  					<table class="table table-bordered table-striped mx-auto" style="max-width: 800px;">
						<thead class="thead-dark">
							<tr>
							<th colspan="2" class="text-center">Recetas</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Consulta:</td>
								<td>' . $this->_get_combo_db_unique_ordered("consultas", "IdConsulta", "IdConsulta", "IdConsulta", $this->IdConsulta) . '</td>							</tr>
							<tr>
								<td>Medicamento:</td>
								<td>' . $this->_get_combo_db("medicamentos", "IdMedicamento", "Nombre", "IdMedicamento", $this->IdMedicamento) . '</td>
								</tr>
							<tr>
								<td>Cantidad:</td>
								<td><input type="text" size="6" name="Cantidad" value="' . $this->Cantidad. '" required class="form-control"> </td>
							</tr>
							<tr>
								<th colspan="2" class="text-center"><input type="submit" class="btn btn-primary" name="Guardar" value="GUARDAR"></th>
							</tr>
							<tr>
								<th colspan="2" class="text-center"><a href="index.php">Regresar</a></th>
							</tr>
						</tbody>												
					</table>
				</form>
			</div>';
		return $html;
	}



	public function get_list()
	{
		$d_new = "new/0";                           // Línea agregada
		$d_new_final = base64_encode($d_new);       // Línea agregada
	
		$html = '
		<br>
		<div class="container">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
			<table class="table table-bordered table-striped table-hover mx-auto" style="max-width: 800px;">
				<thead class="thead-dark">
					<tr>
						<th colspan="8" class="text-center">Lista de Recetas</th>
					</tr>
					<tr>
						<th colspan="10" class="text-center">
							<a href="index.php?d=' . $d_new_final . '" class="btn btn-success">
								<i class="fas fa-plus"></i> Nuevo
							</a>
						</th>
					</tr>
					<tr>						
						<th class="text-center">Consulta</th>
						<th class="text-center">Medicamento</th>
						<th class="text-center">Cantidad</th>
			
						<th colspan="3" class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody>';
	
		$sql = "SELECT IdReceta, IdConsulta, IdMedicamento, Cantidad FROM recetas;";
		$res = $this->con->query($sql);
	
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		
		while ($row = $res->fetch_assoc()) {

			$nombre_medicamento = $this->_get_nombre_medicamento($row['IdMedicamento']);

	
			$d_del = "del/" . $row['IdReceta'];
			$d_del_final = base64_encode($d_del);
	
			$d_act = "act/" . $row['IdReceta'];
			$d_act_final = base64_encode($d_act);
	
			$d_det = "det/" . $row['IdReceta'];
			$d_det_final = base64_encode($d_det);
	
			$html .= '
			<tr>
				<td class="text-center">' . $row['IdConsulta'] . '</td>
				<td class="text-center">' . $nombre_medicamento . '</td>
				<td class="text-center">' . $row['Cantidad'] . '</td>
				<td>
					<a href="index.php?d=' . $d_del_final . '" class="btn btn-danger">
						<i class="fas fa-trash"></i>
					</a>
				</td>
				<td>
					<a href="index.php?d=' . $d_act_final . '" class="btn btn-warning">
						<i class="fas fa-edit"></i>
					</a>
				</td>
				<td>
					<a href="index.php?d=' . $d_det_final . '" class="btn btn-info">
						<i class="fas fa-info"></i>
					</a>
				</td>
			</tr>';
		}
	
		$html .= '</tbody></table></div>';
		return $html;
	}
	


	public function get_detail_receta($IdReceta)
	{
		// Consulta para obtener los datos de la receta
		$sql_receta = "SELECT r.IdReceta, r.IdConsulta, r.IdMedicamento, r.Cantidad, c.IdPaciente, c.IdMedico, c.FechaConsulta, c.Diagnostico,
						m.Nombre AS NombreMedicamento, m.Tipo AS TipoMedicamento,
						p.Nombre AS NombrePaciente,
						med.Nombre AS NombreMedico
						FROM recetas r
						JOIN consultas c ON r.IdConsulta = c.IdConsulta
						JOIN medicamentos m ON r.IdMedicamento = m.IdMedicamento
						JOIN pacientes p ON c.IdPaciente = p.IdPaciente
						JOIN medicos med ON c.IdMedico = med.IdMedico
						WHERE r.IdReceta = $IdReceta;";
	
		$res_receta = $this->con->query($sql_receta);
	
		$num_receta = $res_receta->num_rows;
	
		if ($num_receta == 0) {
			$mensaje = "Intento de editar la receta con ID= " . $IdReceta;
			echo $this->_message_error($mensaje);
		} else {
			$row_receta = $res_receta->fetch_assoc();  // Obtener la primera fila de resultados
	
			// Construir el HTML
			$html = '
			<br>
				<div class="container">
					<table class="table table-bordered table-striped mx-auto" style="max-width: 800px;">
						<thead class="thead-dark">
							<tr>
								<th colspan="2" class="text-center">DATOS DE LA RECETA</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Id Consulta: </td>
								<td>' . $row_receta['IdConsulta'] . '</td>
							</tr>
							<tr>
								<td>Nombre Paciente: </td>
								<td>' . $row_receta['NombrePaciente'] . '</td>
							</tr>
							<tr>
								<td>Nombre Médico: </td>
								<td>' . $row_receta['NombreMedico'] . '</td>
							</tr>
							<tr>
								<td>Nombre Medicamento: </td>
								<td>' . $row_receta['NombreMedicamento'] . '</td>
							</tr>
							<tr>
								<td>Tipo del Medicamento: </td>
								<td>' . $row_receta['TipoMedicamento'] . '</td>
							</tr>
							<tr>
								<td>Fecha de la Consulta: </td>
								<td>' . $row_receta['FechaConsulta'] . '</td>
							</tr>
							<tr>
								<td>Diagnóstico: </td>
								<td>' . $row_receta['Diagnostico'] . '</td>
							</tr>
							<tr>
								<td>Cantidad: </td>
								<td>' . $row_receta['Cantidad'] . '</td>
							</tr>
							<tr>
								<th colspan="2" class="text-center"><a href="index.php">Regresar</a></th>
							</tr>
						</tbody>                                                                                     
					</table>
				</div>';
	
			return $html;
		}
	}
	
	
	
	



	public function delete_receta($IdReceta)
	{
		$sql = "DELETE FROM recetas WHERE IdReceta=$IdReceta;";
		if ($this->con->query($sql)) {
			echo $this->_message_ok("ELIMINÓ");
		} else {
			echo $this->_message_error("eliminar");
		}
	}

	//*************************************************************************	

	private function _message_error($tipo)
	{
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th class="text-center"><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}


	private function _message_ok($tipo)
	{
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th class="text-center"><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}

	//****************************************************************************	

} // FIN SCRPIT
?>