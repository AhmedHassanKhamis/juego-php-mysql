<?php
include 'funciones.php';
include 'templates/header.php';


session_start();

//UPDATE on WHERE

if (isset($_POST["enviar"])){

if ($_POST["contra1"] == $_POST["contra2"] || (strlen($_POST["contra1"])) < 5){
$class=true;
$resultado = "ERROR: contraseña debe ser minimo 5";
$error = false;
$config = include 'config.php';


?>
<div class="container mt-3">
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-danger ?>" role="alert">
        <?php echo $resultado?>
      </div>
    </div>
  </div>
</div>   
<?php                    
}else{


                    $nombrecito=$_SESSION["inicio"];
                    $contra=$_POST["contra1"];
                    $resultado = "Contraseña actualizada con exito";

                    $link = mysqli_connect("localhost", "php", "php", "pagina_crud");
                        // Chequea coneccion
                        if($link === false){
                            die("ERROR: No pudo conectarse con la DB. " . mysqli_connect_error());
                        }
                        // Ejecuta la actualizacion del registro


                    $sql = "UPDATE registros SET pass='$contra' WHERE usuario='$nombrecito'";



                    if(mysqli_query($link, $sql)){
                      ?>
                      <div class="container mt-3">
                        <div class="row">
                         <div class="col-md-12">
                            <div class="alert alert-success ?>" role="alert">
                              <?php echo $resultado?>
                            </div>
                          </div>
                        </div>
                      </div>   
                    <?php                 
                  } 
                    // Cierra la conexion
                    mysqli_close($link);
                }
        }


        

           $config = include 'config.php';
           $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
           $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);    

          if (isset($_SESSION['inicio'])) {
            $consultaSQL = "SELECT correo,portal,aula_virtual,cloud FROM registros WHERE usuario = '" . $_SESSION['inicio'] . "'";
          } else {
            $consultaSQL = "SELECT correo,portal,aula_virtual,cloud FROM registros";
          }

          $sentencia = $conexion->prepare($consultaSQL);
          $sentencia->execute();
        
          $usuario = $sentencia->fetchAll();
          
      


?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-4">Perfil de <?=$_SESSION["inicio"]?></h2>
      <hr>

      <table class="table table-striped col-6">
  <thead>
        <h4>Permisos</h4>
  </thead>
  <tbody>
    <?php
     
        foreach ($usuario as $fila) {
    ?>    
    <tr>
      <th scope="row">Correo</th>
      <td><?php echo escapar($fila["correo"]); ?></td>
    </tr>
    <tr>
    <th scope="row">Portal</th>
      <td><?php echo escapar($fila["portal"]); ?></td>
    </tr>
    <th scope="row">Aula Virtual</th>
      <td><?php echo escapar($fila["aula_virtual"]); ?></td>
    </tr>
    <th scope="row">Cloud</th>
      <td><?php echo escapar($fila["cloud"]); ?></td>
    </tr>
    <?php
            }
          
    ?>  
  </tbody>
</table>
<br>



      <?=$campos?>

        <form method="post">
          <div class="form-group">
            <label for="contra1" <?=$clase1=($class)?'class="error"':'class="error2"'?>>Nueva Contraseña</label>
            <input type="pass" class="form-control" name="contra1" id="contra1">
          </div>
          <div class="form-group">
            <label for="contra2" <?=$clase2=($class)?'class="error"':'class="error2"'?>>Repite la Contraseña</label>
            <input type="pass" class="form-control" name="pass" id="contra2" class="contra2">
          </div>
            <input type="submit" class="btn btn-primary" name="enviar" value="Aceptar">
          
        </form>
            <a href="index.php"> <input type="submit" class="btn btn-primary" name="enviar" value="Volver al Inicio"></a>

    </div>
  </div>
</div>

<?php include "templates/footer.php";