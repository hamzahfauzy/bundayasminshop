<?php load_templates('layouts/top') ?>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Scan Barcode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Penjualan : <?=$customer->name?></h2>
                        <h5 class="text-white op-7 mb-2">Pembuatan penjualan baru untuk <?=$customer->name?></h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="text" class="form-control" placeholder="Barcode Produk disini" onkeyup="addToCashier(event, this.value)" onchange="addToCashier(event, this.value)">
                            <p></p>
                            <button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#exampleModal">Scan Barcode</button>

                            <p></p>
                            <div class="table-responsive">
                                <table class="table table-hover" id="transactions-table">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>Rp. <span id="total">0</span></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label for="">Total Bayar</label>
                                    <input type="number" class="form-control mb-2" name="payment_total" placeholder="Nominal Bayar" onkeyup="hitungKembalian(this.value, event)" value="0">
                                    <input type="number" class="form-control mb-2" name="return_total" placeholder="Kembalian" value="0" readonly>
                                    <button id="btn-bayar" class="btn btn-primary btn-block btn-sm" onclick="bayar()">BAYAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    window.transactions = []
    var codeTimeout = null;
    var calculateTimeout = null;

    function addToCashier(ev, kode)
    {
        if(codeTimeout) clearTimeout(codeTimeout)

        codeTimeout = setTimeout(() => {
            fetch('index.php?r=api/transactions/add-to-cashier&code='+kode+'&pos_sess_id=<?=$pos_sess_id?>')
            .then(res => res.json())
            .then(res => {
                if(!res.hasOwnProperty('error'))
                {
                    window.transactions = res
                    initTransactionToTable()
                    ev.target.value = ""
                }
            })
            .catch(err => {
                console.log(err)
            })
        }, 1000);
    }

    function deleteTransaction(id)
    {
        fetch('index.php?r=api/transactions/delete-transaction&id='+id+'&pos_sess_id=<?=$pos_sess_id?>')
        .then(res => res.json())
        .then(res => {
            window.transactions = res
            initTransactionToTable()
        })
    }

    function initTransactionToTable()
    {
        // console.log(window.transactions)
        // var table = document.querySelector('#transactions-table')
        var xTable = document.getElementById('transactions-table');
        xTable.getElementsByTagName("tbody")[0].innerHTML = "";
        var index = 0;
        for(tr in window.transactions.items)
        {
            var transaction = window.transactions.items[tr]
            
            xTable.getElementsByTagName("tbody")[0].innerHTML += `<tr id="data-${index}" class="data-row" data-id="${transaction.id}"><td>${transaction.code}</td>
                            <td>${transaction.name}</td>
                            <td>${transaction.price_format}</td>
                            <td><input type="number" id="q-${index}" value="${transaction.qty}" min="1" class="p-1" style="width:100%" onchange="updateQty(this,${transaction.id})"></td>
                            <td>${transaction.subtotal_format}</td>
                            <td><button class="btn btn-danger btn-sm" onclick="deleteTransaction(${transaction.id})"><i class="fas fa-times"></i></button></td></tr>`
            index++
        }


        document.querySelector('span#total').innerHTML = window.transactions.hasOwnProperty('total') ? window.transactions.total_format : 0

    }

    function updateQty(el, id)
    {
        fetch('index.php?r=api/transactions/update-qty&id='+id+'&pos_sess_id=<?=$pos_sess_id?>&qty='+el.value)
        .then(res => res.json())
        .then(res => {
            window.transactions = res
            initTransactionToTable()
        })
    }

    function hitungKembalian(nominal,event){
        if(calculateTimeout) clearTimeout(calculateTimeout)

        var kembalian = 0
        if(nominal > 0)
            kembalian = nominal - window.transactions.total


        calculateTimeout = setTimeout(() => {
            document.querySelector('input[name=return_total]').value = kembalian
        }, 200);

        var key = event.key
		if(key == 'Enter')
		{
			document.querySelector('#btn-bayar').click()
		}
    }

    async function bayar(){
        var nominal_bayar = document.querySelector('input[name=payment_total]')
        
        var formData = new FormData
        formData.append('customer_id', '<?=$customer->id?>')
        formData.append('paytotal', nominal_bayar.value)
        formData.append('pos_sess_id', '<?=$pos_sess_id?>')
        
        var request = await fetch('index.php?r=api/transactions/bayar',{
            'method':'POST',
            'body':formData
        })
        var response = await request.json()
        if(response.status == 'success') 
        {
            alert('Pembayaran Berhasil! Klik Oke untuk mencetak struk')
            fetch('index.php?r=print/invoice&inv_code='+response.inv_code)
            // window.open('index.php?r=print/invoice&inv_code='+response.inv_code)
        }
        // .then(res => res.json())
        // .then(res => {
        //     location.reload()
        // })

        
    }

    initTransactionToTable()
    </script>
<?php load_templates('layouts/bottom') ?>