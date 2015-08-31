<?php

/**
 * This is the model class for table "penjualan".
 *
 * The followings are the available columns in table 'penjualan':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property string $hutang_piutang_id
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property HutangPiutang $hutangPiutang
 * @property Profil $profil
 * @property User $updatedBy
 * @property PenjualanDetail[] $penjualanDetails
 */
class Penjualan extends CActiveRecord {

   const STATUS_DRAFT = 0;
   const STATUS_PIUTANG = 1;
   const STATUS_LUNAS = 2;
   /* ========== */
   const CUSTOMER_UMUM = 2; // ID di DB untuk customer UMUM (non member)

   public $max; // Untuk mencari untuk nomor surat;
   public $namaProfil;
   public $nomorHutangPiutang;

   /**
    * @return string the associated database table name
    */
   public function tableName() {
      return 'penjualan';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules() {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
          array('profil_id', 'required'),
          array('status', 'numerical', 'integerOnly' => true),
          array('nomor', 'length', 'max' => 45),
          array('profil_id, hutang_piutang_id, updated_by', 'length', 'max' => 10),
          array('created_at, updated_at, updated_by, tanggal', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, nomor, tanggal, profil_id, hutang_piutang_id, status, updated_at, updated_by, created_at, namaProfil, nomorHutangPiutang', 'safe', 'on' => 'search'),
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
          'hutangPiutang' => array(self::BELONGS_TO, 'HutangPiutang', 'hutang_piutang_id'),
          'profil' => array(self::BELONGS_TO, 'Profil', 'profil_id'),
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
          'penjualanDetails' => array(self::HAS_MANY, 'PenjualanDetail', 'penjualan_id'),
      );
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels() {
      return array(
          'id' => 'ID',
          'nomor' => 'Nomor',
          'tanggal' => 'Tanggal',
          'profil_id' => 'Profil',
          'hutang_piutang_id' => 'Hutang Piutang',
          'status' => 'Status',
          'updated_at' => 'Updated At',
          'updated_by' => 'Updated By',
          'created_at' => 'Created At',
          'namaProfil' => 'Customer',
          'nomorHutangPiutang' => 'Nomor Piutang'
      );
   }

   /**
    * Retrieves a list of models based on the current search/filter conditions.
    *
    * Typical usecase:
    * - Initialize the model fields with values from filter form.
    * - Execute this method to get CActiveDataProvider instance which will filter
    * models according to data in model fields.
    * - Pass data provider to CGridView, CListView or any similar widget.
    *
    * @return CActiveDataProvider the data provider that can return the models
    * based on the search/filter conditions.
    */
   public function search() {
      // @todo Please modify the following code to remove attributes that should not be searched.

      $criteria = new CDbCriteria;

      $criteria->compare('id', $this->id, true);
      $criteria->compare('t.nomor', $this->nomor, true);
      $criteria->compare('tanggal', $this->tanggal, true);
      $criteria->compare('profil_id', $this->profil_id, true);
      $criteria->compare('hutang_piutang_id', $this->hutang_piutang_id, true);
      $criteria->compare('t.status', $this->status);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      $criteria->with = array('profil', 'hutangPiutang');
      $criteria->compare('profil.nama', $this->namaProfil, true);
      $criteria->compare('hutangPiutang.nomor', $this->nomorHutangPiutang, true);

      $sort = array(
          'defaultOrder' => 't.status, tanggal desc',
          'attributes' => array(
              '*',
              'namaProfil' => array(
                  'asc' => 'profil.nama',
                  'desc' => 'profil.nama desc'
              ),
              'nomorHutangPiutang', array(
                  'asc' => 'hutangPiutang.nomor',
                  'desc' => 'hutangPiutang.nomor desc'
              )
          )
      );

      return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
          'sort' => $sort
      ));
   }

   /**
    * Returns the static model of the specified AR class.
    * Please note that you should have this exact method in all your CActiveRecord descendants!
    * @param string $className active record class name.
    * @return Penjualan the static model class
    */
   public static function model($className = __CLASS__) {
      return parent::model($className);
   }

   public function beforeValidate() {
      $this->profil_id = empty($this->profil_id) ? Profil::PROFIL_UMUM : $this->profil_id;
      return parent::beforeValidate();
   }

