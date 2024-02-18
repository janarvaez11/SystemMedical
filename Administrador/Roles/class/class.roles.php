<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

<?php
class rolpersona {
    private $IdRol;
    private $Nombre;

    private $Accion;
    private $con;

    function __construct($cn) {
        $this->con = $cn;
    }

    public function update_rol(){
        $this->IdRol = $_POST['IdRol'];
        $this->Nombre = $_POST['Nombre'];
        $this->Accion = $_POST['Accion'];

    
        // Aquí puedes agregar otras propiedades según la estructura de tu tabla
    
        $sql = "UPDATE roles SET Nombre='$this->Nombre', Accion='$this->Accion'
                WHERE IdRol=$this->IdRol;";
    
        if($this->con->query($sql)){
            echo $this->_message_ok("modificó");
        } else {
            echo $this->_message_error("al modificar");
        }
    }
    
    public function save_rol() {
        $this->Nombre = $_POST['Nombre'];
        $this->Accion = $_POST['Accion'];
    
        $sql = "INSERT INTO roles VALUES(NULL,
                                             '$this->Nombre',
                                             '$this->Accion');";
    
        if($this->con->query($sql)) {
            echo $this->_message_ok("guardó");
        } else {
            echo $this->_message_error("guardar");
        }
    }
    
    public function get_form($id = NULL) {
        if ($id === NULL) {
            $this->Nombre = NULL;
            $this->Accion = NULL;
    
            $flag = NULL;
            $op = "new";
    
        } else {
            $sql = "SELECT * FROM roles WHERE IdRol = $id;";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();
    
            $num = $res->num_rows;
            if ($num == 0) {
                $mensaje = "tratar de actualizar el rol de la persona con RolPersonaId= " . $id;
                echo $this->_message_error($mensaje);
            } else {
                // ***** TUPLA ENCONTRADA *****
                /* echo "<br>TUPLA <br>";
                echo "<pre>";
                print_r($row);
                echo "</pre>"; */
    
                $this->Nombre = $row['Nombre'];
                $this->Accion = $row['Accion'];
    
                $flag = "enabled";
                $op = "update";
            }
        }
    
        $html = '
        <form name="rolpersona" method="POST" action="index.php" enctype="multipart/form-data">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <input type="hidden" name="IdRol" value="' . $id  . '">
				<input type="hidden" name="op" value="' . $op  . '">
                <br>
				<div class="container">
				<div class="table-responsive">
                <table align="center" class="table table-bordered table-striped">
                <thead class="thead-dark">
                  <tr>
				  <th colspan="2" class="text-center">Datos del Rol de la Persona</th>
				  </tr>
				  </thead>
						<div class="form-group">
							<tr>
								<td>Nombre:</td>
								<td><input type="text" name="Nombre" value="' . $this->Nombre . '" ' . $flag . '></td>
						</tr>
                        </div>
                        <div class="form-group">
							<tr>
								<td>Accion:</td>
								<td><input type="text" name="Accion" value="' . $this->Accion . '" ' . $flag . '></td>
						</tr>
                        </div>
						<tr>
							<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR" class="btn btn-primary"></th>
						</tr>			
                        <tr>
                        <th colspan="2" class="text-center"><a href="index.php">Regresar</a></th>
                    </tr>                            
					</table>
					';
		return $html;
    }

    public function get_detail_rol($id) {
        $sql = "SELECT * FROM roles WHERE IdRol=$id;";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();
    
        $num = $res->num_rows;
    
        if ($num == 0) {
            $mensaje = "tratar de editar el rolpersona con RolPersonaId= " . $id;
            echo $this->_message_error($mensaje);
        } else {
            $html = '
            <br>
				   <div class="container">
				   <table class="table table-bordered">
				   <thead class="table-dark">
                <tr>
                <th colspan="8" class="text-center">Datos del Rol de la Persona</th>
                </tr>
				</thead>
                <tbody>
					<tr>
						<td>Nombre: </td>
						<td>'. $row['Nombre'] .'</td>
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
                <th colspan="8" class="text-center">Lista de Rol de la Persona</th>
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
						<th colspan="3" class="text-center">Acciones</th>
					</tr>
				</thead>
                <tbody>';
    
        $sql = "SELECT * FROM roles;";
        $res = $this->con->query($sql);
    
        while ($row = $res->fetch_assoc()) {
            $d_det = "det/" . $row['IdRol'];
			$d_det_final = base64_encode($d_det);
			$d_del = "del/" . $row['IdRol'];
            $d_del_final = base64_encode($d_del);
            $d_act = "act/" . $row['IdRol'];
            $d_act_final = base64_encode($d_act);
            $html .= '
                <tr>
                    <td>' . $row['Nombre'] . '</td>
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
    
        $html .= '  
        </tbody></table></div>';
    
        return $html;
    }
    
    public function delete_rol($id) {
        $sql = "DELETE FROM roles WHERE IdRol=$id;";
        
        if ($this->con->query($sql)) {
            echo $this->_message_ok("ELIMINÓ");
        } else {
            echo $this->_message_error("eliminar");
        }
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