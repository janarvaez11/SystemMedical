
<?php
	session_start();
?>
<?php

	require_once("../../constantes.php");
	require_once("class.usuarios.php");

	$cn = conectar();
		$v = new usuario($cn);
	
	$usuario = $_POST['usuario'];
	$clave = $_POST['clave']; 
	
	//session_start();

		$_SESSION['usuario']=$usuario;
/* 
	echo "<br>PETICION POST <br>";
				echo "<pre>";
					print_r($_POST);
				echo "</pre>";
		
				echo "<br>PETICION session <br>";
				echo "<pre>";
					print_r($_SESSION);
				echo "</pre>"; */
		

	$sql = "SELECT * FROM usuarios WHERE IdUsuario = '$usuario' and Password = '$clave'";
			$res = $cn->query($sql);
			$row = $res->fetch_assoc();

			$rol = $row['Rol'];
			$_SESSION['Rol']=$rol;
			
			$num = $res->num_rows;
            if($num>0){	
                echo "<br>USUARIO  ENCONTRADO <br>";
				header("Location: index.php?usuario=$usuario");
            }else{   
			
				echo"<script>";
				echo "alert('ERROR EN LA AUTENTIFICACION');";
				echo "window.location.href = 'index.php';";
				echo"</script>";

			}
		

	function conectar(){
		//echo "<br> CONEXION A LA BASE DE DATOS<br>";
		$c = new mysqli(SERVER,USER,PASS,BD);
				
		if($c->connect_errno) {
			die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
		}else{
			//echo "La conexión tuvo éxito .......<br><br>";
		}
				
		$c->set_charset("utf8");
		return $c;
		}
?>