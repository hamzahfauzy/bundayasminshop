<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST')
{
    $pic  = $_FILES['products'];
    $ext  = pathinfo($pic['name']['pic'], PATHINFO_EXTENSION);
    $name = strtotime('now').'.'.$ext;
    $file = 'uploads/products/'.$name;
    copy($pic['tmp_name']['pic'],$file);
    $_POST['products']['pic'] = $file;

    $product = $db->insert('products',$_POST['products']);

    set_flash_msg(['success'=>'Produk berhasil ditambahkan']);
    header('location:index.php?r=products/view&id='.$product->id);
}

return;