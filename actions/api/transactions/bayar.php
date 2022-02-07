<?php

$conn = conn();
$db   = new Database($conn);

$pos_sess_id = $_POST['pos_sess_id'];
$carts = $_SESSION[$pos_sess_id];

$customer_id = $_POST['customer_id'];

$inv_code = str_replace('pos_sess_id_','',$pos_sess_id);
$status = $_POST['paytotal']-$carts['total'] < 0 ? 'credit' : 'cash'; 

$insert_data = [
    'customer_id' => $customer_id,
    'total'    => $carts['total'],
    'status'   => $status,
    'inv_code' => $inv_code,
];

// 'paytotal' => $_POST['paytotal'],
// 'return_total' => $_POST['paytotal']-$carts['total']

$transaction = $db->insert('transactions',$insert_data);

$db->insert('transaction_payments',[
    'transaction_id' => $transaction->id,
    'subtotal'       => $_POST['paytotal'],
    'description'    => $status == 'credit' ? 'Pembayaran Ke 1' : 'Pembayaran'
]);

foreach($carts['items'] as $product_id => $item)
{
    $db->insert('transaction_items',[
        'transaction_id' => $transaction->id,
        'product_id'     => $product_id,
        'qty'            => $item['qty'],
        'subtotal'       => $item['subtotal'],
        'status'         => 'pay',
    ]);
}
unset($_SESSION[$pos_sess_id]);
echo json_encode([
    'status' => 'success',
    'msg'    => 'payment success',
    'inv_code' => $inv_code
]);
die();