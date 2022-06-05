<?php
  session_start();
  $config = include 'config.php';


if(
    !isset($_SESSION['sesion']) ||
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

$config = include 'config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

if (!isset($_GET['id'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'El usuario no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $usuario = [
      "id"        => $_GET['id'],
      "usuario"    => $_POST['usuario'],
      "servicio"  => $_POST['servicio'],
      "host"      => $_POST['host'],
      "correo"     => $_POST['permisos1'],
      "portal"     => $_POST['permisos2'],
      "aula_virtual"     => $_POST['permisos3'],
      "cloud"     => $_POST['permisos4'],
    ];
    
    $consultaSQL = "UPDATE registros SET
        usuario = :usuario,
        servicio = :servicio,
        host = :host,
        correo = :correo,
        portal = :portal,
        aula_virtual = :aula_virtual,
        cloud = :cloud,
        updated_at = NOW()
        WHERE id = :id";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($usuario);
    
  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id'];
  $consultaSQL = "SELECT * FROM registros WHERE id =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();
  $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);



  $consultaSQL2 = "SELECT correo FROM registros WHERE id =" . $id;
  $consultaSQL3 = "SELECT portal FROM registros WHERE id =" . $id;
  $consultaSQL4 = "SELECT aula_virtual FROM registros WHERE id =" . $id;
  $consultaSQL5 = "SELECT cloud FROM registros WHERE id =" . $id;

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






  if (!$usuario) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el usuario';
  }

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}



?>


<?php require "templates/header.php"; ?>

<?php
if ($resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($usuario) && $usuario) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Editando el usuario <?= escapar($usuario['usuario']) . ' ' . escapar($usuario['servicio'])  ?></h2>
        <hr>
        <form method="post">
          <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" value="<?= escapar($usuario['usuario']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="servicio">servicio</label>
            <input type="text" name="servicio" id="servicio" value="<?= escapar($usuario['servicio']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="host">Host</label>
            <input type="text" name="host" id="host" value="<?= escapar($usuario['host']) ?>" class="form-control">
          </div>
          <div class="form-group">
          <br/>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Permisos</th>
                  <th scope="col">Admin</th>
                  <th scope="col">Basico</th>
                  <th scope="col">Nada</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">correo</th>
                  <?php if ($usuario2["correo"] == "admin"){?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio" checked name="permisos1" id="permisos1" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>
                  <td><input style="width: 20" type="radio"  name="permisos1" id="permisos1" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos1" id="permisos1" value="nada"></td>
                  <?php }elseif($usuario2["correo"] == "basico"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos1" id="permisos1" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>
                  <td><input style="width: 20" type="radio" checked name="permisos1" id="permisos1" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos1" id="permisos1" value="nada"></td>
                  <?php }elseif($usuario2["correo"] == "nada"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos1" id="permisos1" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>
                  <td><input style="width: 20" type="radio"  name="permisos1" id="permisos1" value="basico"></td>
                  <td><input style="width: 20" type="radio" checked name="permisos1" id="permisos1" value="nada"></td>
                  <?php } ?>
                </tr>
                <tr>
                  <th scope="row">Portal</th>
                  <?php if ($usuario3["portal"] == "admin"){?>
                    <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio" checked name="permisos2" id="permisos2" value="admin"></td>
                    <?php }else{?>
                  <td> </td>
                    <?php }?>                  
                  <td><input style="width: 20" type="radio"  name="permisos2" id="permisos2" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos2" id="permisos2" value="nada"></td>
                  <?php }elseif($usuario3["portal"] == "basico"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos2" id="permisos2" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>  
                  <td><input style="width: 20" type="radio" checked name="permisos2" id="permisos2" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos2" id="permisos2" value="nada"></td>
                  <?php }elseif($usuario3["portal"] == "nada"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos2" id="permisos2" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>  
                  <td><input style="width: 20" type="radio"  name="permisos2" id="permisos2" value="basico"></td>
                  <td><input style="width: 20" type="radio" checked name="permisos2" id="permisos2" value="nada"></td>
                  <?php } ?>
                </tr>
                <tr>
                <th scope="row">Aula virtual</th>
                <?php if ($usuario4["aula_virtual"] == "admin"){?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio" checked name="permisos3" id="permisos3" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>                  
                  <td><input style="width: 20" type="radio"  name="permisos3" id="permisos3" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos3" id="permisos3" value="nada"></td>
                  <?php }elseif($usuario4["aula_virtual"] == "basico"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos3" id="permisos3" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>  
                  <td><input style="width: 20" type="radio" checked name="permisos3" id="permisos3" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos3" id="permisos3" value="nada"></td>
                  <?php }elseif($usuario4["aula_virtual"] == "nada"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos3" id="permisos3" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>  
                  <td><input style="width: 20" type="radio"  name="permisos3" id="permisos3" value="basico"></td>
                  <td><input style="width: 20" type="radio" checked name="permisos3" id="permisos3" value="nada"></td>
                  <?php } ?>
                </tr>
                <tr>
                <th scope="row">Cloud</th>
                <?php if ($usuario5["cloud"] == "admin"){?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio" checked name="permisos4" id="permisos4" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>                  
                  <td><input style="width: 20" type="radio"  name="permisos4" id="permisos4" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos4" id="permisos4" value="nada"></td>
                  <?php }elseif($usuario5["cloud"] == "basico"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos4" id="permisos4" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>  
                  <td><input style="width: 20" type="radio" checked name="permisos4" id="permisos4" value="basico"></td>
                  <td><input style="width: 20" type="radio"  name="permisos4" id="permisos4" value="nada"></td>
                  <?php }elseif($usuario5["cloud"] == "nada"){ ?>
                  <?php if ($_SESSION["inicio"] == "admin"){?>
                  <td><input style="width: 20" type="radio"  name="permisos4" id="permisos4" value="admin"></td>
                  <?php }else{?>
                  <td> </td>
                  <?php }?>  
                  <td><input style="width: 20" type="radio"  name="permisos4" id="permisos4" value="basico"></td>
                  <td><input style="width: 20" type="radio" checked name="permisos4" id="permisos4" value="nada"></td>
                  <?php } ?>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="form-group">
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php require "templates/footer.php"; ?>
