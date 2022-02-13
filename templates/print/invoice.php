<div style="font-size:12px;width:58mm">
    <br>
    <div style="text-align:center">
        <b><?=app('name')?></b><br>
        <?=app('address').'<br>'.app('phone')?>
    </div>
    <br>

    <table border="0" width="100%" style="width:100%;font-size:12px;">
        <tr>
            <td colspan="2" width="85" style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;">
            <?=date('d-m-Y H:i:s')?>
            </td>
            <td colspan="2" width="85" style="border-top:1px dashed #000;border-bottom:1px dashed #000;text-align:right;padding:5px 0px;">
            <?=$transaction->inv_code.' / '.substr(auth()->user->name,0,10)?>
            </td>
        </tr>
        <?php foreach($transaction->items as $item): ?>
        <tr>
            <td><?=$item->product->name?></td>
            <td></td>
            <td style="text-align:right;"><?=$item->qty?> x <?=number_format($item->subtotal/$item->qty)?></td>
            <td style="text-align:right;"><?=number_format($item->subtotal)?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="4" style="border-top:1px dashed #000;">
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="padding:5px 0px;"><b>Total</b></td>
            <td style="padding:5px 0px;text-align:right;"><?=number_format($transaction->total)?></td>
        </tr>
        <tr>
            <td colspan="4" style="border-top:1px dashed #000;">
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center">
                Pembayaran
            </td>
        </tr>
        <?php $all_payment = 0; foreach($transaction->payments as $payment): $all_payment += $payment->subtotal;?>
        <tr>
            <td colspan="2"><?=$payment->created_at?></td>
            <td colspan="2" style="text-align:right;"><?=number_format($payment->subtotal)?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td></td>
            <td></td>
            <td style="padding:5px 0px;"><b>Total</b></td>
            <td style="padding:5px 0px;text-align:right;"><?=number_format($all_payment)?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;"><b>Sisa</b></td>
            <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;text-align:right;"><?=number_format($transaction->total-$all_payment)?></td>
        </tr>
        <tr>
            <td colspan="4">
                <br><br>
                <div style="text-align:center">
                    <i>** Terimakasih telah berbelanja di <?=app('name')?> **</i>
                </div>
            </td>
        </tr>
    </table>
</div>

<script>
    window.onload = function() { window.print(); }
</script>