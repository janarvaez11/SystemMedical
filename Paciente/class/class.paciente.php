<!-- Agrega el enlace al CSS de Bootstrap -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<?php
class pacientes
{
	private $IdPaciente;
	private $IdUsuario;
	private $Nombre;
	private $Cedula;
	private $Edad;
	private $Genero;
	private $Estatura;
	private $Peso;
	private $con;

	function __construct($cn)
	{
		$this->con = $cn;
	}


	//*********************** 3.1 METODO update_() *****marca*********************************************	

	public function update_paciente()
	{
		$this->IdPaciente = $_POST['IdPaciente'];
		$this->IdUsuario = $_POST['IdUsuario'];
		$this->Nombre = $_POST['Nombre'];
		$this->Cedula = $_POST['Cedula'];
		$this->Edad = $_POST['Edad'];
		$this->Genero = $_POST['Genero'];
		$this->Estatura = $_POST['Estatura'];
		$this->Peso = $_POST['Peso'];

		$sql = "UPDATE pacientes SET
									IdUsuario='$this->IdUsuario',
									Nombre='$this->Nombre',
									Cedula='$this->Cedula',
									Edad='$this->Edad',
									Genero='$this->Genero',
									Estatura='$this->Estatura',
									Peso='$this->Peso'
				WHERE IdPaciente=$this->IdPaciente;";
		//echo $sql;
		//exit;
		if ($this->con->query($sql)) {
			echo $this->_message_ok("modificó");
		} else {
			echo $this->_message_error("al modificar");
		}

	}


	//*********************** 3.2 METODO save_vehiculo() **************************************************	

	public function save_paciente()
	{

		//$this->IdUsuario = $_POST['IdUsuario'];
		$this->Nombre = $_POST['Nombre'];
		$this->Cedula = $_POST['Cedula'];
		$this->Edad = $_POST['Edad'];
		$this->Genero = $_POST['Genero'];
		$this->Estatura = $_POST['Estatura'];
		$this->Peso = $_POST['Peso'];
		/*
					  echo "<br> FILES <br>";
					  echo "<pre>";
						  print_r($_FILES);
					  echo "</pre>";
				  */

		//exit;
		/* if (!move_uploaded_file($_FILES['Foto']['tmp_name'], $path)) {
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		} */

		$sql = "INSERT INTO pacientes VALUES(NULL,
											'$this->Nombre',
											'$this->Cedula',
											'$this->Edad',
											'$this->Genero',
											'$this->Estatura',
											'$this->Peso');";

						
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
	private function _get_combo_db_unique($tabla, $valor, $etiqueta, $nombre, $defecto)
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

	

	//************************************* PARTE II ****************************************************	

