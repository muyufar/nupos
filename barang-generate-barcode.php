<?php 
  include '_header.php';
  include '_nav.php';
  include '_sidebar.php'; 
?>
<?php  
  if ( $levelLogin === "kasir") {
    echo "
      <script>
        document.location.href = 'bo';
      </script>
    ";
  }


  $id = abs((int)base64_decode($_GET['id']));
  $barang = query("SELECT * FROM barang WHERE barang_id = $id")[0];

  // Proses Generate Barcode
  require "vendor/barcode/autoload.php";
  $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
  file_put_contents('vendor/barcode/img/'.$barang["barang_kode"].'-produk-'.$barang["barang_nama"].'-cabang-'.$sessionCabang.'.png', $generator->getBarcode($barang["barang_kode"], $generator::TYPE_CODE_128, 3, 50));
?>


  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1>Generate Barcode <b>Produk <?= $barang['barang_nama']; ?></b></h1>
          </div>
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="bo">Home</a></li>
              <li class="breadcrumb-item active">Barcode</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Barcode</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6 col-lg-6">
<div class="detail-barcode-box" id="detail-barcode-box" style="padding: 10px; position: relative;">
    <!-- Harga Barang -->
    <div style="position: relative; margin-bottom: 20px;">
        <!-- Label Harga -->
        <span style="font-size: 20px; font-weight: bold;">Rp</span>

        <!-- Angka Harga -->
        <span style="position: absolute; left: 170px; font-size: 30px; font-weight: bold;">
            <?= number_format($barang["barang_harga"], 0, ',', '.'); ?>
        </span>
        <div style="clear: both;"></div>

        <!-- Keterangan Harga Retail -->
        <?php if ($barang["barang_harga_grosir_1"] > 0): ?>
            <div style="margin-top: 10px; font-size: 14px; font-weight: normal;">
                <span style="font-weight: normal;">Harga Retail:</span>
                <span style="font-size: 16px; font-weight: bold; margin-left: 10px;">
                    Rp <?= number_format($barang["barang_harga_grosir_1"], 0, ',', '.'); ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- Keterangan Harga Grosir -->
        <?php if ($barang["barang_harga_grosir_2"] > 0): ?>
            <div style="margin-top: 0px; font-size: 14px; font-weight: normal;">
                <span style="font-weight: normal;">Harga Grosir:</span>
                <span style="font-size: 16px; font-weight: bold; margin-left: 10px;">
                    Rp <?= number_format($barang["barang_harga_grosir_2"], 0, ',', '.'); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Nama Barang -->
    <div style="font-size: 21px; margin-top: 0px; text-align: left; padding-left: 0; max-width: 28ch; word-wrap: break-word; overflow-wrap: break-word;">
        <?= $barang["barang_nama"]; ?>
    </div>

    <!-- Kode Barang -->
    <div style="font-size: 18px; margin-top: 0px; text-align: left; padding-left: 0;">
        <?= $barang["barang_kode"]; ?>
    </div>
</div>







                          <div class="card-footer text-right">
                              <input id="btn_convert" class="btn btn-primary" type="button" value="Download" />
                          </div>
                          <br>
                    </div>

                    <div class="col-md-6 col-lg-6">
                      <form action="barang-generate-barcode-lots" method="post" target="_blank">
                          <div class="form-group">
                            <label for="barang_kode">Cetak Barcode Sesuai Jumlah Keinginan</label>
                            <input type="number" name="input_barcode" class="form-control" id="barang_kode" placeholder="Contoh: 26" required>
                            <input type="hidden" name="input_kode" value="<?= $barang['barang_kode']; ?>">
                          </div>
                          <div class="card-footer text-right">
                            <button type="submit" name="submit" class="btn btn-primary">Generate Barcode</button>
                          </div>
                          <br>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
              
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

<?php include '_footer.php'; ?>
<script>
    document.getElementById("btn_convert").addEventListener("click", function() {
      html2canvas(document.getElementById("detail-barcode-box"), {
        allowTaint: true,
        useCORS: true
      }).then(function (canvas) {
        var anchorTag = document.createElement("a");
        document.body.appendChild(anchorTag);
        anchorTag.download = "<?= $barang["barang_nama"]; ?>-kode-barcode-<?= $barang["barang_kode"]; ?>-cabang-<?= $sessionCabang; ?>.jpg";
        anchorTag.href = canvas.toDataURL();
        anchorTag.target = '_blank';
        anchorTag.click();
      });
 });
</script>