   public function beforeSave() {

      if ($this->isNewRecord) {
         $this->created_at = date('Y-m-d H:i:s'); /*
          * Tanggal akan diupdate jika melalui proses simpanPenjualan
          * bersamaan dengan dapat nomor
          */
         $this->tanggal = date('Y-m-d H:i:s');
      }
      $this->updated_at = null; // Trigger current timestamp
      $this->updated_by = Yii::app()->user->id;
      // Jika disimpan melalui proses simpan penjualan
      if ($this->scenario === 'simpanPenjualan') {
         // Status diubah jadi penjualan belum bayar (piutang)
         $this->status = Penjualan::STATUS_PIUTANG;
         // Dapat nomor dan tanggal baru
         $this->tanggal = date('Y-m-d H:i:s');
         $this->nomor = $this->generateNomor();
      }
      return parent::beforeSave();
   }

   public function tambahBarang($barcode, $qty) {
      $transaction = $this->dbConnection->beginTransaction();
      try {
         $barang = Barang::model()->find('barcode=:barcode', array(':barcode' => $barcode));

         /* Jika barang tidak ada */
         if (is_null($barang)) {
            throw new Exception('Barang tidak ditemukan', 500);
         }

         /* Siapkan data untuk diinput */
         $detail = new PenjualanDetail;
         $detail->penjualan_id = $this->id;
         $detail->barang_id = $barang->id;
         $detail->qty = $qty;
         $detail->harga_jual = HargaJual::model()->terkini($barang->id);
         $detail->harga_jual_rekomendasi = HargaJualRekomendasi::model()->terkini($barang->id);

         /* Jika apakah barang sudah ada di detail? */
         $sudahAda = PenjualanDetail::model()->find('barang_id=:barangId AND penjualan_id=:penjualanId', array(
             ':barangId' => $barang->id,
             ':penjualanId' => $this->id
         ));

         /* Jika sudah ada, tambahkan $detail->qty, dan delete $sudahAda */
         if (!is_null($sudahAda)) {
            $detail->qty += $sudahAda->qty;
            $sudahAda->delete();
         }

         /* Coba simpan, jika gagal throw exception */
         if (!$detail->save()) {
            throw new Exception("Gagal simpan penjualan detail: penjualanId:{$this->id}, barangId:{$barang->id}, qty:{$qty}", 500);
         }

         $transaction->commit();
         return array(
             'sukses' => true
         );
      } catch (Exception $ex) {
         $transaction->rollback();
         return array(
             'sukses' => false,
             'error' => array(
                 'msg' => $ex->getMessage(),
                 'code' => $ex->getCode(),
         ));
      }
   }

   /**
    * Mencari nomor untuk penomoran surat
    * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
    */
   public function cariNomor() {
      $tahun = date('y');
      $data = $this->find(array(
          'select' => 'max(substring(nomor,9)*1) as max',
          'condition' => "substring(nomor,5,2)='{$tahun}'")
      );

      $value = is_null($data) ? 0 : $data->max;
      return $value + 1;
   }

   /**
    * Membuat nomor surat
    * @return string Nomor sesuai format "[KodeCabang][kodeDokumen][Tahun][Bulan][SequenceNumber]"
    */
   public function generateNomor() {
      $config = Config::model()->find("nama='kode'");
      $kodeCabang = $config->nilai;
      $kodeDokumen = KodeDokumen::PENJUALAN;
      $kodeTahunBulan = date('ym');
      $sequence = substr('0000'.$this->cariNomor(), -5);
      return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
   }

   /**
    * Total Penjualan
    * @return int total dalam bentuk raw (belum terformat)
    */
   public function ambilTotal() {
      $detail = Yii::app()->db->createCommand()
              ->select('sum(harga_jual * qty) total')
              ->from(PenjualanDetail::model()->tableName())
              ->where('penjualan_id=:penjualanId', array(':penjualanId' => $this->id))
              ->queryRow();
      return $detail['total'];
   }

   /**
    * Total penjualan
    * @return string Total dalam format 0.000
    */
   public function getTotal() {
      return number_format($this->ambilTotal(), 0, ',', '.');
   }

