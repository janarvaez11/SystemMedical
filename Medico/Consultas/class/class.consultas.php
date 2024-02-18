<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<?php
class consultas
{
	private $IdConsulta;
	private $IdMedico;
	private $IdPaciente;
	private $FechaConsulta;
	private $HI;
	private $HF;
	private $Diagnostico;
	private $con;

	function __construct($cn)
	{
		$this->con = $cn;
	}


	//*********************** 3.1 METODO update_() *****marca*********************************************	

	public function update_consulta()
	{
		$this->IdConsulta = $_POST['IdConsulta'];
		$this->IdMedico = $_POST['IdMedicoCMB'];
		$this->IdPaciente = $_POST['IdPacienteCMB'];
		$this->FechaConsulta = $_POST['FechaConsulta'];
		$this->HI = $_POST['HI'];
		$this->HF = $_POST['HF'];
		$this->Diagnostico = $_POST['Diagnostico'];

		$sql = "UPDATE consultas SET
									IdMedico='$this->IdMedico',
									IdPaciente='$this->IdPaciente',
									FechaConsulta='$this->FechaConsulta',
									HI='$this->HI',
									HF='$this->HF',
									Diagnostico='$this->Diagnostico'

				WHERE IdConsulta=$this->IdConsulta;";
		//echo $sql;
		//exit;
		if ($this->con->query($sql)) {
			echo $this->_message_ok("modificó");
		} else {
			echo $this->_message_error("al modificar");
		}

	}


	//*********************** 3.2 METODO save_vehiculo() **************************************************	

