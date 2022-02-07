<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Kustomer : <?=$data->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Kustomer</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=customers/edit&id=<?=$data->id?>" class="btn btn-secondary btn-round">Edit</a>
                        <a href="index.php?r=customers/index" class="btn btn-warning btn-round">Kembali</a>
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
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td><?=$data->name?></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td><?=$data->address?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h2>Transaksi</h2>
                            <a href="index.php?r=transactions/create&customer_id=<?=$data->id?>" class="btn btn-success">Transaksi Baru</a>
                            <p></p>
                            <div class="table-hover table-sales">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transaksi</th>
                                            <th>Total</th>
                                            <th>Pembayaran</th>
                                            <th>Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($transactions as $index => $transaction): ?>
                                        <tr>
                                            <td><?=$index+1?></td>
                                            <td>
                                                INV : <a href="index.php?r=transactions/view&id=<?=$transaction->id?>"><?=$transaction->inv_code?></a><br>
                                                <i><?=$transaction->created_at?></i>
                                            </td>
                                            <td><?=number_format($transaction->total)?></td>
                                            <td><?=number_format($transaction->total_payment)?></td>
                                            <td><?=number_format($transaction->total-$transaction->total_payment)?></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>