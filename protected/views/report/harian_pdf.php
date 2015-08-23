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
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/pdf-laporan.css" />
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
            <td class="kanan tebal"><?php echo number_format($report['saldoAwal'], 0, ',', '.'); ?></td>
         </tr>
         <?php
         if (!empty($report['pembelianBayar'])):
            ?>
            <tr>
               <td class="tebal trx-header">PEMBAYARAN PEMBELIAN (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalPembelianBayar'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['pembelianBayar'] as $pembayaran):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$pembayaran['nomor']} {$pembayaran['nama']} ".date('d-m-Y', strtotime($pembayaran['tanggal'])); ?></td>
                  <td class="kanan"><?php echo number_format($pembayaran['total_bayar'], 0, ',', '.'); ?></td>
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
                  <td class="kanan"><?php echo number_format($pembelianTunai['jumlah'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         foreach ($report['itemPengeluaran'] as $pengeluaran) {
            if (!empty($pengeluaran['items'])) {
               ?>
               <tr>
                  <td class="tebal trx-header"><?php echo strtoupper($pengeluaran['nama']); ?> (-)</td>
                  <td class="kanan tebal trx-header"><?php echo number_format($pengeluaran['total'], 0, ',', '.'); ?></td>
               </tr>
               <?php
               foreach ($pengeluaran['items'] as $items):
                  ?>
                  <tr>
                     <td class="level-1"><?php echo "[{$items['akun']}] [{$items['nama']}] {$items['keterangan']}"; ?></td>
                     <td class="kanan"><?php echo number_format($items['jumlah'], 0, ',', '.'); ?></td>
                  </tr>
                  <?php
               endforeach;
            }
         }
         ?>
         <?php
         foreach ($report['itemPenerimaan'] as $penerimaan) {
            if (!empty($penerimaan['items'])) {
               ?>
               <tr>
                  <td class="tebal trx-header"><?php echo strtoupper($penerimaan['nama']); ?> (+)</td>
                  <td class="kanan tebal trx-header"><?php echo number_format($penerimaan['total'], 0, ',', '.'); ?></td>
               </tr>
               <?php
               foreach ($penerimaan['items'] as $items):
                  ?>
                  <tr>
                     <td class="level-1"><?php echo "[{$items['akun']}] [{$items['nama']}] {$items['keterangan']}"; ?></td>
                     <td class="kanan"><?php echo number_format($items['jumlah'], 0, ',', '.'); ?></td>
                  </tr>
                  <?php
               endforeach;
            }
         }
         ?>
         <tr>
            <td class="trx-header tebal">SALDO AKHIR BUKU</td>
            <td class="kanan tebal trx-header"><?php echo number_format($report['saldoAkhir'], 0, ',', '.'); ?></td>
         </tr>
         <tr>
            <td class="tebal">SALDO AKHIR ASLI</td>
            <td class="kanan tebal"><?php echo number_format($report['saldoAkhirAsli'], 0, ',', '.'); ?></td>
         </tr>         
         <?php
         if (!empty($report['penjualanTunai'])):
            ?>
            <tr>
               <td class="trx-header tebal">PENJUALAN TUNAI (+)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalPenjualanTunai'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['penjualanTunai'] as $penjualanTunai):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$penjualanTunai['nomor']} {$penjualanTunai['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($penjualanTunai['jumlah'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>  
         <?php
         if (!empty($report['margin'])):
            ?>
            <tr>
               <td class="trx-header tebal">GROSS PROFIT (MARGIN)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalMargin'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['margin'] as $marginPenjualan):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$marginPenjualan['nomor']} {$marginPenjualan['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($marginPenjualan['margin'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?> 
         <?php
         if (!empty($report['penjualanBayar'])):
            ?>
            <tr>
               <td class="trx-header tebal">PENERIMAAN PIUTANG PENJUALAN (+)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalPenjualanBayar'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['penjualanBayar'] as $penerimaanPiutang):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$penerimaanPiutang['nomor']} {$penerimaanPiutang['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($penerimaanPiutang['jumlah_bayar'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($report['penjualanPiutang'])):
            ?>
            <tr>
               <td class="trx-header tebal">PIUTANG PENJUALAN</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalPenjualanPiutang'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['penjualanPiutang'] as $piutangPenjualan):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$piutangPenjualan['nomor']} {$piutangPenjualan['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format(abs($piutangPenjualan['jml_bayar'] - $piutangPenjualan['jumlah']), 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($report['returJualTunai'])):
            ?>
            <tr>
               <td class="trx-header tebal">RETUR PENJUALAN (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalReturJualTunai'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['returJualTunai'] as $returJual):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$returJual['nomor']} {$returJual['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($returJual['jumlah'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($report['returBeliTunai'])):
            ?>
            <tr>
               <td class="trx-header tebal">RETUR PEMBELIAN (+)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalReturBeliTunai'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['returBeliTunai'] as $returBeli):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$returBeli['nomor']} {$returBeli['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($returBeli['jumlah'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($report['returBeliBayar'])):
            ?>
            <tr>
               <td class="trx-header tebal">PENERIMAAN PIUTANG RETUR PEMBELIAN (+)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalReturBeliBayar'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['returBeliBayar'] as $penerimaanPiutangReturBeli):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$penerimaanPiutangReturBeli['nomor']} {$penerimaanPiutangReturBeli['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($penerimaanPiutangReturBeli['jumlah_bayar'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($report['returJualBayar'])):
            ?>
            <tr>
               <td class="trx-header tebal">PEMBAYARAN HUTANG RETUR PENJUALAN (-)</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalReturJualBayar'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['returJualBayar'] as $pembayaran):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$pembayaran['nomor']} {$pembayaran['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($pembayaran['jumlah_bayar'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($report['returBeliPiutang'])):
            ?>
            <tr>
               <td class="trx-header tebal">PIUTANG RETUR PEMBELIAN</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalReturBeliPiutang'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['returBeliPiutang'] as $piutangReturBeli):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$piutangReturBeli['nomor']} {$piutangReturBeli['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($piutangReturBeli['jumlah'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
         <?php
         if (!empty($report['returJualHutang'])):
            ?>
            <tr>
               <td class="trx-header tebal">HUTANG RETUR PENJUALAN</td>
               <td class="kanan tebal trx-header"><?php echo number_format($report['totalReturJualHutang'], 0, ',', '.'); ?></td>
            </tr>
            <?php
            foreach ($report['returJualHutang'] as $hutangReturJual):
               ?>
               <tr>
                  <td class="level-1"><?php echo "{$hutangReturJual['nomor']} {$hutangReturJual['nama']}"; ?></td>
                  <td class="kanan"><?php echo number_format($hutangReturJual['jumlah'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
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
                  <td class="kanan"><?php echo number_format($hutang['jumlah'], 0, ',', '.'); ?></td>
               </tr>
               <?php
            endforeach;
            ?>
            <?php
         endif;
         ?>
      </table>
      <?php
      if ($report['keterangan'] > ''):
         ?>
         <div class="remarks-h">Remarks:</div>
         <div class="remarks-d"><?php echo nl2br($report['keterangan']); ?></div>
         <?php
      endif;
      ?>

   </body>
</html>