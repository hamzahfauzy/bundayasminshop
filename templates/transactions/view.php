<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Penjualan : <?=$transaction->inv_code?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data penjualan</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=print/invoice&inv_code=<?=$transaction->inv_code?>&print=true" target="_blank" class="btn btn-default btn-round">Cetak Invoice</a>
                        <a href="index.php?r=transactions/index" class="btn btn-warning btn-round">Kembali</a>
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
                                    <td>Invoice</td>
                                    <td width="10px">:</td>
                                    <td><?=$transaction->inv_code?></td>
                                </tr>
                                <tr>
                                    <td>Customer</td>
                                    <td>:</td>
                                    <td><?=$transaction->customer?$transaction->customer->name:'-'?></td>
                                </tr>
                                <tr>
                                    <td>Kasir</td>
                                    <td>:</td>
                                    <td><?=auth()->user->name?></td>
                                </tr>
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
                            </table>
                            
                            <div class="table-responsive table-hover table-sales">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($transaction->items as $index => $item): $total_payment = 0;?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <td><?=$item->product->name?></td>
                                            <td><?=$item->qty?></td>
                                            <td><?=number_format($item->subtotal / $item->qty)?></td>
                                            <td><?=number_format($item->subtotal)?></td>
                                            <?php /*
                                            <td>
                                                <?php if($item->status == 'retur'): ?>
                                                <i>Barang dikembalikan</i>
                                                <?php else: ?>
                                                <a href="index.php?r=transactions/retur&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Retur Barang</a>
                                                <?php endif ?>
                                            </td> */ ?>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <br><br>
                            <h2>Pembayaran</h2>
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <form action="" method="post">
                                <input type="text" class="form-control" name="amount" placeholder="Nominal Pembayaran" required>
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