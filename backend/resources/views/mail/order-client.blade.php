<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Verdana, sans-serif; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { text-align: center; margin-bottom: 20px; }
    .header img { width: 120px; }
    h2 { text-align: center; color: #000; }
    .numero { color: #4caf50; font-weight: bold; }
    hr { border: none; border-top: 1px solid #eee; margin: 20px 0; }
    ul { padding-left: 20px; }
    li { margin-bottom: 6px; }
    .total { font-size: 16px; font-weight: bold; }
    .footer { font-size: 13px; color: #666; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{ asset('images/logoDyd.jpg') }}" alt="DYD Accesorios">
    </div>

    <h2>¡Gracias por elegirnos, {{ $data['nombre'] }}!</h2>
    <p>Tu pedido fue registrado exitosamente.</p>
    <p><b>Pedido Nº:</b> <span class="numero">{{ $numeroPedido }}</span></p>
    <p>En breve nos pondremos en contacto para coordinar la entrega.</p>

    <hr>
    <h4>Resumen del pedido:</h4>
    <ul>
      @foreach ($data['carrito'] as $item)
        <li>{{ $item['nombre'] }} — Cant: {{ $item['cantidad'] }} — ${{ number_format($item['precioARS'] ?? $item['precio'], 0, ',', '.') }}</li>
      @endforeach
    </ul>
    <p class="total">Total: ${{ number_format($data['total'], 0, ',', '.') }}</p>

    <hr>
    <p class="footer">
      Por cualquier consulta comunicate con nosotros:<br>
      WhatsApp: 1122334455 | Email: envios_dydaccesorios@hotmail.com
    </p>
  </div>
</body>
</html>
