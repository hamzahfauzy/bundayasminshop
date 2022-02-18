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
                        <button class="btn btn-default btn-round" onclick="printInvoice()">Cetak Struk</button>
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
                                <div class="d-lg-flex d-sm-block">
                                    <input type="number" class="form-control" name="amount" placeholder="Nominal Pembayaran" required>
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

<script>
    var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'IDR',

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });

    function printInvoice(){
        if(typeof(Android) === "undefined") 
        {
            var customerId = <?=$customer->id?>;
            window.open("index.php?r=print/invoice&customer_id="+customerId+"&print=true" , '_blank');
        }
        else
        {
            var transaction = <?=json_encode($transaction)?>;

            var transactionItems = "[C]--------------------------------\n";
            transaction.items.forEach(item=>{
                transactionItems += `[L]${item.product.name}\n`
                transactionItems += `[L]${item.qty} x ${formatter.format(item.subtotal/item.qty)} [R]${formatter.format(item.subtotal)}\n`
            })
            transactionItems += "[C]--------------------------------\n";

            var transactionPayments = "[C]--------------------------------\n";

            var allPayment = 0;
            
            transaction.payments.forEach(payment=>{

                allPayment += parseInt(payment.subtotal);

                transactionPayments += `[L]${payment.created_at} [R]${formatter.format(payment.subtotal)}\n`
            })

            transactionPayments += "[C]--------------------------------\n";

            var printText = "[C]<b><?=app('name')?></b>\n" +
                            "[C]<?=app('address')?>\n" +
                            "[C]<?=app('phone')?>\n" +
                            "[C]--------------------------------\n" +
                            "[C]<?=date('d/m/Y H:i')?>\n" +
                            transactionItems +
                            `[L]<b>Total</b> [R]${formatter.format(transaction.total)}\n` +
                            "[C]--------------------------------\n" +
                            "[C]Pembayaran\n" +
                            transactionPayments +
                            `[L]<b>Total</b> [R]${formatter.format(allPayment)}\n` +
                            "[C]--------------------------------\n" +
                            `[L]<b>Sisa</b> [R]${formatter.format(transaction.total-allPayment)}\n` +
                            "[C]--------------------------------\n\n" +
                            "[C]** <?=$transaction->inv_code.' / '.substr($customer->name,0,10)?> **"
                            ;
            Android.printInvoice(printText);
        }
    }

</script>