<div style="font-size:14px;width:42mm;padding:0;margin:0;">
    <br>
    <div style="text-align:center">
        <b><?=app('name')?></b><br>
        <?=app('address').'<br>'.app('phone')?>
    </div>
    <br>

    <table border="0" width="100%" style="width:100%;font-size:14px;">
        <tr>
            <td colspan="3" style="text-align:center;border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;">
            <?=date('d/m/Y H:i')?>
            </td>
        </tr>
        <?php foreach($transaction->items as $item): ?>
        <tr>
            <td colspan="3"><?=$item->product->name?></td>
        </tr>
        <tr>
            <td colspan="2"><?=$item->qty?> &times; <?=number_format($item->subtotal/$item->qty)?></td>
            <td style="text-align:right"><?=number_format($item->subtotal)?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="3" style="border-top:1px dashed #000;">
            </td>
        </tr>
        <tr>
            <td style="padding:5px 0px;"><b>Total</b></td>
            <td colspan="2" style="padding:5px 0px;text-align:right;"><?=number_format($transaction->total)?></td>
        </tr>
        <tr>
            <td colspan="3" style="border-top:1px dashed #000;">
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center">
                Pembayaran
            </td>
        </tr>
        <?php $all_payment = 0; foreach($transaction->payments as $payment): $all_payment += $payment->subtotal;?>
        <tr>
            <td><?=date("d/m/Y",strtotime($payment->created_at))?></td>
            <td colspan="2" style="text-align:right;"><?=number_format($payment->subtotal)?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td style="padding:5px 0px;border-top:1px dashed #000;"><b>Total</b></td>
            <td colspan="2" style="padding:5px 0px;text-align:right;border-top:1px dashed #000;"><?=number_format($all_payment)?></td>
        </tr>
        <tr>
            <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;"><b>Sisa</b></td>
            <td colspan="2" style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;text-align:right;"><?=number_format($transaction->total-$all_payment)?></td>
        </tr>
        <tr>
            <td colspan="3">
                <br><br>
                <div style="text-align:center">
                    <i>** <?=$transaction->inv_code.' / '.substr(auth()->user->name,0,10)?> **</i>
                </div>
            </td>
        </tr>
    </table>
</div>

<script>
    window.onload = function() { window.print(); }
</script>