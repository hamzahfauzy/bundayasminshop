<?php

$conn = conn();
$db   = new Database($conn);

$customer = $db->single('customers',[
    'id' => $_GET['customer_id']
]);

$pos_sess_id = 'pos_sess_id_'.strtotime('now');

return compact('customer','pos_sess_id');