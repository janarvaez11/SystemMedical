

<?php
class usuario{
	private $IdUsuario;
	private $usuario;
	private $Nombre;
	private $Rol;
	private $NombreRol;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}

		public function bienvenida_usuario(){

			$usuario = $_SESSION['usuario'];
			$Rol = $_SESSION['Rol'];

			$sql = "SELECT u.IdUsuario, u.Nombre, u.Rol, r.Nombre as NombreRol
			FROM usuarios u, roles r
			WHERE u.Rol=r.IdRol and IdUsuario = '$usuario';";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de enviar la consulta";
                echo $this->_message_error($mensaje);
            }else{   
			
				$this->IdUsuario = $row['IdUsuario'];
				$this->Nombre = $row['Nombre'];
				$this->NombreRol = $row['NombreRol'];
				$Rol = $row['Rol'];

			}
			
			if ($Rol == 1) {
				// Si el rol es 1 (ADM), mostrar un mensaje de bienvenida personalizado y redirigir.
				echo "<script>alert('Bienvenido Señor $this->Nombre');";
				echo "window.location.href = '../../Administrador/index.html';";
				echo "</script>";
				exit;
			} elseif ($Rol == 2) {
				echo "Bienvenido $this->NombreRol";
			} elseif ($Rol == 3) {
				
				echo "Bienvenido $this->NombreRol";

			}

		
		//echo $html;
		}

	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla WHERE Rol = 1;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	

	public function get_login(){
		$sql = "SELECT IdUsuario
				FROM usuarios;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		$num = $res->num_rows;
		if($num == 0){
			$mensaje = "tratar de enviar la consulta";
			echo $this->_message_error($mensaje);
		} else {   
			$this->IdUsuario = $row['IdUsuario'];
		}
	
		$html = '
			<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

			<style>
            body {
                background-color: #f8f9fa;
            }
            .container {
                margin-top: 50px;
            }
            .card {
                border: 1px solid #ced4da;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .card-body {
                padding: 30px;
            }
            .card-title {
                color: #1E2830;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-control {
                border: 1px solid #1E2830;
                border-radius: 4px;
                padding: 10px;
            }
            .btn-primary {
                background-color: #41698A;
                color: #fff;
                font-weight: bold;
            }
            .btn-danger {
                background-color: #dc3545;
                color: #fff;
                font-weight: bold;
            }
			.btn-secondary.active {
					background-color: #28a745;
					color: #fff;
				}
			</style>
	
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-6">
						<div style="background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 28px;" class="card mt-5">                           
							<div class="card-body">
								<h2 style="color: #1E2830;" class="card-title text-center">Iniciar Sesión</h2>
								<form name="login" method="POST" action="validar.php" enctype="multipart/form-data">
									<div class="form-group">
										<label for="usuario">Usuario</label>
										' . $this->_get_combo_db("usuarios", "IdUsuario", "Nombre", "usuario", $this->IdUsuario) . '
									</div>
									
									<div class="form-group" style="margin-bottom: 20px;">
										<label for="clave">Contraseña</label>
										<input style="border: 1px solid #1E2830; border-radius: 4px; padding: 10px;" type="password" class="form-control" id="clave" placeholder="&#128272; Contraseña" name="clave">
									</div>
	
									<div class="form-group text-center">
										<button type="button" class="btn btn-secondary" id="toggleButton">Activar 2FA Autenticación</button>
										<span id="statusBadge" class="badge badge-light ml-2">Inactivo</span>
									</div>
									
									<button style="padding: 10px; border-radius: 4px; font-weight: bold; cursor: pointer; background-color: #41698A; color: #fff;" class="btn btn-primary btn-block" type="submit" name="LOGIN">Iniciar sesión</button>
									<a style="padding: 10px; border-radius: 4px; font-weight: bold; cursor: pointer; background-color: #dc3545; color: #fff;" href="../../index.html" class="btn btn-danger btn-block">Cancelar</a>
								</form>

								<!-- Iconos para iniciar sesión con redes sociales -->
                            	<div class="text-center mt-3">
                                	<p>o inicia sesión con:</p>
                                	<a href="#" class="btn btn-outline-primary mr-2">
                                    	<i class="fab fa-facebook"></i> Facebook
                                	</a>
                                	<a href="#" class="btn btn-outline-danger">
                                    <i class="fab fa-google"></i> Gmail
                                	</a>
                            	</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					var button = document.getElementById("toggleButton");
					var statusBadge = document.getElementById("statusBadge");
	
					button.addEventListener("click", function() {
						button.classList.toggle("active");
						statusBadge.innerText = button.classList.contains("active") ? "Activo" : "Inactivo";
					});
				});
			</script>
		';
	
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
	
//****************************************************************************	
	
} // FIN SCRPIT
?>

