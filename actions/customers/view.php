<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('customers',[
    'id' => $_GET['id']
]);

$query = "SELECT transactions.*, SUM(transaction_payments.subtotal) as total_payment FROM transactions JOIN transaction_payments ON transaction_payments.transaction_id = transactions.id WHERE transactions.customer_id = $_GET[id] GROUP BY transactions.id";
$db->query = $query;
$transactions = $db->exec('all');

return compact('data','transactions');