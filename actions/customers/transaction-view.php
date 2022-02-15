<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$transaction = $db->single('transactions',[
    'customer_id' => $_GET['id']
]);

if(isset($_POST['amount']) && !empty($_POST['amount']))
{
    $all_payments = $db->all('transaction_payments',[
        'transaction_id' => $transaction->id
    ]);

    $db->insert('transaction_payments',[
        'transaction_id' => $transaction->id,
        'subtotal' => $_POST['amount'],
        'description' => 'Pembayaran ke '.(count($all_payments)+1)
    ]);

    set_flash_msg(['success'=>'Pembayaran Berhasil']);
    header('location:index.php?r=customers/transaction-view&id='.$_GET['id']);
    die();
}

if($transaction)
{
    $items = $db->all('transaction_items',[
        'transaction_id' => $transaction->id
    ]);
    
    foreach($items as $index => $item)
    {
        $item->product = $db->single('products',[
            'id' => $item->product_id
        ]);
    }
    
    $transaction->items = $items;
    
    $transaction->payments = $db->all('transaction_payments',[
        'transaction_id' => $transaction->id
    ]);
}

$customer = $db->single('customers',[
    'id' => $_GET['id']
]);

return compact('transaction','customer','success_msg');