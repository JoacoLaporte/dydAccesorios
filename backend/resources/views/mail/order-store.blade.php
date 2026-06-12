<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Verdana, sans-serif; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { text-align: center; margin-bottom: 20px; }
    .header img { width: 120px; }
    hr { border: none; border-top: 1px solid #eee; margin: 20px 0; }
    ul { padding-left: 20px; }
    li { margin-bottom: 6px; }
    .total { font-size: 16px; font-weight: bold; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{ asset('images/logoDyd.jpg') }}" alt="DYD Accesorios">
    </div>

    <h3>Nuevo pedido recibido — Nº {{ $numeroPedido }}</h3>
    <hr>

    <h4>Datos del cliente</h4>
    <p><b>Nombre:</b> {{ $data['nombre'] }} {{ $data['apellido'] }}</p>
    <p><b>Celular:</b> {{ $data['celular'] }}</p>
    <p><b>Email:</b> {{ $data['email'] }}</p>
    <p><b>Método de entrega:</b> {{ $data['metodoEntrega'] }}</p>

    @if ($data['metodoEntrega'] === 'Retiro sucursal')
      <p><b>Sucursal:</b> {{ $data['sucursal'] }}</p>
    @elseif ($data['metodoEntrega'] === 'Envio')
      <p><b>Dirección:</b> {{ $data['calle'] }}</p>
      <p><b>Código postal:</b> {{ $data['codigoPostal'] }}</p>
      <p><b>Localidad:</b> {{ $data['localidad'] }}</p>
      <p><b>Provincia:</b> {{ $data['provincia'] }}</p>
    @endif

    <hr>
    <h4>Detalle del pedido</h4>
    <ul>
      @foreach ($data['carrito'] as $item)
        <li>{{ $item['nombre'] }} — Cant: {{ $item['cantidad'] }} — ${{ number_format($item['precioARS'] ?? $item['precio'], 0, ',', '.') }}</li>
      @endforeach
    </ul>
    <p class="total">Total: ${{ number_format($data['total'], 0, ',', '.') }}</p>
  </div>
</body>
</html>
