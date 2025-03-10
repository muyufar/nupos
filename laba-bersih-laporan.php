<?php
include '_header.php';
include '_nav.php';
include '_sidebar.php';

if ($levelLogin != "admin" && $levelLogin != "super admin") {
  echo "
    <script>
      document.location.href = 'bo';
    </script>
  ";
}
$listCabang = query("SELECT * FROM toko ");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Laporan Laba Bersih</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="bo">Home</a></li>
            <li class="breadcrumb-item active">Laba Bersih</li>
          </ol>
        </div>
      </div><br>
      <div class="callout callout-info">
        <h5><i class="fas fa-info"></i> Note:</h5>
        Pastikan Anda sudah mengisi Data Operasional Pemasukan & Pengeluaran toko. Jika belum silahkan lengkapi <a
          href="laba-bersih-data" style="color: #007bff;"><b>disini</b></a>.
      </div>
    </div><!-- /.container-fluid -->
  </section>


  <section class="content">
    <div class="container-fluid">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Filter Data Berdasrkan Tanggal</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
          </div>
        </div>
        <!-- /.card-header -->
        <!-- <form role="form" action="laba-bersih-laporan" method="POST"> -->
        <!-- <form role="form" action="laba-bersih-laporan-detail" method="POST" target="_blank"> -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label for="tanggal_awal">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" id="tanggal_awal" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" id="tanggal_akhir" required>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <label for="exampleFormControlInput1">Cabang</label>
                <select class="form-control form-control" id="cabang" <?= $levelLogin == "super admin" ? "" : "disabled" ?>>
                  <?php foreach ($listCabang as $cab) : ?>
                    <option value="<?= $cab['toko_cabang'] ?>" <?= $cab['toko_cabang'] == $_SESSION['user_cabang'] ? 'selected' : '' ?>><?= $cab['toko_nama'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="tanggal_akhir">Aksi</label>
                <button type="button" id="btn-filter" class="btn btn-primary form-control">
                  <i class="fa fa-filter"></i> Filter
                </button>
              </div>
            </div>
          </div>

        </div>
        <!-- </form> -->
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary">
        <div class="card-header ">
          <h3 class="card-title">
            Laporan Laba Bersih
          </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="" id="content-detail">
            <div class="">
              <div class="llb-header">
                <div class="text-center text-lg font-weight-bold" id="store-name">
                </div>
                <div class="text-center" id="store-address">
                </div>
                <div class="llb-header-contact">
                  <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                    <span id="store-phone"></span>
                    <span id="store-wa"></span>
                  </div>
                </div>
                <div class="llb-header-contact">
                  <span id="periode"></span>
                </div>
              </div>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th colspan="2">1. Pendapatan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>a. Sub Total Penjualan</td>
                  <td id="total-penjualan" class="text-right">Rp 0</td>
                </tr>
                <tr>
                  <td>b. DP Piutang</td>
                  <td id="total-dp" class="text-right">Rp 0</td>
                </tr>
                <tr>
                  <td>c. Piutang (Cicilan)</td>
                  <td id="total-piutang" class="text-right">Rp 0</td>
                </tr>
                <tr>
                  <td>d. Pendapatan Lain</td>
                  <td id="total-pendapatan-lain" class="text-right">Rp 0</td>
                </tr>
                <tr>
                  <td><b>Total Pendapatan</b></td>
                  <td id="total-pendapatan" class="text-right">Rp 0</td>
                </tr>
              </tbody>
            </table>
            <table class="table">
              <thead>
                <tr>
                  <th colspan=" 2">2. HPP</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>a. HPP (Harga Pokok Penjualan)</td>
                  <td id="total-hpp" class="text-right">Rp 0</td>
                </tr>
                <tr>
                  <td><b>Laba / Rugi Kotor</b></td>
                  <td id="total-laba-rugi-kotor" class="text-right">Rp 0</td>
                </tr>
              </tbody>
            </table>
            <table class="table">
              <thead>
                <tr>
                  <th colspan="2">3. Biaya Pengeluaran</th>
                </tr>
              </thead>
              <tbody id="table-biaya-pengeluaran">
              </tbody>
            </table>
          </div>
          <div class="mt-3 d-flex" style="gap: 0.5rem;">
            <div class="text-right">
              <button type="button" disabled class="btn btn-success" id="export-btn"><i class="fa fa-download" aria-hidden="true"></i>
                Export
              </button>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-primary" id="print-btn" disabled onclick="printDiv('content-detail')"><i class="fa fa-print" aria-hidden="true"></i>
                Print
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- include 'laba-bersih-laporan-view.php'  -->
</div>
</div>
<script src="/dist/js/utils.js"></script>
<script>
  const base_url = '<?= $base_url ?>'
  
  
  function printDiv(divId) {
    var content = document.getElementById(divId).innerHTML; // Ambil konten div
    var printWindow = window.open('', '', 'height=600,width=800'); // Buka jendela baru

    // Tulis HTML konten ke dalam jendela print
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<link rel="stylesheet" type="text/css" href="dist/css/adminlte.min.css">'); // Tambahkan link ke CSS jika perlu
    printWindow.document.write('</head><body>');
    printWindow.document.write(content); // Masukkan konten ke body
    printWindow.document.write('</body></html>');
    printWindow.document.close(); // Menutup dokumen
    printWindow.print(); // Memulai proses print
  }

  function exportToExcel() {
    var tanggal_awal = $('#tanggal_awal').val();
    var tanggal_akhir = $('#tanggal_akhir').val();
    let cabang = $('#cabang').val();
    fetch(base_url + '/api/admin/laba/bersih/export', {
        method: 'GET', // Anda bisa menggunakan 'POST' jika perlu
        data: {
          cabang:cabang,
          start: tanggal_awal,
          end: tanggal_akhir
        },
        headers: {
          'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // Header untuk mengindikasikan file Excel
        },
      })
      .then(response => {
        if (response.ok) {
          return response.blob(); // Mengambil file sebagai blob
        }
        throw new Error('Network response was not ok');
      })
      .then(blob => {
        // Membuat URL untuk file blob
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'laba_bersih.xlsx'; // Nama file Excel
        document.body.appendChild(link); // Menambahkan link sementara ke body
        link.click(); // Klik link untuk mengunduh file
        document.body.removeChild(link); // Menghapus link setelah digunakan
      })
      .catch(error => {
        console.error('There was a problem with the export:', error);
      });
  }



  $(document).ready(function() {
    $('#export-btn').click(function() {
      var tanggal_awal = $('#tanggal_awal').val();
      var tanggal_akhir = $('#tanggal_akhir').val();
      let cabang = $('#cabang').val();
      $.ajax({
        url: base_url + '/api/admin/laba/bersih/export', // URL untuk ekspor data ke Excel
        method: 'GET', // Atau 'POST' jika perlu
        data: {
          cabang: cabang,
          start: tanggal_awal,
          end: tanggal_akhir
        },
        headers: {
          'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // Header untuk menunjukkan jenis file Excel
        },
        xhrFields: {
          responseType: 'blob', // Mengatur agar respons berupa blob (file)
        },
        success: function(data, status, xhr) {
          // Membuat URL untuk blob yang diterima
          const blob = data;
          const url = window.URL.createObjectURL(blob);
          const link = document.createElement('a');
          link.href = url;
          link.download = 'laba_bersih.xlsx'; // Nama file Excel yang akan diunduh
          document.body.appendChild(link); // Menambahkan link sementara ke body
          link.click(); // Mengklik link untuk memulai unduhan
          document.body.removeChild(link); // Menghapus link setelah digunakan
        },
        error: function(xhr, status, error) {
          console.error('There was a problem with the export:', error);
        }
      });
    });

    $('#btn-filter').click(function() {var tanggal_awal = $('#tanggal_awal').val();    
var tanggal_akhir = $('#tanggal_akhir').val();    
let cabang = $('#cabang').val();    
$.ajax({    
  url: base_url + '/api/admin/laba/bersih',    
  type: 'GET',    
  data: {    
    cabang: cabang,    
    start: tanggal_awal,    
    end: tanggal_akhir    
  },    
  success: (res) => {    
    console.log("🚀 ~ $ ~ res:", res)    
    $('#print-btn').prop('disabled', false);    
    $('#export-btn').prop('disabled', false);    
    
    const total = res?.data?.totals;    
    const labaRugiKotor = total?.laba_rugi_kotor; // Laba / Rugi Kotor    
    const totalBiayaPengeluaran = res?.data?.laba_bersih?.total?.pengeluaran; // Total Biaya Pengeluaran    
    const hpp = total?.hpp; // Harga Pokok Penjualan    
    const totalLaba = labaRugiKotor - totalBiayaPengeluaran; // Calculate Total Laba    
    const profitPercentage = hpp ? ((totalLaba / hpp) * 100).toFixed(2) : 0; // Calculate Profit Percentage    
    
    $('#total-penjualan').text(toRupiah(total.penjualan));    
    $('#total-dp').text(toRupiah(total?.dp_piutang));    
    $('#total-piutang').text(toRupiah(total.piutang));    
    $('#total-pendapatan-lain').text(toRupiah(res?.data?.laba_bersih?.total?.pendapatan));    
    $('#total-pendapatan').text(toRupiah(total?.pendapatan));    
    $('#total-hpp').text(toRupiah(hpp));    
    $('#total-laba-rugi-kotor').text(toRupiah(labaRugiKotor));    
    $('#total-laba-bersih').text(toRupiah(res.total_laba_bersih));    
    $('#store-name').text(res?.data?.toko?.toko_nama);    
    $('#store-address').text(res?.data?.toko?.toko_kota);    
    $('#store-phone').html('<a href="tel:' + res?.data?.toko?.toko_tlpn + '" target="_blank"><i class="fa fa-phone" aria-hidden="true"></i> ' + res?.data?.toko?.toko_tlpn + '</a>');    
    $('#store-wa').html('<a href="https://wa.me/' + res?.data?.toko?.toko_wa + '" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i> ' + res?.data?.toko?.toko_wa + '</a>');    
    $('#periode').html(new Date(tanggal_awal).toLocaleDateString('en-GB', {    
      year: 'numeric',    
      month: 'long',    
      day: 'numeric'    
    }) + ' - ' + new Date(tanggal_akhir).toLocaleDateString('en-GB', {    
      year: 'numeric',    
      month: 'long',    
      day: 'numeric'    
    }));    
    
    let table_pengeluaran = '';    
    res?.data?.laba_bersih?.pengeluaran?.map((item) => {    
      table_pengeluaran += `    
        <tr>    
          <td>${item.kategori}</td>    
          <td class="text-right">${toRupiah(item.jumlah)}</td>    
        </tr>    
      `;    
    });    
    
    table_pengeluaran += `    
      <tr>    
        <td><b>Total Biaya Pengeluaran</b></td>    
        <td class="text-right">${toRupiah(totalBiayaPengeluaran)}</td>    
      </tr>    
      <tr>    
        <td><b>Total Laba</b></td>    
        <td class="text-right">${toRupiah(totalLaba)}</td>    
      </tr>  
      <tr>    
        <td><b>Presentase Keuntungan (%)</b></td>    
        <td class="text-right">${profitPercentage}%</td>    
      </tr>`;    
    
    $('#table-biaya-pengeluaran').html(table_pengeluaran);    
  }  
})  

    });
  })
</script>
<?php include '_footerlaporan.php' ?>
<?php include '_footer.php'; ?>
</body>


</html>