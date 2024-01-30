<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    public const DATA_USER_INCORRECT = "
    Este correo electrónico ya está asociado a otra cuenta. Los datos que acaba de ingresar 
    no concuerdan con la información existente dentro de la plataforma. 
    Si eres este usuario, dirígete al panel de configuración de perfil y actualiza los datos. 
    Lamentamos cualquier inconveniente que esto pueda causarte. 
    ";

    public const NOT_VOUCHER_PRESENT = "
    No se puede generar el pedido de compra si no ingresa la imagen del comprobante de pago.
    ";

    public const NEW_USER_PAYMENT_TICKET = "
    ¡Felicidades! Ya eres parte de nuestra plataforma. Ahora dispones de un usuario para ingresar al panel del cliente. 
    Dirígete a tu bandeja de entrada de correos electrónicos, confirma el correo electrónico y crea una nueva contraseña.
    
    <br />

    Tu transacción de compra se ha completado correctamente. El comprobante de pago se ha enviado al organizador de la rifa. 
    Una vez que él apruebe la veracidad del comprobante, tendrás acceso a tus boletos ganadores.

    ";

    public const USER_PAYMENT_TICKET = "
    Tu transacción de compra se ha completado correctamente. El comprobante de pago se ha enviado al organizador de la rifa. 
    Una vez que él apruebe la veracidad del comprobante, tendrás acceso a tus boletos ganadores.
    ";

    public const USER_OWNER_RAFFLE = "
    No puedes comprar boletos de rifas que tú organizaste. 
    
    Va en contra de las normas de la plataforma.
    ";
}
