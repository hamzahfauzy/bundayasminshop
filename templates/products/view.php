<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Produk : <?=$data->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data produk</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=products/edit&id=<?=$data->id?>" class="btn btn-secondary btn-round">Edit</a>
                        <a href="index.php?r=products/index" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Kode</td>
                                    <td width="10px">:</td>
                                    <td><?=$data->code?></td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td><?=$data->name?></td>
                                </tr>
                                <tr>
                                    <td>Harga Jual</td>
                                    <td>:</td>
                                    <td><?=number_format($data->sale)?></td>
                                </tr>
                                <tr>
                                    <td>Harga Beli</td>
                                    <td>:</td>
                                    <td><?=number_format($data->purchase)?></td>
                                </tr>
                                <tr>
                                    <td>Gambar</td>
                                    <td>:</td>
                                    <td><img src="<?=$data->pic?>" alt="" width="300px" style="object-fit:cover"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>