<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Cargar las variables de entorno
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$hostname = "localhost";
$username = "root";
$password = "";
$basedatosname = "formulario";
$tablename = "formulario_contacto";

// Crear conexión
$conexion = mysqli_connect($hostname, $username, $password, $basedatosname);


if (!$conexion) {
    die("Error al conectarse a la base de datos: " .mysqli_connect_error());
}else {
echo "Conexión exitosa a la base de datos";
}
// Recibir datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$ubicacion = $_POST['ubicacion'];
$mensaje = $_POST['mensaje'];
$archivo = $_FILES['archivo']['name'];
$ruta_archivo = "uploads/" . basename($archivo);


if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)) {
    echo "El archivo se ha subido correctamente.";
} else {
    echo "Error al subir el archivo.";
}

$sql = "INSERT INTO formulario_contacto (nombre, email, direccion, ubicacion, mensaje, archivo)
VALUES ('$nombre', '$email', '$direccion', '$ubicacion', '$mensaje', '$archivo')";

if ($conexion->query($sql) === TRUE) {
    echo "Datos guardados correctamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conexion->errno;
}

 // Enviar correo electrónico con PHPMailer
 $mail = new PHPMailer(true);

 try {
    $mail->isSMTP();
    $mail->Host = $_ENV['smtp.gmail.com'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['elvergt1102@gmail.com'];
    $mail->Password = $_ENV['JSk891211#'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = $_ENV['587'];

     // Configuración del correo
     $mail->setFrom($_ENV['elvergt1102@gmail.com'], '3CosmosEvents');
     $mail->addAddress($email);

     // Adjuntar archivo
     $mail->addAttachment($ruta_archivo);

     $mail->isHTML(true);
     $mail->Subject = 'Confirmación de formulario de contacto';
     $mail->Body = "Hola $nombre,<br><br>Gracias por contactar con nosotros. Hemos recibido el mensaje:<br><br>
     $mensaje<br><br>Saludos,<br>Equipo de CosmosEvents";

     $mail->send();
     echo "El mensaje de correo electrónico se ha enviado correctamente.";
 } catch (Exception $e) {
     echo "Error al enviar el mensaje de correo electrónico: {$mail->ErrorInfo}";
 }
} else {
 echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>
