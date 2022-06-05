<?php
session_start();



if(
    !isset($_SESSION['sesion']) ||
    $_SESSION['sesion'] == 0
) {
    header('Location: login.php');
    exit;
}

if(
  isset($_POST['cerrar']) ||
  $_SESSION['sesion'] == 0
) {
  header('Location: login.php');
  exit;
}



include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['usuario'])) {
    $consultaSQL = "SELECT * FROM registros WHERE usuario LIKE '%" . $_POST['usuario'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM registros";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $usuario = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}


try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  $consultaSQL2 = "SELECT correo FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";
  $consultaSQL3 = "SELECT portal FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";
  $consultaSQL4 = "SELECT aula_virtual FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";
  $consultaSQL5 = "SELECT cloud FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";

  $sentencia2 = $conexion->prepare($consultaSQL2);
  $sentencia2->execute();
  $usuario2 = $sentencia2->fetch(PDO::FETCH_ASSOC);

  $sentencia3 = $conexion->prepare($consultaSQL3);
  $sentencia3->execute();
  $usuario3 = $sentencia3->fetch(PDO::FETCH_ASSOC);

  $sentencia4 = $conexion->prepare($consultaSQL4);
  $sentencia4->execute();
  $usuario4 = $sentencia4->fetch(PDO::FETCH_ASSOC);

  $sentencia5 = $conexion->prepare($consultaSQL5);
  $sentencia5->execute();
  $usuario5 = $sentencia5->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}

if(
  $usuario2["correo"] == "admin" ||
  $usuario3["portal"] == "admin" ||
  $usuario4["aula_virtual"] == "admin" ||
  $usuario5["cloud"] == "admin" ||
  $usuario2["correo"] == "*" ||
  $usuario3["portal"] == "*" ||
  $usuario4["aula_virtual"] == "*" ||
  $usuario5["cloud"] == "*" 
){
$_SESSION["permiso"] = 1;
}else{
$_SESSION["permiso"] = 0;
}




$consultaSQL6 = "SELECT correo FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";
$consultaSQL7 = "SELECT portal FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";
$consultaSQL8 = "SELECT aula_virtual FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";
$consultaSQL9 = "SELECT cloud FROM registros WHERE usuario ='" . $_SESSION["inicio"] ."'";

$sentencia6 = $conexion->prepare($consultaSQL6);
$sentencia6->execute();
$usuario6 = $sentencia6->fetch(PDO::FETCH_ASSOC);

$sentencia7 = $conexion->prepare($consultaSQL7);
$sentencia7->execute();
$usuario7 = $sentencia7->fetch(PDO::FETCH_ASSOC);

$sentencia8 = $conexion->prepare($consultaSQL8);
$sentencia8->execute();
$usuario8 = $sentencia8->fetch(PDO::FETCH_ASSOC);

$sentencia9 = $conexion->prepare($consultaSQL9);
$sentencia9->execute();
$usuario9 = $sentencia9->fetch(PDO::FETCH_ASSOC);




$titulo = isset($_POST['servicio']) ? 'Lista de usuario (' . $_POST['servicio'] . ')' : 'Lista de usuarios';
?>

<?php include "templates/header.php"; 


$borrado = $_SESSION['permiso'] ;



?>



<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
    <form action="" method="post">
      <br>
      <h1>Bienvenido <?=$_SESSION["inicio"]?> </h1>
      <a href="perfil.php" class="btn btn-dark float-right"> Perfil</a>
      <input type="submit" name="cerrar" class="btn btn-danger mr-2 float-right" id="cerrar" value="Cerrar Sesion"></input>
      <a href="crear.php"  class="btn btn-primary mt-4">Crear usuario</a>
    </form>
      
      
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="usuario" name="usuario" placeholder="Buscar por usuario" class="form-control">
        </div>
        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
        <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3"><?= $titulo ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Usuario</th>
            <th>Servicio</th>
            <th>Host</th>
            <th class="bg-primary">Permisos</th>
            <th class="bg-primary">Correo</th>
            <th class="bg-primary">Portal</th>
            <th class="bg-primary">A.virtual</th>
            <th class="bg-primary">Cloud</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($usuario && $sentencia->rowCount() > 0) {
            foreach ($usuario as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id"]); ?></td>
                <td><?php echo escapar($fila["usuario"]); ?></td>
                <td><?php echo escapar($fila["servicio"]); ?></td>
                <td><?php echo escapar($fila["host"]); ?></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;➜</td>
                <td><?php echo escapar($fila["correo"]); ?></td>
                <td><?php echo escapar($fila["portal"]); ?></td>
                <td><?php echo escapar($fila["aula_virtual"]); ?></td>
                <td><?php echo escapar($fila["cloud"]); ?></td>
                <td>
     


                <?php if (  
                  
                  $usuario6["correo"] == "admin" && $fila["correo"] == "basico"||
                  $usuario7["portal"] == "admin" && $fila["portal"] == "basico" ||
                  $usuario8["aula_virtual"] == "admin" && $fila["aula_virtual"] == "basico" ||
                  $usuario9["cloud"] == "admin" && $fila["cloud"] == "basico" ||

                  $usuario6["correo"] == "*" && $fila["correo"] == "basico" ||
                  $usuario7["portal"] == "*" && $fila["portal"] == "basico" ||
                  $usuario8["aula_virtual"] == "*" && $fila["aula_virtual"] == "basico" ||
                  $usuario9["cloud"] == "*" && $fila["cloud"] == "basico" ||

                  $usuario6["correo"] == "*" && $fila["correo"] == "admin" ||
                  $usuario7["portal"] == "*" && $fila["portal"] == "admin" ||
                  $usuario8["aula_virtual"] == "*" && $fila["aula_virtual"] == "admin" ||
                  $usuario9["cloud"] == "*" && $fila["cloud"] == "admin" ||

                  $usuario6["correo"] == "*" && $fila["correo"] == "nada" ||
                  $usuario7["portal"] == "*" && $fila["portal"] == "nada" ||
                  $usuario8["aula_virtual"] == "*" && $fila["aula_virtual"] == "nada" ||
                  $usuario9["cloud"] == "*" && $fila["cloud"] == "nada") 
                  
                  
                  {  

                 ?> 
                

                  <a href="<?= 'borrar.php?id=' . escapar($fila["id"]) ?>">🗑️Borrar</a>
                  <a href="<?= 'editar.php?id=' . escapar($fila["id"]) ?>">✏️Editar</a>
               <?php } else { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>-</b>"; } ?>

               
                  
                
                  
                </td>
              </tr>
              <?php
            }
          }
          ?>
         
        <tbody>
      </table>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>