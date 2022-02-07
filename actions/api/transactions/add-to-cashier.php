<?php

/*
    start of transaction properties
    customer_id -> nullable
    user_id     -> cashier_id
    total
    paytotal
    return_total
    status
    inv_code

    start of transaction item properties
    product_id
    transaction_id
    price
    qty
    subtotal
    status
*/

$conn = conn();
$db   = new Database($conn);

$pos_sess_id = $_GET['pos_sess_id'];

if(!isset($_SESSION[$pos_sess_id]))
    $_SESSION[$pos_sess_id] = ['items'=>[],'total'=>0];

$clause = [];
if(isset($_GET['code']))
    $clause = ['code' => $_GET['code']];
elseif(isset($_GET['name']))
    $clause = ['name' => ['LIKE','%'.$_GET['name'].'%']];

$data = $db->single('products',$clause);

// check if item is already on cashier
if(isset($_SESSION[$pos_sess_id]['items'][$data->id]))
{
    $_SESSION[$pos_sess_id]['items'][$data->id]['qty'] += 1;
    $_SESSION[$pos_sess_id]['items'][$data->id]['subtotal'] = $_SESSION[$pos_sess_id]['items'][$data->id]['qty'] * $_SESSION[$pos_sess_id]['items'][$data->id]['price'];
    $_SESSION[$pos_sess_id]['items'][$data->id]['subtotal_format'] = number_format($_SESSION[$pos_sess_id]['items'][$data->id]['subtotal']);
}
// if item not already on cashier
else
{
    $_SESSION[$pos_sess_id]['items'][$data->id] = [
        'id'   => $data->id,
        'code' => $data->code,
        'name' => $data->name,
        'qty'  => 1,
        'price'  => $data->sale,
        'price_format'  => number_format($data->sale),
        'subtotal' => $data->sale,
        'subtotal_format' => number_format($data->sale)
    ];
}

$_SESSION[$pos_sess_id]['total'] = count_total($_SESSION[$pos_sess_id]['items']);
$_SESSION[$pos_sess_id]['total_format'] = number_format($_SESSION[$pos_sess_id]['total']);
$_SESSION[$pos_sess_id]['pic'] = $data->pic;

echo json_encode($_SESSION[$pos_sess_id]);
die();