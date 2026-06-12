<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderClientMail;
use App\Mail\OrderStoreMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'        => 'required|string|max:100',
            'apellido'      => 'required|string|max:100',
            'email'         => 'required|email',
            'celular'       => 'required|string|max:20',
            'metodoEntrega' => 'required|string',
            'sucursal'      => 'nullable|string',
            'calle'         => 'nullable|string',
            'codigoPostal'  => 'nullable|string',
            'localidad'     => 'nullable|string',
            'provincia'     => 'nullable|string',
            'carrito'       => 'required|array|min:1',
            'total'         => 'required|numeric',
        ]);

        $numeroPedido = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Mail::to($data['email'])->send(new OrderClientMail($data, $numeroPedido));
        Mail::to(config('mail.store_email'))->send(new OrderStoreMail($data, $numeroPedido));

        return response()->json(['status' => 'ok', 'numeroPedido' => $numeroPedido]);
    }
}
