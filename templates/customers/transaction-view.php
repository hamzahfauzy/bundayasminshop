<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Transaksi : <?=$customer->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data transaksi kustomer</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=print/invoice&customer_id=<?=$customer->id?>&print=true" target="_blank" class="btn btn-default btn-round">Cetak Struk</a>
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
                                    <td>Customer</td>
                                    <td>:</td>
                                    <td><?=$customer->name?></td>
                                </tr>
                                <tr>
                                    <td>Kasir</td>
                                    <td>:</td>
                                    <td><?=auth()->user->name?></td>
                                </tr>
                                <?php if($transaction): ?>
                                <tr>
                                    <td>Total</td>
                                    <td>:</td>
                                    <td><?=number_format($transaction->total)?></td>
                                </tr>
                                <tr>
                                    <td>Pembayaran</td>
                                    <td>:</td>
                                    <td>
                                        <?php $payment_total = 0; foreach($transaction->payments as $payment) $payment_total+=$payment->subtotal; ?>
                                        <?=number_format($payment_total)?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sisa</td>
                                    <td>:</td>
                                    <td><?=number_format($transaction->total-$payment_total)?></td>
                                </tr>
                                <?php endif ?>
                            </table>
                            
                            <div class="table-responsive table-hover table-sales">
                                <a href="index.php?r=transactions/create&customer_id=<?=$customer->id?>" class="btn btn-success btn-round">Tambah Pembelian</a>
                                <p></p>
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Sub Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($transaction) : foreach($transaction->items as $index => $item): $total_payment = 0;?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <td><?=$item->product->name?></td>
                                            <td><?=$item->qty?></td>
                                            <td><?=number_format($item->subtotal / $item->qty)?></td>
                                            <td><?=number_format($item->subtotal)?></td>
                                            <td><a href="index.php?r=transactions/delete-item&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a></td>
                                        </tr>
                                        <?php endforeach; endif;  ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if($transaction): ?>
                            <br><br>
                            <h2>Pembayaran</h2>
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <form action="" method="post">
                                <div class="d-flex">
                                    <input type="text" class="form-control" name="amount" placeholder="Nominal Pembayaran" required>
                                    &nbsp;
                                    <input type="date" class="form-control" name="date" placeholder="Tanggal Pembayaran" required>
                                </div>
                                <button class="btn btn-primary btn-block">Buat Pembayaran</button>
                            </form>
                            <p></p>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($transaction->payments as $index => $payment): ?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <td><?=$payment->created_at?></td>
                                            <td><?=number_format($payment->subtotal)?></td>
                                            <td><a href="index.php?r=transactions/delete-payment&id=<?=$payment->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>