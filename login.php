<?php 
session_start();
$config = include 'config.php';
$usuario=$_POST["usuario"];
$pass=$_POST["pass"];

$_SESSION["inicio"]=$_POST["usuario"];



$dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
$conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

$select = $conexion->prepare("SELECT * FROM registros WHERE usuario = :usuario;");
$row = $select->fetch($select->execute(array(':usuario' => $usuario)));



if(isset($_POST["enviar"])) {
    if($_POST["pass"] == $row["pass"] && $_POST["usuario"] == $row["usuario"]) {

        if($_POST["pass"] == "" || $_POST["usuario"] == "" ){
            $_SESSION["sesion"] = 0;
        }else{
        $_SESSION["sesion"] = 1;
        header('Location: index.php');
        exit;}
    } else {
        $_SESSION["sesion"] = 0;
    }
} else {
    $_SESSION["sesion"] = 0;
}
include 'funciones.php';
$error = false;
?>
<?php include "templates/header.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Saira+Condensed:wght@300;500&display=swap');
    </style>
</head>
<body style="background:black;">
    <br><br><br>
    <div align="center" style="border: 5px inset; width:450px; margin: auto; border-radius: 25px; font-family:'Saira Condensed', sans-serif; background: white;">
        <br>
        <h1 style="font-size:65;">CRUD</h1>
        <br/>
        <form action="login.php" method="post">
            <table>
                <tr>
                    <td style="padding: 10;"><label>Usuario </label></td>
                    <td><input type="text" name="usuario" id="usuario"></td>
                </tr>
                <tr>
                    <td style="padding: 10;"><label>Contraseña </label></td>
                    <td><input type="password" name="pass" id="pass"></td>
                </tr>
                <tr>
                    <td  style="padding: 15;" colspan="2" align="center" ><input class="btn btn-dark" name="enviar" type="submit" value="Enviar"></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
<?php include "templates/footer.php"; ?>