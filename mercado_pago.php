<?php 

require 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken('TEST-8221914580112236-042519-c2e2c4462e8a9d4bb2171aa8533e1bb7-292116879');

$preference = new MercadoPago\Preference();

$item = new MercadoPago\Item();
$item->id = '0001';
$item->title = 'producto CDP';
$item->quantity = 1;
$item->unit_price = 100;
$item->currency_id = 'ARS';

$preference->items = array($item);

$preference->back_urls = array(
    "success" => "http://localhost/tienda_online/captura.php",
    "failure" => "http://localhost/tienda_online/fallo.php",
);

$preference->auto_return = "approved";
$preference->binary_mode = true;

$preference->save();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>

    <div class="checkout-btn"></div>

    <script>
        const mp = new MercadoPago('TEST-d12b8954-eb42-49a5-a133-2c6f2b73c5b6', {
            locale: 'es-AR'
        });

        mp.checkout({
            preference: {
                id: '<?php echo $preference->id; ?>'
            },
            render : {
                container: '.checkout-btn',
                label: 'Pagar con Mercado Pago'
            }
        });

    </script>

</body>
</html>