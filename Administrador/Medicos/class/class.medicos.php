<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<?php
class rolpersona {
    private $IdUsuario;
    private $IdMedico;
    private $NombreMedico;
    private $NombreUsuario;
    private $NombreEspecialidad;
    private $Especialidad;
    private $Foto;
    private $con;

    function __construct($cn) {
        $this->con = $cn;
    }

    public function update_usuario(){
        $this->IdMedico = $_POST['id'];
        $this->NombreMedico = $_POST['Nombre'];
        $this->Especialidad = $_POST['EspecialidadCMB'];
        $this->IdUsuario = $_POST['UsuarioCMB'];
    
        $sql = "UPDATE medicos SET Nombre='$this->NombreMedico',
                                    Especialidad='$this->Especialidad',
                                    IdUsuario='$this->IdUsuario'
                WHERE IdMedico=$this->IdMedico;";
    
        if($this->con->query($sql)){
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }
    }
    
    public function save_usuario() {
        $this->NombreMedico = $_POST['Nombre'];
        $this->Especialidad = $_POST['EspecialidadCMB'];
        $this->IdUsuario = $_POST['UsuarioCMB'];
    
        $sql = "INSERT INTO medicos VALUES(NULL,
                                             '$this->NombreMedico',
                                             '$this->Especialidad',
                                             '$this->IdUsuario');";
    
        if($this->con->query($sql)) {
            echo $this->_message_ok("guardó");
        } else {
            echo $this->_message_error("guardar");
        }
    }
    
    public function get_form($id = NULL) {
        if ($id === NULL) {
            $this->NombreMedico = NULL;
            $this->Especialidad = NULL;
            $this->IdUsuario = NULL;
    
            $flag = NULL;
            $op = "new";
    
        } else {
            $sql = "SELECT * FROM medicos WHERE IdMedico = $id;";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();
    
            $num = $res->num_rows;
            if ($num == 0) {
                $mensaje = "tratar de actualizar el medico con el id = " . $id;
                echo $this->_message_error($mensaje);
            } else {
                // ***** TUPLA ENCONTRADA *****
                echo "<br>TUPLA <br>";
                echo "<pre>";
                print_r($row);
                echo "</pre>";
    
                $this->NombreMedico = $row['Nombre'];
                $this->Especialidad = $row['Especialidad'];
                $this->IdUsuario = $row['IdUsuario'];
    
                $flag = "enabled";
                $op = "update";
            }
        }
    
        $html = '
        <br>
        <form name="rolpersona" method="POST" action="index.php" enctype="multipart/form-data">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <input type="hidden" name="id" value="' . $id  . '">
        <input type="hidden" name="op" value="' . $op  . '">
            <div class="container">
                <div class="table-responsive">
                <br>
                <table align="center" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                                <th colspan="2" class="text-center">DATOS DEL USUARIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nombre:</td>
                                <td><input type="text" name="Nombre" value="' . $this->NombreMedico . '" ' . $flag . '></td>
                            </tr>
                            <tr>
                                <td>Especialidad:</td>
                                <td>' . $this->_get_combo_db("especialidades","IdEsp","Descripcion","EspecialidadCMB",$this->Especialidad) . '</td>
                            </tr>
                            <tr>
                                <td>Usuario:</td>
                                <td>' . $this->_get_combo_db("usuarios","IdUsuario","Nombre","UsuarioCMB",$this->IdUsuario) . '</td>
                            </tr>
							<tr>
								<th colspan="2"><input type="submit" align="center" name="Guardar" value="GUARDAR" class="btn btn-primary"></th>
							</tr>												
							<tr>
								<th colspan="2" class="text-center"><a href="index.php">Regresar</a></th>
							</tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

					';
		return $html;
    }

    public function get_detail_usuario($id) {

        $sql = "SELECT m.IdMedico, m.Nombre as NombreMedico, u.Nombre as NombreUsuario, e.Descripcion as NombreEspecialidad, u.Foto, e.Dias, e.Franja_HI, e.Franja_HF FROM usuarios u, medicos m, especialidades e WHERE m.Especialidad=e.IdEsp and m.IdUsuario=u.IdUsuario AND IdMedico=$id;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();
    
        $num = $res->num_rows;
    
        if ($num == 0) {
            $mensaje = "tratar de editar el medico con el id= " . $id;
            echo $this->_message_error($mensaje);
        } else {
            $html = '
            <br>
				   <div class="container">
                   <table class="table table-bordered table-striped mx-auto" style="max-width: 800px;">
                   <thead class="thead-dark">
           <tr>
                <th colspan="8" class="text-center">Datos del Medico</th>
                </tr>
				</thead>
                <tbody>
					<tr>
						<td>Nombre: </td>
						<td>'. $row['NombreMedico'] .'</td>
					</tr>
                    <tr>
						<td>Usuario: </td>
						<td>'. $row['NombreUsuario'] .'</td>
					</tr>
                    <tr>
						<td>Especialidad: </td>
						<td>'. $row['NombreEspecialidad'] .'</td>
					</tr>
                    <tr>
						<td>Dias Atencion: </td>
						<td>'. $row['Dias'] .'</td>
					</tr>
                    <tr>
						<td>Horario Atencion: </td>
						<td>'. $row['Franja_HI'] .' - '. $row['Franja_HF'] .'</td>
					</tr>
                    <tr>
					<th colspan="2"><img src="' .PATH .'/' . $row['Foto'] . '" width="300px"/></th>
					
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
    
    
    public function get_list() {
        $d_new = "new/0";
        $d_new_final = base64_encode($d_new);
    
        $html = '
        <br>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
		<div class="container">
		<div class="table-responsive">
        <table class="table table-bordered table-striped table-hover mx-auto" style="max-width: 800px;">
        <thead class="thead-dark">
        <tr>
                <th colspan="8" class="text-center">Lista de Medicos</th>
                </tr>
					<tr>
						<th colspan="10" class="text-center">
							<a href="index.php?d=' . $d_new_final . '" class="btn btn-success">
								<i class="fas fa-plus"></i> Nuevo
							</a>
						</th>
					</tr>
					<tr>
						<th>Nombre</th>
                        <th>Especialidad</th>
						<th colspan="3" class="text-center">Acciones</th>
					</tr>
				</thead>';
    
        $sql = "SELECT m.IdMedico, m.Nombre as NombreMedico, u.Nombre as NombreUsuario, e.Descripcion as NombreEspecialidad FROM usuarios u, medicos m, especialidades e WHERE m.Especialidad=e.IdEsp and m.IdUsuario=u.IdUsuario;";
        $res = $this->con->query($sql);
    
        while ($row = $res->fetch_assoc()) {
            $d_det = "det/" . $row['IdMedico'];
			$d_det_final = base64_encode($d_det);
			$d_del = "del/" . $row['IdMedico'];
            $d_del_final = base64_encode($d_del);
            $d_act = "act/" . $row['IdMedico'];
            $d_act_final = base64_encode($d_act);
            $html .= '
                <tr>
                    <td>' . $row['NombreMedico'] . '</td>
                    <td>' . $row['NombreEspecialidad'] . '</td>
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
    
    public function delete_usuario($id) {
        $sql = "DELETE FROM medicos WHERE IdMedico=$id;";
        
        if ($this->con->query($sql)) {
            echo $this->_message_ok("ELIMINÓ");
        } else {
            echo $this->_message_error("eliminar");
        }
    }

    private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
    
    private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="index.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	

}
?>