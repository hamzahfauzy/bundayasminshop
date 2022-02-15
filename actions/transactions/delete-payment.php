<?php

$conn = conn();
$db   = new Database($conn);

$item = $db->single('transaction_payments',[
    'id' => $_GET['id']
]);

$transaction = $db->single('transactions',[
    'id' => $item->transaction_id
]);

$db->delete('transaction_payments',[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Pembayaran berhasil dihapus']);
header('location:index.php?r=customers/transaction-view&id='.$transaction->customer_id);