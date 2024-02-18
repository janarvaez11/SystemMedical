<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Medicamentos</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
	<?php
		require_once("../../constantes.php");
		include_once("class/class.medicamentos.php");
		
		$cn = conectar();
		$v = new rolpersona($cn);
		
		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $v->delete_medicamento($id);
			}elseif($op == "det"){
				echo $v->get_detail_medicamento($id);
			}elseif($op == "new"){
				echo $v->get_form();
			}elseif($op == "act"){
				echo $v->get_form($id);
			}
			
       // PARTE III	
		}else{
			   
				/* echo "<br>PETICION POST <br>";
				echo "<pre>";
					print_r($_POST);
				echo "</pre>"; */
		      
			if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				$v->save_medicamento();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$v->update_medicamento();
			}else{
				echo $v->get_list();
			}	
		}
		
	//*******************************************************
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
	//**********************************************************	

		
	?>	
</body>
</html>
