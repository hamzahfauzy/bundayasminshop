<?php

$conn = conn();
$db   = new Database($conn);

$customer = $db->single('customers',[
    'id' => $_GET['customer_id']
]);

if(request() == 'POST')
{
    $product = $db->insert('products',[
        'name' => $_POST['name'],
        'code' => strtotime('now'),
        'stok' => 1,
        'purchase' => $_POST['price'],
        'sale' => $_POST['price'],
    ]);

    // get transaction
    $transaction = $db->single('transactions',[
        'customer_id' => $customer->id
    ]);

    if(empty($transaction))
    {
        $insert_data = [
            'customer_id' => $customer->id,
            'total'    => $_POST['price'],
            'status'   => 'ambil',
            'inv_code' => 'INV-'.strtotime('now'),
        ];
    
        $transaction = $db->insert('transactions',$insert_data);
    }
    else
    {
        $db->update('transactions',[
            'total' => $transaction->total + $_POST['price']
        ]);
    }

    $db->insert('transaction_items',[
        'transaction_id' => $transaction->id,
        'product_id' => $product->id,
        'qty' => 1,
        'status' => 'ambil',
        'subtotal' => $_POST['price'],
    ]);

    set_flash_msg(['success'=>'Transaksi berhasil ditambahkan']);
    header('location:index.php?r=customers/transaction-view&id='.$customer->id);

}

return compact('customer');