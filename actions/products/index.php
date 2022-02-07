<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$datas = $db->all('products',[],[
    'id' => 'DESC'
]);

return compact('datas','success_msg');