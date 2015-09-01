<?php

/**
 * This is the model class for table "pembelian".
 *
 * The followings are the available columns in table 'pembelian':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property string $referensi
 * @property string $tanggal_referensi
 * @property string $hutang_piutang_id
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Profil $profil
 * @property HutangPiutang $hutangPiutang
 * @property User $updatedBy
 * @property PembelianDetail[] $pembelianDetails
 */
class Pembelian extends CActiveRecord {

   const STATUS_DRAFT = 0;
   const STATUS_HUTANG = 1;
   const STATUS_LUNAS = 2;

   public $totalPembelian;
   public $namaSupplier;
   public $max; // Untuk mencari untuk nomor surat;
   public $nomorHutang;

   /**
    * @return string the associated database table name
    */
   public function tableName() {
      return 'pembelian';
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
          array('nomor, referensi', 'length', 'max' => 45),
          array('profil_id, hutang_piutang_id, updated_by', 'length', 'max' => 10),
          array('tanggal_referensi, created_at, updated_at, updated_by, tanggal', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, nomor, tanggal, profil_id, referensi, tanggal_referensi, hutang_piutang_id, status, updated_at, updated_by, created_at, namaSupplier, nomorHutang', 'safe', 'on' => 'search'),
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
          'profil' => array(self::BELONGS_TO, 'Profil', 'profil_id'),
          'hutangPiutang' => array(self::BELONGS_TO, 'HutangPiutang', 'hutang_piutang_id'),
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
          'pembelianDetails' => array(self::HAS_MANY, 'PembelianDetail', 'pembelian_id'),
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
          'profil_id' => 'Supplier',
          'referensi' => 'Referensi',
          'tanggal_referensi' => 'Tanggal Referensi',
          'hutang_piutang_id' => 'Hutang Piutang',
          'status' => 'Status',
          'updated_at' => 'Updated At',
          'updated_by' => 'Updated By',
          'created_at' => 'Created At',
          'nomorHutang' => 'Nomor Hutang'
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
      $criteria->compare('referensi', $this->referensi, true);
      $criteria->compare('tanggal_referensi', $this->tanggal_referensi, true);
      $criteria->compare('hutang_piutang_id', $this->hutang_piutang_id, true);
      $criteria->compare('t.status', $this->status);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      $criteria->with = array('profil', 'hutangPiutang');
      $criteria->compare('profil.nama', $this->namaSupplier, true);
      $criteria->compare('hutangPiutang.nomor', $this->nomorHutang, true);

      $sort = array(
          'defaultOrder' => 't.status, t.tanggal desc',
          'attributes' => array(
              'namaSupplier' => array(
                  'asc' => 'profil.nama',
                  'desc' => 'profil.nama desc'
              ),
              'nomorHutang' => array(
                  'asc' => 'hutangPiutang.nomor',
                  'desc' => 'hutangPiutang.nomor desc'
              ),
              '*'
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
    * @return Pembelian the static model class
    */
   public static function model($className = __CLASS__) {
      return parent::model($className);
   }

   public function beforeSave() {

      if ($this->isNewRecord) {
         $this->created_at = date('Y-m-d H:i:s');
         /*
          * Tanggal akan diupdate jika melalui proses simpanPembelian
          * bersamaan dengan dapat nomor
          */
         $this->tanggal = date('Y-m-d H:i:s');
      }
      $this->updated_at = null; // Trigger current timestamp
      $this->updated_by = Yii::app()->user->id;

      // Jika disimpan melalui proses simpan pembelian
      if ($this->scenario === 'simpanPembelian') {
         // Status diubah jadi pembelian belum bayar (hutang)
         $this->status = Pembelian::STATUS_HUTANG;
         // Dapat nomor dan tanggal
         $this->tanggal = date('Y-m-d H:i:s');
         $this->nomor = $this->generateNomor();
      }

      return parent::beforeSave();
   }

   public function beforeValidate() {
      $this->tanggal_referensi = !empty($this->tanggal_referensi) ? date_format(date_create_from_format('d-m-Y', $this->tanggal_referensi), 'Y-m-d') : NULL;
      return parent::beforeValidate();
   }

   public function afterFind() {
      $this->tanggal = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->tanggal), 'd-m-Y H:i:s') : '0';
      $this->tanggal_referensi = !is_null($this->tanggal_referensi) ? date_format(date_create_from_format('Y-m-d', $this->tanggal_referensi), 'd-m-Y') : '';
      return parent::afterFind();
   }

   /**
    * Ambil data barang. nama, barcode, satuan, harga_beli (harga beli terakhir), harga_jual (harga jual terakhir),
    * rrp (Rrp terakhir)
    * @param int $id id barang
    * @return mixed 1 row dalam array, false jika tidak ketemu
    */
   public function ambilDataBarang($id) {
      return Yii::app()->db->createCommand("
							select
								b.nama,
								b.barcode,
								sb.nama satuan,
								(select harga_beli from pembelian_detail where barang_id=:barangId
								order by id desc limit 1) harga_beli,
								(select harga
								from barang_harga_jual
								where barang_id =:barangId
								order by id desc limit 1) harga_jual,
								(select harga
								from barang_harga_jual_rekomendasi
								where barang_id =:barangId
								order by id desc limit 1) rrp
							from barang b
							left join barang_satuan sb on sb.id = b.satuan_id
							where b.id=:barangId
							")
                      ->bindParam(':barangId', $id)
                      ->queryRow();
   }

   /**
    * Total Pembelian
    * @return int Nilai Total
    */
   public function ambilTotal() {
      $pembelian = Yii::app()->db->createCommand()
              ->select('sum(harga_beli * qty) total')
              ->from(PembelianDetail::model()->tableName())
              ->where('pembelian_id=:pembelianId', array(':pembelianId' => $this->id))
              ->queryRow();
      return $pembelian['total'];
   }

   /**
    * Nilai total pembelian
    * @return text Total Pembelian dalam format ribuan
    */
   public function getTotal() {
      return number_format($this->ambilTotal(), 0, ',', '.');
   }

   /**
    * Simpan pembelian:
    * 1. update status dari draft menjadi pembelian.
    * 2. update stock.
    * 3. update harga jual & rrp
    * 4. create hutang.
    * 5. update stok minus. BELUM !!
    * @return boolean True jika proses berhasil
    * @throws Exception
    */
   public function simpanPembelian() {
      $this->scenario = 'simpanPembelian';
      $transaction = $this->dbConnection->beginTransaction();

      /* Uncomment untuk jumlah pembelian yang sangat banyak, misal: init data */
      // ini_set('memory_limit', '-1');
      // set_time_limit(0);

      try {
         if ($this->save()) {
            /*
             * Ambil data barang, dan data inventory terakhir/terbaru nya
             * Jika inventory tidak ada, maka ib.* nilainya null
             */
            $details = PembelianDetail::model()->findAll('pembelian_id=:pembelianId', array(':pembelianId' => $this->id));
            foreach ($details as $detail) {
               /* Untuk setiap barang yang dibeli */

               /* Sesuaikan inventory */
               $inventoryBalance = new InventoryBalance();
               $inventoryBalance->beli(InventoryBalance::ASAL_PEMBELIAN, $this->nomor, $detail->id, $detail->barang_id, $detail->harga_beli, $detail->qty);

               /*
                * Update harga jual
                */
               if (!HargaJual::model()->updateHarga($detail->barang_id, $detail->harga_jual)) {
                  throw new Exception("Gagal Update Harga Jual");
               }
               /*
                * Update Rrp
                */
               if (!HargaJualRekomendasi::model()->updateHarga($detail->barang_id, $detail->harga_jual_rekomendasi)) {
                  throw new Exception("Gagal Update RRP");
               }
            }

            // Total dari pembelian barang
            // Jika nanti ada item lain, misal: Transport, pajak, dll
            // ditambahkan di sini
            $jumlahPembelian = $this->ambilTotal();
            /*
             * Create (hutang)
             */
            $hutang = new HutangPiutang;
            $hutang->profil_id = $this->profil_id;
            $hutang->jumlah = $jumlahPembelian;
            $hutang->tipe = HutangPiutang::TIPE_HUTANG;
            $hutang->asal = HutangPiutang::DARI_PEMBELIAN;
            $hutang->nomor_dokumen_asal = $this->nomor;
            if (!$hutang->save()) {
               throw new Exception("Gagal simpan hutang");
            }

            /*
             * Hutang Detail
             */
            $hutangDetail = new HutangPiutangDetail;
            $hutangDetail->hutang_piutang_id = $hutang->id;
            $hutangDetail->keterangan = 'Pembelian: '.$this->nomor;
            $hutangDetail->jumlah = $jumlahPembelian;
            if (!$hutangDetail->save()) {
               throw new Exception("Gagal simpan hutang detail");
            }

            /*
             * Simpan hutang_id ke pembelian
             */
            if (!Pembelian::model()->updateByPk($this->id, array('hutang_piutang_id' => $hutang->id)) > 1) {
               throw new Exception("Gagal simpan hutang_id");
            }

            $transaction->commit();
            return array('sukses' => true);
         } else {
            throw new Exception("Gagal Simpan Pembelian");
         }
      } catch (Exception $ex) {
         $transaction->rollback();
         // throw $up;

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
      $config = Config::model()->find("nama='toko.kode'");
      $kodeCabang = $config->nilai;
      $kodeDokumen = KodeDokumen::PEMBELIAN;
      $kodeTahunBulan = date('ym');
      $sequence = substr('0000'.$this->cariNomor(), -5);
      return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
   }

   public function getNamaStatus() {
      $status = array(
          Pembelian::STATUS_DRAFT => 'Draft',
          Pembelian::STATUS_HUTANG => 'Hutang',
          Pembelian::STATUS_LUNAS => 'Lunas'
      );
      return $status[$this->status];
   }

}
