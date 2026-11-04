<?php
require_once __DIR__.'/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$back = 'index.php#contacto';

if (!csrf_ok($_POST['csrf'] ?? '') || !empty($_POST['web'])) {
    flash('err', 'No se pudo enviar el formulario. Intentá de nuevo.'); header("Location: $back"); exit;
}

$nombre   = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$servicio = trim($_POST['servicio'] ?? '');
$mensaje  = trim($_POST['mensaje'] ?? '');
$validos  = ['Cuidado de adulto mayor','Postoperatorio','Curaciones / inyectables','Control de signos vitales','Otro'];

if (mb_strlen($nombre) < 2 || !preg_match('/^[0-9 +()\-]{6,40}$/', $telefono) || !in_array($servicio, $validos, true)) {
    flash('err', 'Revisá tu nombre y teléfono, por favor.'); header("Location: $back"); exit;
}

// Sin base de datos: se abre WhatsApp con el mensaje listo para enviar
$texto = "Hola Noelia, soy $nombre. Necesito: $servicio.\n" . mb_substr($mensaje, 0, 1000) . "\nMi teléfono: $telefono";
header('Location: https://wa.me/' . WHATSAPP . '?text=' . rawurlencode($texto));
exit;
