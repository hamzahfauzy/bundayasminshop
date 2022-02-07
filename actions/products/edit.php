<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('products',[
    'id' => $_GET['id']
]);

if(request() == 'POST')
{
    if(!empty($_FILES['products']['name']['pic']))
    {
        $pic  = $_FILES['products'];
        $ext  = pathinfo($pic['name']['pic'], PATHINFO_EXTENSION);
        $name = strtotime('now').'.'.$ext;
        $file = 'uploads/products/'.$name;
        copy($pic['tmp_name']['pic'],$file);
        $_POST['products']['pic'] = $file;
    }
    else
        $_POST['products']['pic'] = $data->pic;

    $db->update('products',$_POST['products'],[
        'id' => $_GET['id']
    ]);

    set_flash_msg(['success'=>'Produk berhasil diupdate']);
    header('location:index.php?r=products/view&id='.$_GET['id']);
}

return compact('data');