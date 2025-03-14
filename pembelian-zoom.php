<?php 
  include '_header.php';
  include '_nav.php';
  include '_sidebar.php'; 
?>

<?php  
	// ambil data di URL
	$id = abs((int)base64_decode($_GET['no']));

	// query data mahasiswa berdasarkan id
	$invoice = query("SELECT * FROM invoice_pembelian WHERE invoice_pembelian_id = $id && invoice_pembelian_cabang = $sessionCabang ")[0];
  $tipeTransaksi  = $invoice['invoice_hutang'];
?>
	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Invoice Pembelian</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="bo">Home</a></li>
              <li class="breadcrumb-item active">Invoice</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Note:</h5>
				      Data detail Transaksi dengan N0. Invoice: <?= $invoice['pembelian_invoice']; ?>
            </div>


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> N0. Invoice: <?= $invoice['pembelian_invoice']; ?>
                    <small class="float-right">Tanggal: <?= $invoice['invoice_tgl']; ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
             <?php  
                  $toko = query("SELECT * FROM toko WHERE toko_cabang = $sessionCabang");
              ?>
              <?php foreach ( $toko as $row ) : ?>
                  <?php 
                    $toko_nama   = $row['toko_nama'];
                    $toko_kota   = $row['toko_kota'];
                    $toko_tlpn   = $row['toko_tlpn'];
                    $toko_wa     = $row['toko_wa']; 
                    $toko_email  = $row['toko_email'];
                    $toko_alamat = $row['toko_alamat'];
                  ?>
              <?php endforeach; ?>
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Dari
                  <address>
                    <strong><?= $toko_nama; ?></strong><br>
                    <?= $toko_alamat; ?><br>
                    Tlpn/wa: <?= $toko_tlpn; ?> / <?= $toko_wa; ?><br>
                    Email: <?= $toko_email; ?><br>

                    <?php  
                    	$kasir = $invoice['invoice_kasir'];
                    	$dataKasir = query("SELECT * FROM user WHERE user_id = $kasir");
                    ?>
                    <?php foreach ( $dataKasir as $ksr ) : ?>
                    	<?php $ksrDetail = $ksr['user_nama']; ?>
                    <?php endforeach; ?>

                    <b>Kasir: </b><?= $ksrDetail; ?>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Supplier
                  <address>
                    <?php  
                      $supplier = $invoice['invoice_supplier'];
                      $dataSupplier = query("SELECT * FROM supplier WHERE supplier_id = $supplier");
                    ?>
                    <?php foreach ( $dataSupplier as $ctr ) : ?>
                      <?php 
                        $supplier_nama   = $ctr['supplier_nama']; 
                        $supplier_alamat = $ctr['supplier_alamat'];
                        $supplier_company  = $ctr['supplier_company'];
                        $supplier_wa   = $ctr['supplier_wa'];
                      ?>
                    <?php endforeach; ?>

                    <strong><?= $supplier_nama; ?></strong><br>
                    <?= $supplier_alamat; ?><br>
                    Tlpn/wa:
                      <?php  
                        if ( $supplier_wa == null ) {
                          echo "-";
                        } else {
                          echo $supplier_wa;
                        }
                      ?>

                    <br>
                    <b>Perusahaan:</b> 
                      <?php  
                        if ( $supplier_company == null ) {
                          echo"-";
                        } else {
                          echo "<b>".$supplier_company."</b>";
                        }
                      ?>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="card-body">
                <div class="table-auto">
                  <table id="example1" class="table table-striped">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Barang</th>
                      <th>Harga Beli</th>
                      <th>Qty</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $invoice1 = $invoice['pembelian_invoice_parent'];
                    $total = 0;
	                  $i = 1; 
	                  $queryProduct = $conn->query("SELECT pembelian.pembelian_id, barang.barang_id, barang.barang_nama, pembelian.barang_harga_beli, barang.barang_stock, pembelian.barang_qty, pembelian.pembelian_invoice, pembelian.pembelian_invoice_parent, pembelian.pembelian_cabang
	                             FROM pembelian 
	                             JOIN barang ON pembelian.barang_id = barang.barang_id
	                             WHERE pembelian_invoice_parent = $invoice1 && pembelian_cabang = '".$sessionCabang."'
	                             ORDER BY pembelian_id DESC
	                             ");
	                  while ($rowProduct = mysqli_fetch_array($queryProduct)) {
                      $subTotal = $rowProduct['barang_qty'] * $rowProduct['barang_harga_beli'];
                      $total += $subTotal;
	                ?>
	                
                    <tr>
                      <td><?= $i; ?></td>
                      <td><?= $rowProduct['barang_nama']; ?></td>
                      <td><?= $rowProduct['barang_harga_beli']; ?></td>
                      <td style="text-align: center; width: 11%;">
                        <?= $rowProduct['barang_qty']; ?>
                      </td>
                      <td><?= $subTotal; ?>
                      </td>
                    </tr>
                    <?php $i++; ?>
                	<?php } ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <div class="table-responsive">

                    <table class="table">
                      <tr>
                        <th style="width:50%">Total:</th>
                        <td>Rp. <?= number_format($total, 0, ',', '.'); ?></td>
                      </tr>

                      <?php if ( $tipeTransaksi == 1 ) { ?>
                      <tr>
                        <th>DP</th>
                        <td>Rp. <?= number_format($invoice['invoice_hutang_dp'], 0, ',', '.'); ?></td>
                      </tr>
                      <?php } ?>

                      <tr>
                        <th>
                          <?php 
                            if ( $tipeTransaksi < 1 ) {
                              echo "Bayar";
                            } else {
                              echo "DP + Cicilan";
                            }
                          ?>
                        </th>
                        <td>Rp. <?= number_format($invoice['invoice_bayar'], 0, ',', '.'); ?></td>
                      </tr>
                      <tr>
                        <th>
                          <?php  
                            if ( $tipeTransaksi < 1 ) {
                              echo "Uang Kembali";
                            } else {
                              echo "Sisa Hutang";
                            }
                          ?>
                        </th>
                        <td>Rp. <?= number_format($invoice['invoice_kembali'], 0, ',', '.'); ?></td>
                      </tr>
                    </table>
                  </div>
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="#!" class="btn btn-success float-right" onclick="self.close()" style="margin-right: 5px;"> Kembali</a>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<?php include '_footer.php'; ?>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script>
  $(function () {
    $("#example1").DataTable();
  });
</script>
<script>
    function hanyaAngka(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
       if (charCode > 31 && (charCode < 48 || charCode > 57))
 
        return false;
      return true;
    }
    function hanyaAngka1(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode
       if (charCode > 31 && (charCode < 48 || charCode > 57))
 
        return false;
      return true;
    }
</script>
 <script>
      function hitung2() {
      var a = $(".a2").val();
      var b = $(".b2").val();
      c = a - b;
      $(".c2").val(c);
      }
      function isNumberKey(evt){
       var charCode = (evt.which) ? evt.which : event.keyCode;
       if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
       return false;
       return true;
      }
</script>


