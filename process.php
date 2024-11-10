<?php

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

// Recibir datos del formulario y limpiar entradas
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$email = mysqli_real_escape_string($conexion, $_POST['email']);
$direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
$ubicacion = mysqli_real_escape_string($conexion, $_POST['ubicacion']);
$mensaje = mysqli_real_escape_string($conexion, $_POST['mensaje']);
$archivo = $_FILES['archivo']['name'];
$ruta_archivo = "uploads/" . basename($archivo);

// Verificar si la carpeta 'uploads' existe, si no, crearla
if (!is_dir("uploads")) {
    mkdir("uploads", 0777, true);
}

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

 

$conexion->close();
?>