	public function get_form($IdPaciente = NULL)
	{

		if ($IdPaciente == NULL) {
			$this->IdUsuario = NULL;
			$this->Nombre = NULL;
			$this->Cedula = NULL;
			$this->Edad = NULL;
			$this->Genero = NULL;
			$this->Estatura = NULL;
			$this->Peso = NULL;

			$flag = "enabled";
			$op = "new";

		} else {

			$sql = "SELECT * FROM pacientes WHERE IdPaciente=$IdPaciente;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();

			$num = $res->num_rows;
			if ($num == 0) {
				$mensaje = "tratar de actualizar la marca con IdPaciente= " . $IdPaciente;
				echo $this->_message_error($mensaje);
			} else {

				// ***** TUPLA ENCONTRADA *****
				echo "<br>TUPLA <br>";
				echo "<pre>";
				print_r($row);
				echo "</pre>";

				$this->IdUsuario = $row['IdUsuario'];
				$this->Nombre = $row['Nombre'];
				$this->Cedula = $row['Cedula'];
				$this->Edad = $row['Edad'];
				$this->Genero = $row['Genero'];
				$this->Estatura = $row['Estatura'];
				$this->Peso = $row['Peso'];

				$flag = "disabled";
				$op = "update";
			}
		}


		$html = '
			<div class="container">
				<form name="pacientes" method="POST" action="index.php" enctype="multipart/form-data">
				<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
					<input type="hidden" name="IdPaciente" value="' . $IdPaciente . '">
					<input type="hidden" name="op" value="' . $op . '">
					<br>
  					<table class="table table-bordered table-striped mx-auto">
						<thead class="thead-dark">
							<tr>
							<th colspan="2" class="text-center">DATOS PACIENTES</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Id Usuario:</td>
								<td>' . $this->_get_combo_db_unique_ordered("usuarios", "IdUsuario", "IdUsuario", "IdUsuario", $this->IdUsuario) . ' . ' . $flag .' </td>
								</tr>
							<tr>
								<td>Nombre:</td>
								<td><input type="text" size="6" name="Nombre" value="' . $this->Nombre . '" required class="form-control"> </td>
							</tr>
							<tr>
								<td>Cedula:</td>
								<td><input type="text" size="6" name="Cedula" value="' . $this->Cedula . '"  required class="form-control"> </td>
							</tr>
							<tr>
								<td>Edad:</td>
								<td><input type="text" size="6" name="Edad" value="' . $this->Edad . '" required class="form-control"> </td>
							</tr>							
							<tr>
							<td>Genero:</td>
							<td>' . $this->_get_combo_db_unique("pacientes", "Genero", "Genero", "Genero", $this->Genero) . '</td>
						</tr>
						
							<tr>
								<td>Estatura (cm):</td>
								<td><input type="text" size="15" name="Estatura" value="' . $this->Estatura . '" required></td>
							</tr>
							<tr>
								<td>Peso (kg):</td>
								<td><input type="text" size="15" name="Peso" value="' . $this->Peso . '" required></td>
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



	public function get_list($IdPaciente = NULL )
	{

		$IdUsuario = $_SESSION['usuario'];

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
						<th colspan="10" class="text-center">Lista de Pacientes</th>
					</tr>
					<tr>
						<th colspan="10" class="text-center">
							<a href="index.php?d=' . $d_new_final . '" class="btn btn-success disabled">
								<i class="fas fa-plus"></i> Nuevo
							</a>
						</th>
					</tr>
					<tr>
						<th class="text-center">Nombre</th>
						<th class="text-center">Cedula</th>
						<th class="text-center">Edad</th>
						<th class="text-center">Genero</th>
						<th class="text-center">Estatura (cm)</th>
						<th class="text-center">Peso (kg)</th>
						<th colspan="3" class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody>';
	
    // Modifica la consulta SQL para seleccionar solo el paciente actual
	$sql = "SELECT p.IdPaciente, p.Nombre, p.Cedula, p.Edad, p.Genero, p.Estatura, p.Peso 
	FROM pacientes p
	JOIN usuarios u ON u.IdUsuario = p.IdUsuario
	WHERE p.IdUsuario = '$IdUsuario';";
		$res = $this->con->query($sql);

		if ($res === false) {
			die("Error en la consulta SQL: " . $this->con->error);
		}
	
		while ($row = $res->fetch_assoc()) {
			$d_del = "del/" . $row['IdPaciente'];
			$d_del_final = base64_encode($d_del);
	
			$d_act = "act/" . $row['IdPaciente'];
			$d_act_final = base64_encode($d_act);
	
			$d_det = "det/" . $row['IdPaciente'];
			$d_det_final = base64_encode($d_det);
	

			$html .= '
			<tr>
				<td class="text-center">' . $row['Nombre'] . '</td>
				<td class="text-center">' . $row['Cedula'] . '</td>
				<td class="text-center">' . $row['Edad'] . '</td>
				<td class="text-center">' . $row['Genero'] . '</td>
				<td class="text-center">' . $row['Estatura'] . '</td>
				<td class="text-center">' . $row['Peso'] . '</td>
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

	

	public function get_detail_paciente($IdPaciente)
	{
		$sql = "SELECT IdPaciente, IdUsuario, Nombre, Cedula, Edad, Genero, Estatura, Peso
				FROM pacientes
				WHERE IdPaciente = $IdPaciente;";
	
		$res = $this->con->query($sql);
	
		if ($res === false) {
			$mensaje = "tratar de editar la marca con IdPaciente= " . $IdPaciente;
			echo $this->_message_error($mensaje);
		} else {
			$row = $res->fetch_assoc();
	
			$html = '
				<div class="container">
					<table class="table table-bordered table-striped mx-auto" style="max-width: 800px;">
						<thead class="thead-dark">
							<tr>
								<th colspan="2" class="text-center">DATOS DEL PACIENTE</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Id Paciente: </td>
								<td>' . $row['IdPaciente'] . '</td>
							</tr>
							<tr>
								<td>Id Usuario: </td>
								<td>' . $row['IdUsuario'] . '</td>
							</tr>
							<tr>
								<td>Nombre: </td>
								<td>' . $row['Nombre'] . '</td>
							</tr>
							<tr>
								<td>Cedula: </td>
								<td>' . $row['Cedula'] . '</td>
							</tr>
							<tr>
								<td>Edad: </td>
								<td>' . $row['Edad'] . '</td>
							</tr>
							<tr>
								<td>Genero: </td>
								<td>' . $row['Genero'] . '</td>
							</tr>
							<tr>
								<td>Estatura (cm): </td>
								<td>' . $row['Estatura'] . '</td>
							</tr>
							<tr>
								<td>Peso (kg): </td>
								<td>' . $row['Peso'] . '</td>
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
	



	public function delete_paciente($IdPaciente)
	{
		$sql = "DELETE FROM pacientes WHERE IdPaciente=$IdPaciente;";
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