	public function save_consulta()
	{

		$this->IdConsulta = $_POST['IdConsulta'];
		$this->IdMedico = $_POST['IdMedicoCMB'];
		$this->IdPaciente = $_POST['IdPacienteCMB'];
		$this->FechaConsulta = $_POST['FechaConsulta'];
		$this->HI = $_POST['HI'];
		$this->HF = $_POST['HF'];
		$this->Diagnostico = $_POST['Diagnostico'];
		/*
					  echo "<br> FILES <br>";
					  echo "<pre>";
						  print_r($_FILES);
					  echo "</pre>";
				  */


		/* $this->Foto = $this->_get_name_file($_FILES['Foto']['name'],12);

		$path = "PATH" . $this->Foto; */

		//exit;
		/* if (!move_uploaded_file($_FILES['Foto']['tmp_name'], $path)) {
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		} */

		$sql = "INSERT INTO consultas VALUES(NULL,
											'$this->IdMedico',
											'$this->IdPaciente',
											'$this->FechaConsulta',
											'$this->HI',
											'$this->HF',
											'$this->Diagnostico');";

						
		//echo $sql;
		//exit;
		if ($this->con->query($sql)) {
			echo $this->_message_ok("guardó");
		} else {
			echo $this->_message_error("guardar");
		}

	}


	//*********************** 3.3 METODO _get_name_File() **************************************************	

	private function _get_name_file($nombre_original, $tamanio)
	{
		$tmp = explode(".", $nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm - 1]; //Extraer la última posición del arreglo.
		$cadena = "";
		for ($i = 1; $i <= $tamanio; $i++) {
			$c = rand(65, 122);
			if (($c >= 91) && ($c <= 96)) {
				$c = NULL;
				$i--;
			} else {
				$cadena .= chr($c);
			}
		}
		return $cadena . "." . $ext;
	}


	//*************************************** PARTE I ************************************************************


	/*Elimina duplicados en la seleccion del genero*/
	private function _get_combo_db($tabla, $valor, $etiqueta, $nombre, $defecto)
	{
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT DISTINCT $valor, $etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while ($row = $res->fetch_assoc()) {
			$html .= ($defecto == $row[$valor]) ? '<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>';
		}
		$html .= '</select>';
		return $html;
	}

	

	//************************************* PARTE II ****************************************************	

	public function get_form($IdConsulta = NULL)
	{

		if ($IdConsulta == NULL) {
			$this->IdMedico = NULL;
			$this->IdPaciente = NULL;
			$this->FechaConsulta = NULL;
			$this->HI =  NULL;
			$this->HF = NULL;
			$this->DIagnostico = NULL;

			$flag = "enabled";
			$op = "new";

		} else {

			$sql = "SELECT * FROM consultas WHERE IdConsulta=$IdConsulta;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();

			$num = $res->num_rows;
			if ($num == 0) {
				$mensaje = "tratar de actualizar la consulta con id= " . $IdConsulta;
				echo $this->_message_error($mensaje);
			} else {

				// ***** TUPLA ENCONTRADA *****
				/* echo "<br>TUPLA <br>";
				echo "<pre>";
				print_r($row);
				echo "</pre>"; */

				$this->IdMedico = $row['IdMedico'];
				$this->IdPaciente = $row['IdPaciente'];
				$this->FechaConsulta = $row['FechaConsulta'];
				$this->HI = $row['HI'];
				$this->HF = $row['HF'];
				$this->Diagnostico = $row['Diagnostico'];

				$flag = "disabled";
				$op = "update";
			}
		}


		$html = '
			<div class="container">
				<form name="consulta" method="POST" action="index.php" enctype="multipart/form-data">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
					<input type="hidden" name="IdConsulta" value="' . $IdConsulta . '">
					<input type="hidden" name="op" value="' . $op . '">
					<br>
  					<table align="center" class="table table-bordered table-striped">
						<thead class="thead-dark">
							<tr>
							<th colspan="2" class="text-center">DATOS DE LA CONSULTA</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Medico:</td>
								<td>' . $this->_get_combo_db("medicos", "IdMedico", "Nombre", "IdMedicoCMB", $this->IdMedico) . '</td>
								</tr>
							<tr>
								<td>Paciente:</td>
								<td>' . $this->_get_combo_db("pacientes", "IdPaciente", "Nombre", "IdPacienteCMB", $this->IdPaciente) . '</td>
							</tr>
							<tr>
								<td>Fecha de la Consulta:</td>
								<td><input type="date" name="FechaConsulta" value="' . $this->FechaConsulta . '"  required class="form-control"> </td>
							</tr>
							<tr>
								<td>Hora de Inicio:</td>
								<td><input type="time" size="6" name="HI" value="' . $this->HI . '" required class="form-control"> </td>
							</tr>							
							<tr>
								<td>Hora Final:</td>
								<td><input type="time" size="6" name="HF" value="' . $this->HF . '" required class="form-control"> </td>
							</tr>						
							<tr>
								<td>Diagnóstico:</td>
								<td><input type="text" name="Diagnostico" value="' . $this->Diagnostico . '" required></td>
							</tr>
							<tr>
								<th colspan="2"><input type="submit" align="center" name="Guardar" value="GUARDAR" class="btn btn-primary"></th>
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
		$d_new = "new/0";
		$d_new_final = base64_encode($d_new);
	
		$html = '
		<br>
		<div class="container">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
			<table class="table table-bordered table-striped table-hover mx-auto" style="max-width: 800px;">
				<thead class="thead-dark">
					<tr>
						<th colspan="10" class="text-center">Lista de Consultas</th>
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
						<th class="text-center">Medico</th>
						<th class="text-center">Paciente</th>
						<th class="text-center">Fecha de la Consulta</th>
						<th colspan="3" class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody>';
	
		$sql = "SELECT C.IdConsulta, M.Nombre as Medico, P.Nombre as Paciente, C.FechaConsulta 
		FROM consultas c
		JOIN medicos m ON c.IdMedico = m.IdMedico
		JOIN pacientes p ON c.IdPaciente = p.IdPaciente
		ORDER BY C.IdConsulta ASC;";

		$res = $this->con->query($sql);

		while ($row = $res->fetch_assoc()) {
			$d_del = "del/" . $row['IdConsulta'];
			$d_del_final = base64_encode($d_del);
	
			$d_act = "act/" . $row['IdConsulta'];
			$d_act_final = base64_encode($d_act);
	
			$d_det = "det/" . $row['IdConsulta'];
			$d_det_final = base64_encode($d_det);
	

			$html .= '
			<tr>
				<td class="text-center">' . $row['IdConsulta'] . '</td>
				<td class="text-center">' . $row['Medico'] . '</td>
				<td class="text-center">' . $row['Paciente'] . '</td>
				<td class="text-center">' . $row['FechaConsulta'] . '</td>
				<td>
					<a href="index.php?d=' . $d_del_final . '" class="btn btn-danger disabled">
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

	

	public function get_detail_consulta($IdConsulta)
	{
		$sql = "SELECT C.IdConsulta, M.Nombre as Medico, P.Nombre as Paciente, c.FechaConsulta, c.HI, c.HF, c.Diagnostico	
				FROM consultas c
				JOIN medicos m ON c.IdMedico = m.IdMedico
				JOIN pacientes p ON c.IdPaciente = p.IdPaciente
				WHERE IdConsulta = $IdConsulta;";

$res = $this->con->query($sql);
	
		if ($res === false) {
			$mensaje = "tratar de editar la consulta con id= " . $IdConsulta;
			echo $this->_message_error($mensaje);
		} else {
			$row = $res->fetch_assoc();
	
			$html = '
			<br>
				<div class="container">
					<table class="table table-bordered table-striped mx-auto" style="max-width: 800px;">
						<thead class="thead-dark">
							<tr>
								<th colspan="2" class="text-center">DATOS DE LA CONSULTA</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Id de la Consulta: </td>
								<td>' . $row['IdConsulta'] . '</td>
							</tr>
							<tr>
								<td>Médico: </td>
								<td>' . $row['Medico'] . '</td>
							</tr>
							<tr>
								<td>Paciente: </td>
								<td>' . $row['Paciente'] . '</td>
							</tr>
							<tr>
								<td>Fecha de la Consulta: </td>
								<td>' . $row['FechaConsulta'] . '</td>
							</tr>
							<tr>
								<td>Hora de Inicio: </td>
								<td>' . $row['HI'] . '</td>
							</tr>
							<tr>
								<td>Hora Final: </td>
								<td>' . $row['HF'] . '</td>
							</tr>
							<tr>
								<td>Diagnóstico: </td>
								<td>' . $row['Diagnostico'] . '</td>
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
	



	public function delete_consulta($IdConsulta)
	{
		$sql = "DELETE FROM consultas WHERE IdConsulta=$IdConsulta;";
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