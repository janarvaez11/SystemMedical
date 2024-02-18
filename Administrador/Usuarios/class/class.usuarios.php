<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<?php
class usuarios {
    private $IdUsuario;
    private $RolNombre;
    private $NombreUsuario;
    private $Password;
    private $Rol;
    private $Foto;
    private $con;

    function __construct($cn) {
        $this->con = $cn;
    }

    public function update_usuario(){
        $this->IdUsuario = $_POST['id'];
        $this->NombreUsuario = $_POST['Nombre'];
        $this->Password = $_POST['Password'];
        $this->Rol = $_POST['RolCMB'];
        $this->Foto = $_FILES['Foto']['name'];
    
        $sql = "UPDATE usuarios SET Nombre='$this->NombreUsuario',
                                    Password='$this->Password',
                                    Rol='$this->Rol',
                                    Foto='$this->Foto'
                WHERE IdUsuario=$this->IdUsuario;";
    
        if($this->con->query($sql)){
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }
    }
    
    public function save_usuario() {
        $this->NombreUsuario = $_POST['Nombre'];
        $this->Password = $_POST['Password'];
        $this->Rol = $_POST['RolCMB'];
        $this->Foto = $_FILES['Foto']['name'];
       // $this->Foto = $this->_get_name_file($_FILES['Foto']['name'],12);

        $path = "PATHPac" . $this->Foto;

        //exit;
        if (!move_uploaded_file($_FILES['Foto']['tmp_name'], $path)) {
            $mensaje = "Cargar la imagen";
            echo $this->_message_error($mensaje);
            exit;
        }  

        $sql = "INSERT INTO usuarios VALUES(NULL,
        '$this->NombreUsuario',
        '$this->Password',
        '$this->Rol',
        '$this->Foto');";

        if($this->con->query($sql)) {
            echo $this->_message_ok("guardó");
        } else {
            echo $this->_message_error("guardar");
        }
    }



	/* private function _get_name_file($nombre_original, $tamanio)
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
	} */


    
    public function get_form($id = NULL) {
        if ($id === NULL) {
            $this->NombreUsuario = NULL;
            $this->Password = NULL;
            $this->Rol = NULL;
            $this->Foto = NULL;
    
            $flag = NULL;
            $op = "new";
    
        } else {
            $sql = "SELECT * FROM usuarios WHERE IdUsuario = $id;";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();
    
            $num = $res->num_rows;
            if ($num == 0) {
                $mensaje = "tratar de actualizar el usuario con el id = " . $id;
                echo $this->_message_error($mensaje);
            } else {
                // ***** TUPLA ENCONTRADA *****
                /* echo "<br>TUPLA <br>";
                echo "<pre>";
                print_r($row);
                echo "</pre>"; */
    
                $this->NombreUsuario = $row['Nombre'];
                $this->Password = $row['Password'];
                $this->Rol = $row['Rol'];
                $this->Foto = $row['Foto'];
    
                $flag = "enabled";
                $op = "update";
            }
        }
    
        $html = '
        <form name="rolpersona" method="POST" action="index.php" enctype="multipart/form-data">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <input type="hidden" name="id" value="' . $id  . '">
        <input type="hidden" name="op" value="' . $op  . '">
        <br>
            <div class="container">
                <div class="table-responsive">
                <table align="center" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                                <th colspan="2" class="text-center">Datos del Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nombre:</td>
                                <td><input type="text" name="Nombre" value="' . $this->NombreUsuario . '" ' . $flag . '></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><input type="text" name="Password" value="' . $this->Password . '" ' . $flag . '></td>
                            </tr>
                            <tr>
                                <td>Rol:</td>
                                <td>' . $this->_get_combo_db("roles","IdRol","Nombre","RolCMB",$this->Rol) . '</td>
                            </tr>
                            <tr>
                                <td>Foto:</td>
                                <td><input type="file" name="Foto"></td>
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

        $sql = "SELECT u.IdUsuario, r.Nombre as RolNombre, r.Accion, u.Nombre AS NombreUsuario, u.Foto FROM usuarios u, roles r WHERE u.Rol=r.IdRol AND IdUsuario=$id;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();
    
        $num = $res->num_rows;
    
        if ($num == 0) {
            $mensaje = "tratar de editar el usuario con el id= " . $id;
            echo $this->_message_error($mensaje);
        } else {
            $html = '
            <br>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
   				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
				   <div class="container">
                   <table class="table table-bordered table-striped mx-auto" style="max-width: 800px;">
                   <thead class="thead-dark">
           <tr>
                <th colspan="8" class="text-center">Datos del Usuario</th>
                </tr>
				</thead>
                <tbody>
					<tr>
						<td>Nombre: </td>
						<td>'. $row['NombreUsuario'] .'</td>
					</tr>
                    <tr>
						<td>Rol: </td>
						<td>'. $row['RolNombre'] .'</td>
					</tr>
                    <tr>
                        <td>Foto: </td>
                        <td><img src="' . PATHPac . '/' . $row['Foto'] . '" width="200"></td>
                    </tr>
                    <tr>
						<td>Accion: </td>
						<td>'. $row['Accion'] .'</td>
					</tr>
					<tr>
                    <th colspan="2" class="text-center"><a href="index.php">Regresar</a></th>
                    </th>
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
                <th colspan="8" class="text-center">Lista de Usuarios</th>
                </tr>
					<tr>
						<th colspan="8" class="text-center">
							<a href="index.php?d=' . $d_new_final . '" class="btn btn-success">
								<i class="fas fa-plus"></i> Nuevo
							</a>
						</th>
					</tr>
					<tr>
						<th>Nombre</th>
                        <th>Rol</th>
						<th colspan="3" class="text-center">Acciones</th>
					</tr>
				</thead>';
    
        $sql = "SELECT u.IdUsuario, u.Nombre as NombreUsuario, u.Foto, r.Nombre as RolNombre FROM usuarios u, roles r WHERE u.Rol=r.IdRol;";
        $res = $this->con->query($sql);
    
        while ($row = $res->fetch_assoc()) {
            $d_det = "det/" . $row['IdUsuario'];
			$d_det_final = base64_encode($d_det);
			$d_del = "del/" . $row['IdUsuario'];
            $d_del_final = base64_encode($d_del);
            $d_act = "act/" . $row['IdUsuario'];
            $d_act_final = base64_encode($d_act);
            $html .= '
                <tr>
                    <td>' . $row['NombreUsuario'] . '</td>
                    <td>' . $row['RolNombre'] . '</td>
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
        $sql = "DELETE FROM usuarios WHERE IdUsuario=$id;";
        
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