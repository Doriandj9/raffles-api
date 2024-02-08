<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>HAYU24</title>
</head>
<body style="width: 75%; margin: auto;">
    <header>
        <h1 style="font-weight: bold;">Sistema de rifas en linea HAYU 24</h1>
    </header>
    <p>
        @yield('content')
    </p>
   <footer>

    <table style="width: 100%; border-collapse: collapse">
            <colgroup>
                <col />
                <col />
                <col />
                <col />
                <col />
            </colgroup> 
        <tbody>
            <tr>
                <td colspan="3" style="background-color: #ede9fe;">
                    <img width="125px" height="125px" src="{{ asset('img/security.png') }}" alt="secure">
                </td>
                <td colspan="2" style="background-color: #ede9fe; padding: 5px;">
                    <article class="position: relative;">
                        <h2 style="font-size: 15px; text-align: center; font-weight: bold;">Tu seguridad es importante</h2>
                        <p style="font-size: 12px;">
                           Recuerde que HAYU24 no solicita información sensible vía SMS, correo electrónico, formularios, redes sociales, datos confidenciales como contraseñas,
                           códigos de seguridad, números telefónicos, entre otros.
                           <br>
                           Si no fue usted, por favor comuníquese con nosotros inmediatamente al correo: <a style="font-weight: bold;" href="mailto:soporte@hayu24.ec">soporte@hayu24.ec</a> <br>
                           Si recibes este correo es porque te suscribiste y/o registraste a <span style="font-weight: bold;">HAYU24.</span>
                           <br>
                           Puedes <a target="__blank" style="font-weight: bold;" href="{{env('APP_URL_FRONT') . '/not-recive/emails'}}">dejar de recibir correos promocionales</a>
                           <br>
                           Este correo ha sido enviado de forma automática y no requiere respuesta
                        </p>
                   </article>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: end; background-color: #ede9fe; padding-right: 25px;">
                                <span style="font-size: 11px;"> Siguenos en nuestras redes sociales </span> <br>
                                <a target="__blank" href="https://www.facebook.com/digitaleducas.oficial" className="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                                    <img style="display: inline;" src="{{ asset('img/facebook.png') }}" alt="facebbok">
                                </a>
                                <a target="__blank" href="https://www.instagram.com/digitaleducas/" className="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                                    <img style="display: inline;" src="{{ asset('img/instagram.png') }}" alt="instagram">
                                </a>
                                <a target="__blank" href="https://twitter.com/digital_educas" className="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                                    <img style="display: inline;" src="{{ asset('img/twitter.png') }}" alt="twitter">
                                </a>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="height: 25px; background-color: #ede9fe;"></td>
            </tr>
        </tbody>
    </table>
    <div style="height: 25px; background-color: #ffff;"></div>
    <table>
        <tr>
            <td style="background-color:  #ede9fe; font-size: 12px; border-width: 1px; border-style: dashed; border-color:#003049; padding: 10px;">
                La información enviada desde este correo electrónico cumple con todas las normas legales establecidas en LA LEY DE COMERCIO ELECTRÓNICO, FIRMAS ELECTRÓNICAS,
                MENSAJES DE DATOS y  su Reglamento   General,  vigente  en  el Ecuador. <br> <br>
                Nota de descargo: La información contenida en este correo es confidencial y solo puede ser utilizada por el individuo o la compañía a las cuales está dirigido. Esta
                información no debe ser distribuida ni copiada total o parcialmente por ningún medio sin la autorización de HAYU24. La organización no asume responsabilidad sobre
                información opiniones, criterios contenidos en este correo que no esté relacionada con las actividades de HAYU24.
            </td>
        </tr>
    </table>
   </footer>
</body>
</html>