   public function ambilMargin() {
      $command = Yii::app()->db->createCommand();
      $command->select('sum(pd.harga_jual)-sum(hpp.harga_beli) margin');
      $command->from(PenjualanDetail::model()->tableName().' pd');
      $command->join(Penjualan::model()->tableName().' pj', 'pd.penjualan_id=pj.id and pj.id='.$this->id);
      $command->join(HargaPokokPenjualan::model()->tableName().' hpp', 'pd.id=hpp.penjualan_detail_id');

      $penjualan = $command->queryRow();
      return $penjualan['margin'];
   }

   /**
    * Total Margin
    * @return text Total margin dalam format 0.000
    */
   public function getMargin() {
      return number_format($this->ambilMargin(), 0, ',', '.');
   }

   /**
    * Total Profit Margin
    * @return text Total margin dalam persen
    */
   public function getProfitMargin() {
      if ($this->ambilTotal() == 0) {
         return null;
      }
      return number_format($this->ambilMargin() / $this->ambilTotal() * 100, 2, ',', '.');
   }

   /**
    * Proses simpan penjualan.
    * Jika piutang, terbit nota debit (gudang)
    * 
    * Simpan penjualan: 
    * 1. Update status dari draft menjadi piutang.
    * 2. Update stock
    * 3. Catat harga beli dan harga jual
    * 4. Jika stok minus harga beli adalah harga beli terakhir
    * 5. Buat nota debit (piutang)
    * 
    */
   public function simpan() {
      $transaction = $this->dbConnection->beginTransaction();
      $this->scenario = 'simpanPenjualan';
      try {
         if (!$this->save()) {
            throw new Exception('Gagal simpan penjualan', 500);
         }
         $details = PenjualanDetail::model()->findAll('penjualan_id=:penjualanId', array(':penjualanId' => $this->id));
         foreach ($details as $detail) {
            $inventoryTerpakai = InventoryBalance::model()->jual($detail->barang_id, $detail->qty);
            $count = 1;
            foreach ($inventoryTerpakai as $layer) {
               $hpp = new HargaPokokPenjualan;
               $hpp->penjualan_detail_id = $detail->id;
               $hpp->pembelian_detail_id = $layer['pembelianDetailId'];
               $hpp->qty = $layer['qtyTerpakai'];
               $hpp->harga_beli = $layer['hargaBeli'];

               // Jika negatif simpan juga di harga_beli_temp
               // FIX ME, jika pembelian harga beli nya beda
               if (isset($layer['negatif']) && $layer['negatif']) {
                  $hpp->harga_beli_temp = $layer['hargaBeli'];
               }
               if (!$hpp->save()) {
                  throw new Exception("Gagal simpan HPP", 500);
               }
               $count++;
            }
         }

         $jumlahPenjualan = $this->ambilTotal();
         // Buat Hutang Piutang
         $piutang = new HutangPiutang;
         $piutang->profil_id = $this->profil_id;
         $piutang->jumlah = $jumlahPenjualan;
         $piutang->tipe = HutangPiutang::TIPE_PIUTANG;
         $piutang->asal = HutangPiutang::DARI_PENJUALAN;
         $piutang->nomor_dokumen_asal = $this->nomor;
         if (!$piutang->save()) {
            throw new Exception("Gagal simpan piutang", 500);
         }

         /*
          * Piutang Detail
          */
         $piutangDetail = new HutangPiutangDetail;
         $piutangDetail->hutang_piutang_id = $piutang->id;
         $piutangDetail->keterangan = 'Pembelian: '.$this->nomor;
         $piutangDetail->jumlah = $jumlahPenjualan;
         if (!$piutangDetail->save()) {
            throw new Exception("Gagal simpan piutang detail", 500);
         }

         /*
          * Simpan hutang_piutang_id ke penjualan
          */
         if (!Penjualan::model()->updateByPk($this->id, array('hutang_piutang_id' => $piutang->id)) > 1) {
            throw new Exception("Gagal simpan piutang_id", 500);
         }
         $transaction->commit();
         return array(
             'sukses' => true
         );
      } catch (Exception $ex) {
         $transaction->rollback();
         return array(
             'sukses' => false,
             'error' => array(
                 'msg' => $ex->getMessage(),
                 'code' => $ex->getCode(),
         ));
      }
   }

   public function listStatus() {
      return array(
          Penjualan::STATUS_DRAFT => 'Draft',
          Penjualan::STATUS_PIUTANG => 'Piutang',
          Penjualan::STATUS_LUNAS => 'Lunas'
      );
   }

