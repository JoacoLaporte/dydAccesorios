<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: text/html; charset=UTF-8");

// Captura de datos JSON enviados por Vue
$input = json_decode(file_get_contents('php://input'), true);
$nombre = htmlspecialchars($input['nombre'] ?? '');
$apellido = htmlspecialchars($input['apellido'] ?? '');
$celular = htmlspecialchars($input['celular'] ?? '');
$email = htmlspecialchars($input['email'] ?? '');
$metodoEntrega = htmlspecialchars($input['metodoEntrega'] ?? '');
$sucursal = htmlspecialchars($input['sucursal'] ?? '');
$calle = htmlspecialchars($input['calle'] ?? '');
$codigoPostal = htmlspecialchars($input['codigoPostal'] ?? '');
$localidad = htmlspecialchars($input['localidad'] ?? '');
$provincia = htmlspecialchars($input['provincia'] ?? '');
$carrito = $input['carrito'] ?? [];
$total = htmlspecialchars($input['total'] ?? 0);
$numeroPedido = str_pad(mt_rand(0, 999999), 6, '0');

// Armar cuerpo del correo
$detalle = '';
foreach ($carrito as $item) {
    $detalle .= "<li>{$item['nombre']} - Cant: {$item['cantidad']} - Precio: {$item['precio']}</li>";
}

// cuerpo con logo
// $body = '
// <div style="text-align:center;">
//   <img src="cid:logo_dyd" alt="DYD Accesorios" style="width:120px;margin-bottom:10px;">
// </div>
// <div style="display: flex; justify-content: center;">
//     <h2>¡Tu pedido fue realizado exitosamente!</h2>
// </div>
// <hr>

// <p><b>Hola, ' . $nombre . '.</b></p>
// <p> Falta que poner aca.</p>

// <fieldest>
// <h4>Datos cliente:</h4>
// <p>- Cliente: ' . $nombre . ' ' . $apellido . '</p>
// <p>- Celular: ' . $celular . '</p>
// <p>- E-mail: ' . $email . '</p>
// <h4>Tipo de entrega: ' . $metodoEntrega . '</h4>
// </fieldest>
// ';

// if ($metodoEntrega === 'Retiro sucursal') {
//     $body .= '<p>- Sucursal: ' . $sucursal . '</p>';
// } elseif ($metodoEntrega === 'Envio') {
//     $body .= '
//     <p>- Dirección: ' . $calle . '</p>
//     <p>- Código postal: ' . $codigoPostal . '</p>
//     <p>- Localidad: ' . $localidad . '</p>
//     <p>- Provincia: ' . $provincia . '</p>
//     ';
// }


// $body .= '
// <fieldest>
// <h4>RESUMEN DEL PEDIDO</4>
// <p>Pedido Nº: <b>' . $numeroPedido . '</b></p>

// <ul>' . $detalle . '</ul>
// <p><b>Total:</b> $' . $total . '</p>
// ';


// === Cuerpo del correo para el cliente ===
$bodyCliente = '
<div style="text-align:center;">
    <img src="cid:logo_dyd" alt="DYD Accesorios" style="width:120px;margin-bottom:10px;">
</div>

<div style="font-family: verdana">
    <h2 style="text-align:center;">¡Gracias por elegirnos, ' . $nombre . '!</h2>
    <p>Tu pedido fue registrado exitosamente.</p>
    <p><b>Pedido Nº:</b> <spam style="color:green">' . $numeroPedido . '</spam></p>
    <p>En breve nos pondremos en contacto para coordinar la entrega.</p>
    <hr>
    <h4>Resumen pedido:</h4>
    <ul>' . $detalle . '</ul>
    <p><b>Total:</b> $' . $total . '</p>

    <hr>
    <p>Por cualquier consulta comunicate con nosotros: 
        whatsApp:1122334455 o via mail: envios_dydaccesorios@hotmail.com</p>
