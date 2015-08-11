<?php

function toIndoDate($timeStamp) {
   $tanggal = date_format(date_create($timeStamp), 'j');
   $bulan = date_format(date_create($timeStamp), 'n');
   $namabulan = namaBulan($bulan);
   $tahun = date_format(date_create($timeStamp), 'Y');
   return $tanggal.' '.$namabulan.' '.$tahun;
}

function namaHari($timeStamp) {
   static $hari = array(
       'Ahad',
       'Senin',
       'Selasa',
       'Rabu',
       'Kamis',
       'Jumat',
       'Sabtu'
   );
   return $hari[date('w', strtotime($timeStamp))];
}

function namaBulan($i) {
   static $bulan = array(
       "Januari",
       "Februari",
       "Maret",
       "April",
       "Mei",
       "Juni",
       "Juli",
       "Agustus",
       "September",
       "Oktober",
       "November",
       "Desember"
   );
   return $bulan[$i - 1];
}
?>
<html>
   <head>
      <title>Buku Harian : <?php echo $report['kodeToko'].' '.$report['namaToko'].' '.$report['tanggal']; ?></title>
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css" />
   </head>
   <body>
      <!--mpdf
          <htmlpagefooter name="footer">
              <table style="border-top:thin solid black">
                  <tr>
                      <td style="text-align:left">Buku Harian: <?php
      echo $report['namaToko'].' '.$report['tanggal'];
      ?>
                      </td>
                      <td style="text-align:center">
                      </td>
                      <td style="text-align:right">{PAGENO}{nb}
                      </td>
                  </tr>
               </table>
          </htmlpagefooter>
          <sethtmlpagefooter name="footer" value="on" />
        mpdf-->
      <div id="header1">
         <div>Buku Harian<br /><?php echo $report['namaToko']; ?></div>
         <div id="tanggal"><?php echo namaHari($report['tanggal']).', '.toIndoDate($report['tanggal']); ?></div>
      </div>
      <br />
      <br />		
      <table width="90%" style="margin:0 auto" class="table-bordered">
         <tr>
            <td class="tebal">SALDO AWAL</td>
            <td class="kanan tebal"></td>
         </tr>
         <?php
         if (!empty($pembayaranHutangs)):
            ?>
            <tr>
               <td class="tebal trx-header">PEMBAYARAN HUTANG (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalPembayaranHutang, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($pembayaranHutangs as $pembayaranHutang):
               ?>
               <tr>
                  <td class="level-1"><?php echo date('d-m-Y', strtotime($pembayaranHutang->hutang->tanggal)).' '.$pembayaranHutang->hutang->supplier->nama.' '.$pembayaranHutang->hutang->keterangan; ?></td>
                  <td class="kanan"><?php echo number_format($pembayaranHutang->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php if (!empty($report['pembelianTunai'])):
            ?>
            <tr>
               <td class="tebal trx-header">PEMBELIAN TUNAI (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalPembelianTunai'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['pembelianTunai'] as $pembelianTunai):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$pembelianTunai['nomor']} {$pembelianTunai['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($pembelianTunai['bayar'] + $pembelianTunai['terima'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php if (!empty($banks)):
            ?>
            <tr>
               <td class="tebal trx-header">BANK (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalBank, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($banks as $bank):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$bank->bank->nama} {$bank->keterangan}"; ?></td>
                  <td class="kanan"><?php echo number_format($bank->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php if (!empty($expenses)):
            ?>
            <tr>
               <td class="tebal trx-header">EXPENSE (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalExpense, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($expenses as $expense):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$expense->expense->nama} {$expense->keterangan}"; ?></td>
                  <td class="kanan"><?php echo number_format($expense->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php if (!empty($nonExpenses)):
            ?>
            <tr>
               <td class="tebal trx-header">NON EXPENSE (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalNonExpense, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($nonExpenses as $nonExpense):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$nonExpense->nonExpense->nama} {$nonExpense->keterangan}"; ?></td>
                  <td class="kanan"><?php echo number_format($nonExpense->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php if (!empty($kasbon)):
            ?>
            <tr>
               <td class="tebal trx-header">KASBON (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalKasbon, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($kasbon as $kasbonKaryawan):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$kasbonKaryawan->karyawan->nama} {$kasbonKaryawan->keterangan}"; ?></td>
                  <td class="kanan"><?php echo number_format($kasbonKaryawan->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php if (!empty($saukan)):
            ?>
            <tr>
               <td class="tebal trx-header">SAUKAN (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalSaukan, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($saukan as $saukanKaryawan):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$saukanKaryawan->karyawan->nama} {$saukanKaryawan->keterangan}"; ?></td>
                  <td class="kanan"><?php echo number_format($saukanKaryawan->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php if (!empty($penerimaanPiutangs)):
            ?>
            <tr>
               <td class="tebal trx-header">PENERIMAAN PIUTANG (+)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalPenerimaanPiutang, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($penerimaanPiutangs as $penerimaanPiutang):
               ?>
               <tr>
                  <td class="level-1"><?php echo date('d-m-Y', strtotime($penerimaanPiutang->piutang->tanggal)).' '.$penerimaanPiutang->piutang->rekanan->nama.' '.$penerimaanPiutang->piutang->nomor_nota.' '.$penerimaanPiutang->piutang->keterangan; ?></td>
                  <td class="kanan"><?php echo number_format($penerimaanPiutang->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($pendapatanLainLain)):
            ?>
            <tr>
               <td class="tebal trx-header">PENDAPATAN LAIN-LAIN (+)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalPendapatanLain, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($pendapatanLainLain as $pendapatanLain):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$pendapatanLain->pendapatanLain->nama} {$pendapatanLain->keterangan}"; ?></td>
                  <td class="kanan"><?php echo number_format($pendapatanLain->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($nonPendapatan)):
            ?>
            <tr>
               <td class="tebal trx-header">NON PENDAPATAN (+)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($totalNonPendapatan, 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($nonPendapatan as $nonP):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$nonP->nonPendapatan->nama} {$nonP->keterangan}"; ?></td>
                  <td class="kanan"><?php echo number_format($nonP->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <tr>
            <td class="trx-header tebal">SALDO AKHIR BUKU</td>
            <td class="kanan tebal trx-header"></td>
         </tr>
         <tr>
            <td class="tebal">SALDO AKHIR ASLI</td>
            <td class="kanan tebal"></td>
         </tr>
         <tr>
            <td class="trx-header tebal">OMZET (+)</td>
            <td class="kanan tebal trx-header"><?php echo number_format($report['omzet'], 0, ',', '.'); ?></td>
         </tr>
         <tr>
            <td class="tebal">GROSS PROFIT</td>
            <td class="kanan tebal"></td>
         </tr>
         <?php
         if (!empty($report['pembelianHutang'])):
            ?>
            <tr>
               <td class="trx-header tebal">DAFTAR HUTANG PEMBELIAN</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalPembelianHutang'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['pembelianHutang'] as $hutang):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$hutang['nomor']} {$hutang['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($hutang['jumlah'] - ($hutang['bayar'] + $hutang['terima']), 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($piutangs)):
            ?>
            <tr>
               <td class="trx-header tebal">DAFTAR PIUTANG</td>
               <td></td>
            </tr>
            <?php
            foreach ($piutangs as $piutang):
               ?>
               <tr>
                  <td class="level-1"><?php echo $piutang->rekanan->nama.' '.$piutang->nomor_nota.' '.date('d-m-Y', strtotime($piutang->tanggal_jatuh_tempo)).' '.$piutang->keterangan; ?></td>
                  <td class="kanan"><?php echo number_format($piutang->jumlah, 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
      </table>
      <?php
      if ($bhDetail->remarks > ''):
         ?>
         <div class="remarks-h">Remarks:</div>
         <div class="remarks-d"></div>
         <?php
      endif;
      ?>

   </body>
</html>