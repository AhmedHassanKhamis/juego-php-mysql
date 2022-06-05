<?php
session_start();

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
try{
  $config = include 'config.php';
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

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

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}

if (isset($_POST['submit'])) {

  if (strlen($_POST["usuario"]) < 3 ){
    $username=true;
    
  }
  if (strlen($_POST["pass"]) < 5 ){
    $pass=true;

  }
  if (strlen($_POST["servicio"]) < 5 ){
    $servicio=true;

  }
  if (strlen($_POST["host"]) < 3 ){
    $host=true;

  }

  if ($username==true || $pass==true || $servicio==true  || $host==true){

    $campos="<h3 class='error'> Complete los campos necesarios </h3>";

  }else{

      $resultado = [
        'error' => false,
        'mensaje' => 'El usuario ' . escapar($_POST['usuario']) . ' ha sido agregado con éxito'
      ];

      $config = include 'config.php';

      try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $usuario = [
          "usuario"   => $_POST['usuario'],
          "servicio"    => $_POST['servicio'],
          "host"     => $_POST['host'],
          "correo"     => $_POST['permisos1'],
          "portal"     => $_POST['permisos2'],
          "aula_virtual"     => $_POST['permisos3'],
          "cloud"     => $_POST['permisos4'],
          "pass"     => $_POST['pass'],
        ];

        $consultaSQL = "INSERT INTO registros (usuario, servicio, host, correo,portal,aula_virtual,cloud, pass)";
        $consultaSQL .= "values (:" . implode(", :", array_keys($usuario)) . ")";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($usuario);
      } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
      }
    }
}
?>

<?php include 'templates/header.php'; ?>

<?php
if (isset($resultado)) {
  ?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
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
      <h2 class="mt-4">Crea un Usuario</h2>
      <hr>
      <?=$campos?>

        <form method="post">
          <div class="form-group">
            <label for="usuario" <?=$clase1=($username)?'class="error"':'class="error2"'?>>Usuario</label>
            <input type="text" class="form-control" name="usuario" id="usuario">
          </div>
          <div class="form-group">
            <label for="pass" <?=$clase2=($pass)?'class="error"':'class="error2"'?>>Contraseña</label>
            <input type="text" name="pass" id="pass" class="form-control">
          </div>
          <div class="form-group">
            <label for="servicio" <?=$clase3=($servicio)?'class="error"':'class="error2"'?>>servicio</label>
            <input type="text" name="servicio" id="servicio" class="form-control">
          </div>
          <div class="form-group">
            <label for="host" <?=$clase5=($host)?'class="error"':'class="error2"'?> >Host</label>
            <input type="text"  name="host" id="host" class="form-control">
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
                  <?php if ($usuario6["correo"] == "*") {?>
                  <td><input style="width: 20" type="radio" name="permisos1" id="permisos1" value="admin"></td>
                  <?php }else{?>
                  <td></td>
                  <?php } ?>
                  <td><input style="width: 20" type="radio" name="permisos1" id="permisos1" value="basico"></td>
                  <td><input style="width: 20" type="radio" name="permisos1" id="permisos1" value="nada"></td>
                </tr>
                <tr>
                  <th scope="row">Portal</th>
                  <?php if ($usuario7["portal"] == "*") {?>
                  <td><input style="width: 20" type="radio" name="permisos2" id="permisos2" value="admin"></td>
                  <?php }else{?>
                  <td></td>
                  <?php } ?>                  
                  <td><input style="width: 20" type="radio" name="permisos2" id="permisos2" value="basico"></td>
                  <td><input style="width: 20" type="radio" name="permisos2" id="permisos2" value="nada"></td>
                </tr>
                <tr>
                  <th scope="row">Aula Virtual</th>
                  <?php if ($usuario8["aula_virtual"] == "*") {?>
                  <td><input style="width: 20" type="radio" name="permisos3" id="permisos3" value="admin"></td>
                  <?php }else{?>
                  <td></td>
                  <?php } ?>                  
                  <td><input style="width: 20" type="radio" name="permisos3" id="permisos3" value="basico"></td>
                  <td><input style="width: 20" type="radio" name="permisos3" id="permisos3" value="nada"></td>
                </tr>
                <tr>
                  <th scope="row">cloud</th>
                  <?php if ($usuario9["cloud"] == "*") {?>
                  <td><input style="width: 20" type="radio" name="permisos4" id="permisos4" value="admin"></td>
                  <?php }else{?>
                  <td></td>
                  <?php } ?>                  
                  <td><input style="width: 20" type="radio" name="permisos4" id="permisos4" value="basico"></td>
                  <td><input style="width: 20" type="radio" name="permisos4" id="permisos4" value="nada"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <br>
          <div class="form-group">
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
          </div>
        </form>

    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>