   public function getNamaStatus() {
      $status = $this->listStatus();
      return $status[$this->status];
   }

   public function afterFind() {
      $this->tanggal = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->tanggal), 'd-m-Y H:i:s') : '0';
      return parent::afterFind();
   }

   /**
    * Export data penjualan ke CSV, yang terdiri dari (key value):
    * 1. barcode barcode,
    * 2. idBarang barang_id,
    * 3. namaBarang nama_barang,
    * 4. jumBarang qty,
    * 5. hargaBeli harga_beli, (kosong)
    * 6. hargaJual harga_jual,
    * 7. RRP rrp,
    * 8. SatuanBarang satuan,
    * 9. KategoriBarang kategori,
    * 10. Supplier toko_ini,
    * 11. kasir updated_by,
    * @return string Text dalam bentuk csv
    */
   public function eksporCsv() {
      /*
       * CSV Header, dari ahad POS 2
       */
      $csv = '"barcode","idBarang","namaBarang","jumBarang","hargaBeli","hargaJual","RRP","SatuanBarang","KategoriBarang","Supplier","kasir"'.PHP_EOL;

      /*
       * Ambil data penjualan detail, untuk diexport ke csv
       */
      $details = Yii::app()->db->createCommand("
                    select 
                        pd.barang_id,
                        barang.barcode, 
                        barang.nama nama_barang,
                        pd.qty,						
                        pd.harga_jual,
                        pd.harga_jual_rekomendasi,
                        sb.nama satuan,
                        kb.nama kategori
                    from penjualan_detail pd
                    join barang on barang.id = pd.barang_id
                    join barang_satuan sb on sb.id = barang.satuan_id
                    join barang_kategori kb on kb.id = barang.kategori_id
                    where penjualan_id={$this->id} 
                    order by barang.nama")
              ->queryAll();
      /* Kalau perlu harga beli, tambahkan ini ke sql
       * (
        select case
        when harga_pokok_penjualan.harga_beli is null then harga_pokok_penjualan.harga_beli_temp else harga_pokok_penjualan.harga_beli
        end
        from harga_pokok_penjualan
        where harga_pokok_penjualan.penjualan_detail_id = pd.id
        limit 1
        ) as harga_beli,
       */

      // Cari nama toko ini
      $config = Config::model()->find("nama='toko.nama'");
      foreach ($details as $detail):
         $csv.= "\"{$detail['barcode']}\","
                 ."\"{$detail['barang_id']}\","
                 ."\"{$detail['nama_barang']}\","
                 ."\"{$detail['qty']}\","
                 ."\"\"," // harga beli dikosongkan
                 ."\"{$detail['harga_jual']}\","
                 ."\"{$detail['harga_jual_rekomendasi']}\","
                 ."\"{$detail['satuan']}\","
                 ."\"{$detail['kategori']}\","
                 ."\"{$config->nilai}\"," //nama toko/gudang
                 ."\"{$this->updatedBy->nama}\""
                 .PHP_EOL;
      endforeach;
      return $csv;
   }

   public function toIndoDate($timeStamp) {
      $tanggal = date_format(date_create($timeStamp), 'j');
      $bulan = date_format(date_create($timeStamp), 'n');
      $namabulan = $this->namaBulan($bulan);
      $tahun = date_format(date_create($timeStamp), 'Y');
      return $tanggal.' '.$namabulan.' '.$tahun;
   }

   public function namaBulan($i) {
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

   public function invoiceText($cpi = 15) {
      $lebarKertas = 8; //inchi
      $jumlahKolom = $cpi * $lebarKertas;

      $configs = Config::model()->findAll();
      /*
       * Ubah config (object) jadi array
       */
      $branchConfig = array();
      foreach ($configs as $config) {
         $branchConfig[$config->nama] = $config->nilai;
      }

      $penjualanDetail = Yii::app()->db->createCommand("
         select barang.barcode, barang.nama, pd.qty, pd.harga_jual, pd.harga_jual_rekomendasi
         from penjualan_detail pd
         join barang on pd.barang_id = barang.id
         where pd.penjualan_id = :penjualanId
              ")
              ->bindValue(':penjualanId', $this->id)
              ->queryAll();


      $struk = '';

      $strNomor = 'Nomor       : '.$this->nomor;
      $strTgl = 'Tanggal     : '.$this->toIndoDate($this->tanggal);
      $strTglDue = 'Jatuh Tempo : '.$this->toIndoDate(date('Y-m-d', strtotime("+{$branchConfig['penjualan.jatuh_tempo']} days", strtotime(date_format(date_create_from_format('d-m-Y H:i:s', $this->tanggal), 'Y-m-d')))));
      $strKasir = 'Kasir       : '.ucwords($this->updatedBy->nama);
      $strTotal = 'Total       : '.$this->getTotal();

      $kananMaxLength = strlen($strNomor) > strlen($strTgl) ? strlen($strNomor) : strlen($strTgl);
      /* Jika Nama kasir terlalu panjang, akan di truncate */
      $strKasir = strlen($strKasir) > $kananMaxLength ? substr($strKasir, 0, $kananMaxLength - 2).'..' : $strKasir;

      $strInvoice = 'INVOICE '; //Jumlah karakter harus genap!

      $struk = str_pad($branchConfig['toko.nama'], $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ')
              .$strInvoice.str_pad(str_pad($strNomor, $kananMaxLength, ' '), $jumlahKolom / 2 - strlen($strInvoice) / 2, ' ', STR_PAD_LEFT)
              .PHP_EOL;
      $struk .= str_pad($branchConfig['toko.alamat1'], $jumlahKolom - $kananMaxLength, ' ')
              .str_pad($strTgl, $kananMaxLength, ' ')
              .PHP_EOL;
      $struk .= str_pad($branchConfig['toko.alamat2'], $jumlahKolom - $kananMaxLength, ' ')
              .str_pad($strTglDue, $kananMaxLength, ' ')
              .PHP_EOL;
      $struk .= str_pad($branchConfig['toko.alamat3'], $jumlahKolom - $kananMaxLength, ' ')
              .str_pad($strKasir, $kananMaxLength, ' ')
              .PHP_EOL;
      $struk .= str_pad($strTotal, $jumlahKolom - $kananMaxLength + strlen($strTotal), ' ', STR_PAD_LEFT)
              .PHP_EOL;
//      $struk .= PHP_EOL;

      $struk .= str_pad('', $jumlahKolom, "-").PHP_EOL;
      $textHeader1 = ' No  Barang';
      $textHeader2 = 'RRP      Harga     Qty  Sub Total ';
      $textHeader = $textHeader1.str_pad($textHeader2, $jumlahKolom - strlen($textHeader1), ' ', STR_PAD_LEFT).PHP_EOL;
      $struk .= $textHeader;
      $struk .= str_pad('', $jumlahKolom, "-").PHP_EOL;

      $no = 1;
      foreach ($penjualanDetail as $detail) {
         $strNomor = str_pad($no, 3, ' ', STR_PAD_LEFT).'.';
         $strBarang = str_pad(trim($detail['nama']), 44, ' ');
         $strQty = str_pad($detail['qty'], 6, ' ', STR_PAD_LEFT);
         $strHarga = str_pad(number_format($detail['harga_jual'], 0, ',', '.'), 9, ' ', STR_PAD_LEFT);
         $strHargaJualRekomendasi = str_pad(number_format($detail['harga_jual_rekomendasi'], 0, ',', '.'), 9, ' ', STR_PAD_LEFT);
         $strSubTotal = str_pad(number_format($detail['harga_jual'] * $detail['qty'], 0, ',', '.'), 9, ' ', STR_PAD_LEFT);
         $row1 = $strNomor.' '.$strBarang.' ';
         $row2 = $strHargaJualRekomendasi.'  '.$strHarga.'  '.$strQty.'  '.$strSubTotal;
         $row = $row1.str_pad($row2.' ', $jumlahKolom - strlen($row1), ' ', STR_PAD_LEFT).PHP_EOL;

         $struk .= $row;
         $no++;
      }
      $struk .= str_pad('', $jumlahKolom, "-").PHP_EOL.PHP_EOL;

      $signatureHead1 = '          Diterima';
      $signatureHead2 = 'a.n. '.$branchConfig['toko.nama'];

      $struk .= $signatureHead1.str_pad($signatureHead2, 29 - (strlen($signatureHead2) / 2) + strlen($signatureHead2), ' ', STR_PAD_LEFT).PHP_EOL;
      $struk .= PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;
      $struk .= '     (________________)              (________________)'.PHP_EOL;
      return $struk;
   }

}
