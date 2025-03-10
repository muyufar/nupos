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

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
  // var_dump($_POST);

  // cek apakah data berhasil di tambahkan atau tidak
  if (editLabaBersih($_POST) > 0) {
    echo "
      <script>
        alert('Data Berhasil diupdate');
        document.location.href = 'laba-bersih-data';
      </script>
    ";
  } elseif (editLabaBersih($_POST) == null) {
    echo "
      <script>
        alert('Anda Belum Melakukan Perubahan Data');
      </script>
    ";
  } else {
    echo "
      <script>
        alert('data gagal ditambahkan');
      </script>
    ";
  }
}

$labaBersih = query("SELECT * FROM laba_bersih WHERE lb_cabang = $sessionCabang")[0];
$listCabang = query("SELECT * FROM toko ");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-9">
          <h1>Data Operasional Toko dari Pendapatan & Pengeluaran (<?= $levelLogin ?>)</h1>
        </div>
        <div class="col-sm-3">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="bo">Home</a></li>
            <li class="breadcrumb-item active">Data Operasional</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary">
        <div class="card-header d-flex align-content-center">
          <h3 class="card-title">Data Operasional</h3>
          <button
            id="btn-add-modal"
            class="btn btn-success btn-sm ml-auto d-flex justify-content-around  align-content-center"
            data-toggle="modal"
            data-target="#modal-add"
            style="gap: 0.2rem;">
            <i class="bi bi-plus"></i>
            <span>
              Tambah
            </span>
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label for="exampleFormControlInput1">Periode</label>
                <div class="row">
                  <div class="col-6">
                    <input type="date" class="form-control" id="date-start">
                  </div>
                  <div class="col-6">
                    <input type="date" class="form-control" id="date-end">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <label for="jenis">Jenis</label>
                <select class="form-control form-control" id="jenis">
                  <option value="">Semua</option>
                  <option value="0">Pendapatan</option>
                  <option value="1">Pengeluaran</option>
                </select>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <label for="exampleFormControlInput1">Kategori</label>
                <select class="form-control form-control kategori">
                  <option value="">loading</option>
                </select>
              </div>
            </div>
            <!-- <div class="col-2">
              <div class="form-group">
                <label for="exampleFormControlInput1">Keterangan</label>
                <input type="text" class="form-control" placeholder="Cari berdasarkan keterangan" id="keterangan">
              </div>
            </div> -->
            <div class="col-2">
              <div class="form-group">
                <label for="exampleFormControlInput1">Cabang</label>
                <select class="form-control form-control" id="cabang" <?= $levelLogin == "super admin" ? "" : "disabled" ?>>
                  <option value="">Semua</option>
                  <?php foreach ($listCabang as $cab) : ?>
                    <option value="<?= $cab['toko_cabang'] ?>" <?= $cab['toko_cabang'] == $_SESSION['user_cabang'] ? 'selected' : '' ?>><?= $cab['toko_nama'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-2 d-flex justify-content-end align-items-md-end pb-3">
              <button type="button" onclick="getData()" class="btn btn-primary btn-block filter mt-auto">
                <i class="bi bi-filter"></i>
                Filter
              </button>
            </div>
            <div class="col-12 table-responsive mt-3">
              <table class="table table-striped ">
                <caption class="text-center">
                  Tabel Data Operasional periode <span id="period"></span> <i class="bi bi-pencil-square"></i>
                </caption>
                <thead class="thead-default">
                  <tr>
                    <th class="text-center " style="width: 160px;">Dibuat</th>
                    <th class="text-center " style="width: 160px;">Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th class="text-right">Cabang</th>
                    <th class="text-right">Nilai</th>
                    <th class="text-right">PJ</th>
                    <?php if ($levelLogin == 'super admin') : ?>
                      <th class="text-center" style="width: fit-content;">Aksi</th>
                    <?php endif; ?>
                  </tr>
                </thead>
                <tbody class="" id="table-data"></tbody>
              </table>
              <div id="pagination"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="modal-add" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Operasional</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-add">
          <input type="hidden" id="form-type" value="create">
          <input type="hidden" id="add-id" value="">

          <div class="form-group">
            <label for="add-tanggal">Tanggal</label>
            <input type="date" name="keterangan" id="add-tanggal" class="form-control" required>
            <div class="invalid-feedback">
              Tanggal harus diisi
            </div>
          </div>
          <div class="form-group">
            <label for="add-jenis">Jenis</label>
            <select name="jenis" class="form-control form-control jenis" id="add-jenis">
              <option value="0">Pendapatan</option>
              <option value="1" selected>Pengeluaran</option>
            </select>
            <div class="invalid-feedback">
              Jenis diisi
            </div>
          </div>
          <div class="form-group">
            <label for="add-kategori">Kategori</label>
            <select class="form-control form-control kategori" id="add-kategori">
              <option value="">loading</option>
            </select>
            <div class="invalid-feedback">
              Kategori harus diisi
            </div>
          </div>
          <div class="form-group">
            <label for="add-keterangan">Keterangan</label>
            <textarea name="keterangan" id="add-keterangan" class="form-control" placeholder="isikan keterangan jika diperlukan"></textarea>
          </div>
          <div class="form-group">
            <label for="add-jumlah">Jumlah</label>
            <input type="number" min="1" name="jumlah" class="form-control" id="add-jumlah" placeholder="isikan jumlah contoh : 90000" required>
            <div class="invalid-feedback">
              Jumlah harus diisi
            </div>
          </div>
          <div class="form-group">
            <label for="add-jenis">Cabang</label>
            <select name="jenis" class="form-control form-control jenis" id="add-cabang" <?= $levelLogin != "super admin" ? 'disabled' : '' ?>>
              <?php foreach ($listCabang as $c) : ?>
                <option value="<?= $c['toko_cabang']; ?>"
                  <?= $_SESSION['user_cabang'] == $c['toko_cabang'] ? ' selected' : '' ?>>
                  <?= $c['toko_nama']; ?> - <?= $c['toko_kota']; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
              Jenis diisi
            </div>
          </div>
          <div class="form-group">
            <label for="add-pj">Penanggung Jawab</label>
            <input type="text" name="name" class="form-control" id="add-pj" aria-describedby="pjhelp">
            <small id="pjhelp" class="form-text text-muted">Pastikan nama lengkap dengan gelar.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btn-close" data-dismiss="modal">Tututp</button>
        <button type="button" class="btn btn-primary" id="btn-add">Simpan</button>
      </div>
    </div>
  </div>
</div>


<script src="./dist/js/utils.js"></script>
<script>
  const base_url = '<?= $base_url ?>'
  let page = 1
  const levelAdmin = '<?php echo $_SESSION['user_level']; ?>';

  const deleteLaba = (id) => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      console.log("🚀 ~ deleteLaba ~ result:", result)
      if (result.value) {
        $.ajax({
          url: `${base_url}/api/admin/laba/${id}`,
          type: 'DELETE',
          success: function(response) {
            Swal.fire({
              title: "Deleted!",
              text: "Your file has been deleted.",
              icon: "success"
            });
            getData();
          },
          error: function(xhr, status, error) {
            console.log(error);
            Swal.fire({
              title: "Error!",
              text: xhr.responseText,
              icon: "error"
            });
          }
        });
      }
    });
  }

  const editLaba = (item) => {
    // API date format: '17/11/2024, 06:22'
    const apiDate = item?.date || ''; // '17/11/2024, 06:22'

    if (apiDate) {
      // Split date and time parts
      const [datePart] = apiDate.split(','); // Extract '17/11/2024'

      // Split day, month, year
      const [day, month, year] = datePart.split('/'); // ['17', '11', '2024']

      // Reformat to 'YYYY-MM-DD' for input type="date"
      const formattedDate = `${year}-${month}-${day}`;

      // Set the value of the input
      $('#add-tanggal').val(formattedDate);
    } else {
      // Use the current date if API date is not available
      const currentDate = new Date();
      const today = currentDate.toISOString().split('T')[0]; // Format as 'YYYY-MM-DD'
      $('#add-tanggal').val(today);
    }

    $('#form-type').val('edit')
    $('#add-id').val(item?.id)
    $('#add-jenis').val(item?.tipe)
    $('#add-kategori').val(item?.kategori?.id)
    $('#add-keterangan').val(item?.keterangan)
    $('#add-jumlah').val(item?.jumlah)
    $('#add-cabang').val(item?.cabang?.cabang)
    $('#add-pj').val(item?.name)
    $('#modal-add').modal('show')
  }

  const renderAction = (item) => {
    return `
      <td class="text-center">
        <button class="btn btn-danger btn-sm" onclick="deleteLaba('${item?.id}')"><i class="bi bi-trash"></i></button>
        <button class="btn btn-warning btn-sm" onclick='editLaba(${JSON.stringify(item)})'><i class="bi bi-pencil"></i></button>
      </td>
    `;
  }



  const getData = (link) => {
    $.ajax({
      url: link ?? base_url + '/api/admin/laba',
      method: 'GET',
      data: {
        date_start: $('#date-start').val(),
        date_end: $('#date-end').val(),
        tipe: $('#jenis').val() ?? null,
        kategori: $('#kategori').val() ?? null,
        cabang: $('#cabang').val() ?? null,
        keterangan: $('#keterangan').val(),
      },
      headers: {
        'Accept': 'application/json',
      },
      dataType: 'json',
      beforeSend: () => {
        $('#table-data').html('<tr><td class="text-center" colspan="7">Loading...</td></tr>')
      },
      success: (res) => {
        let html = ''
        res.data?.data?.forEach((item, index) => {
          html += `
            <tr>
            <td class="text-center">${item.created_at}</td>
            <td class="text-center">${item.date??'-'}</td>
              <td>${item.tipe==1?'Pengeluaran':'Pendapatan'}</td>
              <td>${item.kategori?.name??'-'}</td>
              <td>${item.keterangan??'-'}</td>
              <td class="text-right">${item.cabang?.name}</td>
              <td class="text-right">${toRupiah(item.jumlah,false)}</td>
              <td class="text-right">${item?.name ?? "-"}</td>
              ${levelAdmin == 'super admin' ? renderAction(item) : ''}
            </tr>`
        })
        $('#table-data').html(html)
        $('#period').text(`${$('#date-start').val().split('-').reverse().join('/')} s/d ${$('#date-end').val().split('-').reverse().join('/')}`);
        let pagination = ''
        if (res.data?.links?.length > 0) {
          pagination += `<nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">`
          res.data?.links.forEach((item, index) => {
            if (index !== 0 && index !== res.data.links.length - 1) {
              pagination += `<li class="page-item ${item.active?'active':''}"><button class="page-link" onclick="getData('${item.url}')">${item.label}</button></li>`
            }
          })
          pagination += `</ul></nav>`
        }
        $('#pagination').html(pagination)
      },
      error: (err) => {
        console.log(err)
        $('#table-data').html('<tr><td class="text-center" colspan="7">Data Tidak Ditemukan</td></tr>')
      }
    })
  }

  const getKategori = () => {
    $.ajax({
      url: base_url + '/api/admin/laba/kategori',
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      },
      dataType: 'json',
      success: (res) => {
        let html = ''
        res.data?.forEach((item, index) => {
          html += `<option value="${item.id}">${item.name}</option>`
        })
        // $('#kategori').html(html)
        $('.kategori').html(html)
      },

    })
  }

  $('#btn-add-modal').click(() => {
    $('#form-type').val('create')
  })

  $(document).ready(function() {
    const date = new Date()
    const firstDayOfMonth = new Date(date.getFullYear(), date.getMonth(), 1)
    $('#date-start').val(`${date.getFullYear()}-${('0' + (date.getMonth() + 1)).slice(-2)}-01`)
    $('#date-end').val(date.toISOString().split('T')[0])
    getKategori()
    getData()

    // Button click triggers form submission
    $('#btn-add').on('click', function() {

      const keterangan = $('#add-keterangan')
      const tanggal = $('#add-tanggal')
      const jenis = $('#add-jenis')
      const kategori = $('#add-kategori')
      const jumlah = $('#add-jumlah')

      if (!tanggal.val() || !jenis.val() || !kategori.val() || !jumlah.val()) {
        if (!tanggal.val()) {
          tanggal.addClass('is-invalid')
        }
        if (jenis.val() === '' || jenis.val() === null) {
          jenis.addClass('is-invalid')
        }
        if (kategori.val() === '' || kategori.val() === null) {
          kategori.addClass('is-invalid')
        }
        if (!jumlah.val()) {
          jumlah.addClass('is-invalid')
        }
        alert('Periksa kembali isian, tidak boleh ada yang kosong')
        return
      }

      if (kategori.val() == 9 && (keterangan.val() == '' || keterangan.val() == null)) {
        keterangan.addClass('is-invalid')
        alert('Periksa kembali isian, tidak boleh ada yang kosong')
        return
      }

      const formData = {
        keterangan: $('#add-keterangan').val(),
        date: $('#add-tanggal').val(),
        tipe: $('#add-jenis').val(),
        kategori: $('#add-kategori').val(),
        jumlah: $('#add-jumlah').val(),
        cabang: $('#add-cabang').val(),
        name:$('#add-pj').val()
      };

      if ($('#form-type').val() == 'create') {
        return createOperasional(formData)
      } else if ($('#form-type').val() == 'edit') {
        formData.id = $('#add-id').val()
        return updateOperasional(formData)
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Terjadi ksealahan - tipe pengiriman tidak ditemukan !",
        });
      }



    });

    $('#btn-close').on('click', function() {
      $('#add-keterangan').removeClass('is-invalid')
      $('#add-jenis').removeClass('is-invalid')
      $('#add-kategori').removeClass('is-invalid')
      $('#add-jumlah').removeClass('is-invalid')
      $('#form-add')[0].reset()
      $('#btn-add').prop('disabled', false).html('Simpan')
    })
  })

  function createOperasional(formData) {
    $.ajax({
      url: base_url + '/api/admin/laba',
      method: 'POST',
      data: formData,
      headers: {
        'Accept': 'application/json',
      },
      dataType: 'json',
      beforeSend: () => {
        $('#btn-add').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...')
      },
      success: (res) => {
        console.log(res)
        getData()
        $('#add-keterangan').removeClass('is-invalid')
        $('#add-jenis').removeClass('is-invalid')
        $('#add-kategori').removeClass('is-invalid')
        $('#add-jumlah').removeClass('is-invalid')
        $('#form-add')[0].reset()
        $('#btn-close').click()
      },
      error: (err) => {
        console.log(err)
        alert('Terjadi Kesalahan -', err?.message)
      }
    });
  }

  function updateOperasional(formData) {
    $.ajax({
      url: base_url + '/api/admin/laba',
      method: 'PUT',
      data: formData,
      headers: {
        'Accept': 'application/json',
      },
      dataType: 'json',
      beforeSend: () => {
        $('#btn-add').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...')
      },
      success: (res) => {
        console.log(res)
        getData()
        $('#add-keterangan').removeClass('is-invalid')
        $('#add-jenis').removeClass('is-invalid')
        $('#add-kategori').removeClass('is-invalid')
        $('#add-jumlah').removeClass('is-invalid')
        $('#form-add')[0].reset()
        $('#btn-close').click()
      },
      error: (err) => {
        console.log(err)
        $('#btn-add').prop('disabled', false).html('Simpan')
        alert('Terjadi Kesalahan -', err?.message)
      }
    });
  }
</script>


<?php include '_footer.php'; ?>