</div>
';

// Cuerpo del correo interno 
$bodyInterno = '
<div style="text-align:center;">
  <img src="cid:logo_dyd" alt="DYD Accesorios" style="width:120px;margin-bottom:10px;">
</div>
<h3>Nuevo pedido recibido</h3>
<p><b>Nº Pedido:</b> ' . $numeroPedido . '</p>
<hr>
<h4>Datos del cliente</h4>
<p>Nombre: ' . $nombre . ' ' . $apellido . '</p>
<p>Celular: ' . $celular . '</p>
<p>Email: ' . $email . '</p>
<p>Método de entrega: ' . $metodoEntrega . '</p>
';

if ($metodoEntrega === 'Retiro sucursal') {
  $bodyInterno .= '<p>Sucursal: ' . $sucursal . '</p>';
} elseif ($metodoEntrega === 'Envio') {
  $bodyInterno .= '
  <p>Dirección: ' . $calle . '</p>
  <p>Código postal: ' . $codigoPostal . '</p>
  <p>Localidad: ' . $localidad . '</p>
  <p>Provincia: ' . $provincia . '</p>';
}

$bodyInterno .= '
<hr>
<h4>Detalle del pedido</h4>
<ul>' . $detalle . '</ul>
<p><b>Total:</b> $' . $total . '</p>
';

// Envío con Mailtrap

// === Configuración de PHPMailer base ===
function configMailer() {
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->isSMTP();
    $mail->Host       = 'smtp.ethereal.email';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'janelle.prohaska@ethereal.email  ';
    $mail->Password   = '5cfExqPTZPtdsk1eYG';
    $mail->Port       = 587;
    $mail->SMTPSecure = 'tls';
    $mail->setFrom('envios_dydaccesorios@hotmail.com', 'DYD Accesorios');
    $mail->AddEmbeddedImage(__DIR__ . '/logoDyd.jpg', 'logo_dyd', 'logoDyd.jpg');
    $mail->isHTML(true);
    return $mail;
  }

try{
    // Correo para cliente
    $mailCliente = configMailer();
    $mailCliente->addAddress($email, "$nombre $apellido");
    $mailCliente->Subject = "Confirmación de pedido Nº $numeroPedido";
    $mailCliente->Body = $bodyCliente;
    $mailCliente->send();

    // Correo interno
    $mailInterno = configMailer();
    $mailInterno->addAddress('envios_dydaccesorios@hotmail.com');
    $mailInterno->Subject = "Nuevo pedido recibido Nº $numeroPedido";
    $mailInterno->Body = $bodyInterno;
    $mailInterno->send();


    echo json_encode(['status' => 'ok']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}


// try {
//     $mail->CharSet = 'UTF-8';
//     $mail->Encoding = 'base64';
//     $mail->isSMTP();
//     $mail->Host       = 'sandbox.smtp.mailtrap.io';
//     $mail->SMTPAuth   = true;
//     $mail->Username   = '06e44bcc433ccb';
//     $mail->Password   = '0d2a922cc10d85';
//     $mail->Port       = 2525;
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

//     $mail->setFrom('envios_dydaccesorios@hotmail.com', 'DYD Accesorios');
//     $mail->addAddress($email, "$nombre $apellido");   // cliente
//     $mail->addAddress('envios_dydaccesorios@hotmail.com'); // copia para vos

//     // === logo embebido ===
//     $mail->AddEmbeddedImage(__DIR__ . '/logoDyd.jpg', 'logo_dyd', 'logoDyd.jpg');

//     $mail->isHTML(true);
//     $mail->Subject = "¡Gracias por tu compra en DydAccesorios!";
//     $mail->Body    = $body;

//     $mail->send();
//     echo json_encode(['status' => 'ok']);
// } catch (Exception $e) {
//     echo json_encode(['status' => 'error', 'msg' => $mail->ErrorInfo]);
// }
