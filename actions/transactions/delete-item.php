<?php

$conn = conn();
$db   = new Database($conn);

$item = $db->single('transaction_items',[
    'id' => $_GET['id']
]);

$transaction = $db->single('transactions',[
    'id' => $item->transaction_id
]);

$db->update('transactions',[
    'total' => $transaction->total - $item->subtotal,
    'return_total' => $transaction->total + $transaction->return_total
],[
    'id' => $transaction->id
]);

$db->delete('transaction_items',[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Transaksi berhasil dihapus']);
header('location:index.php?r=customers/transaction-view&id='.$transaction->customer_id);