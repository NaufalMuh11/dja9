<div class="box box-widget">
  <input type="hidden" id="aktif">
  <input type="hidden" id="strlen" value="0">

  <div class="box-body">
    <table id="iGrid" class="table table-hover table-bordered" style="border-spacing: 1; width: 100%;">
      <div class="input-group" style="padding-bottom: 7px;">
           <input type="hidden" name="nmfunction" value="grid">
           <input id="cari" class="form-control" name="cari" type="text" value="" autocomplete="off" placeholder="Pencarian ...">
            <span class="input-group-btn">
               <button onclick="history.go(-1)" class="btn btn-info btn-flat"><i class="fa fa-search" style="height:16px;margin-top:4px"></i>
               </button>
            </span>
      </div>

      <thead style="font-size:13px">
        <tr style="color: #fff; background-color: #3568B6;">
          <th class="text-center"> No. </th>
          <th class="text-center"> Kelompok </th>
          <th class="text-center"> Ket </th>
        </tr>
      </thead>

      <tbody style="font-size:13px">
          <tr id="tr_crud" style="display: none" bgcolor="#eee">
            <td colspan="6"><div id="crud_form"><?php $this->load->view('referensi/v_sbm_ket'); ?></div></td>
          </tr>

        <!-- <?php foreach ($tabel as $row) { ?>

          <tr id="id_<?= $row['kode'] ?>" class="All" style="margin: 0px; cursor: pointer">
            <td class="text-left"><b><?= $row['kode'] ?></b></td>
            <td id="td_<?= $row['kode'] ?>" class="text-left" data-list="<?= implode('#', $row) ?>"  onclick="go_detil( this.id )">
            <div  style="word-wrap: break-word;">
               <?= $row['uraian'] ?>
            </div>
            </td>
            <td class="text-center"><i class="fa fa-file-text-o"></i></td>
          </tr>

        <?php } ?> -->
      </tbody>
    </table>

  </div>
</div>

