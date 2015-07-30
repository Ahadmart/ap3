<?php

/**
 * This is the model class for table "retur_pembelian".
 *
 * The followings are the available columns in table 'retur_pembelian':
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
 * @property Profil $profil
 * @property User $updatedBy
 * @property ReturPembelianDetail[] $returPembelianDetails
 */
class ReturPembelian extends CActiveRecord {

   const STATUS_DRAFT = 0;
   const STATUS_PIUTANG = 1;
   const STATUS_LUNAS = 2;

   public $namaSupplier;
   public $max; // Untuk mencari untuk nomor surat;

   /**
    * @return string the associated database table name
    */

   public function tableName() {
      return 'retur_pembelian';
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
          array('created_at, tanggal, updated_at, updated_by', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, nomor, tanggal, profil_id, hutang_piutang_id, status, updated_at, updated_by, created_at, namaSupplier', 'safe', 'on' => 'search'),
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
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
          'returPembelianDetails' => array(self::HAS_MANY, 'ReturPembelianDetail', 'retur_pembelian_id'),
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
          'hutang_piutang_id' => 'Hutang Piutang',
          'status' => 'Status',
          'updated_at' => 'Updated At',
          'updated_by' => 'Updated By',
          'created_at' => 'Created At',
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
      $criteria->compare('nomor', $this->nomor, true);
      $criteria->compare('tanggal', $this->tanggal, true);
      $criteria->compare('profil_id', $this->profil_id, true);
		$criteria->compare('hutang_piutang_id',$this->hutang_piutang_id,true);
      $criteria->compare('status', $this->status);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      $criteria->with = array('profil');
      $criteria->compare('profil.nama', $this->namaSupplier, true);

      $sort = array(
          'defaultOrder' => 't.status, t.tanggal desc',
          'attributes' => array(
              'namaSupplier' => array(
                  'asc' => 'profil.nama',
                  'desc' => 'profil.nama desc'
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
    * @return ReturPembelian the static model class
    */
   public static function model($className = __CLASS__) {
      return parent::model($className);
   }

   public function beforeSave() {

      if ($this->isNewRecord) {
         $this->created_at = date('Y-m-d H:i:s');
         /*
          * Tanggal akan diupdate jika melalui proses simpanReturBeli
          * bersamaan dengan dapat nomor
          */
         $this->tanggal = date('Y-m-d H:i:s');
      }
      $this->updated_at = null; // Trigger current timestamp
      $this->updated_by = Yii::app()->user->id;

      // Jika disimpan melalui proses simpan retur pembelian
      if ($this->scenario === 'simpanReturBeli') {
         // Status diubah jadi pembelian belum terima (piutang)
         $this->status = ReturPembelian::STATUS_PIUTANG;
         // Dapat nomor dan tanggal
         $this->nomor = $this->generateNomor();
         $this->tanggal = date('Y-m-d H:i:s');
      }
      return parent::beforeSave();
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
      $kodeDokumen = KodeDokumen::RETUR_PEMBELIAN;
      $kodeTahunBulan = date('ym');
      $sequence = substr('0000'.$this->cariNomor(), -5);
      return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
   }

   public function getNamaStatus() {
      $status = array(
          ReturPembelian::STATUS_DRAFT => 'Draft',
          ReturPembelian::STATUS_PIUTANG => 'Piutang',
          ReturPembelian::STATUS_LUNAS => 'Lunas'
      );
      return $status[$this->status];
   }

   /*
    * Mengembalikan nilai total
    */

   public function ambilTotal() {
      $pembelian = Yii::app()->db->createCommand("select sum(rpd.qty * invbalance.harga_beli) total
										from retur_pembelian_detail rpd
										join inventory_balance invbalance on invbalance.id = rpd.inventory_balance_id
										where retur_pembelian_id = :returPembelianId")
              ->bindValue(':returPembelianId', $this->id)
              ->queryRow();
      return $pembelian['total'];
   }

   /*
    * Mengambil nilai total retur pembelian
    */

   public function getTotal() {
      return number_format($this->ambilTotal(), 0, ',', '.');
   }

   /*
    * Simpan Retur Pembelian
    * 1. Ubah status jadi piutang
    * 2. Kurangi Inventory Balance
    * 3. Buat Piutang
    */

   public function simpanReturPembelian() {
      $this->scenario = 'simpanReturBeli';
      $transaction = $this->dbConnection->beginTransaction();
      try {
         /*
          * Save sekaligus mengubah status dari draft jadi piutang
          */
         if ($this->save()) {
            $details = ReturPembelianDetail::model()->findAll("retur_pembelian_id={$this->id}");
            if (is_null($details)) {
               throw new Exception("Tidak ada detail");
            }
            foreach ($details as $detail):
               $inventoryTerpakai = InventoryBalance::model()->returBeli($detail);
               $count = 1;
               foreach ($inventoryTerpakai as $layer) {
                  if ($count > 1) {
                     $detailBaru = new ReturPembelianDetail;
                     $detailBaru->retur_pembelian_id = $this->id;
                     $detailBaru->inventory_balance_id = $layer['id'];
                     $detailBaru->qty = $layer['qtyTerpakai'];
                     if (!$detailBaru->save()) {
                        throw new Exception('Gagal simpan retur pembelian detail (layer baru)', 500);
                     }
                  } else {
                     $detail->qty = $layer['qtyTerpakai'];
                     if (!$detail->save()) {
                        throw new Exception("Gagal simpan detail", 500);
                     }
                  }
                  $count++;
               }
            endforeach;

            $jumlahReturBeli = $this->ambilTotal();
            /*
             * Create (piutang)
             */
            $hutang = new HutangPiutang;
            $hutang->profil_id = $this->profil_id;
            $hutang->jumlah = $jumlahReturBeli;
            $hutang->tipe = HutangPiutang::TIPE_PIUTANG;
            $hutang->asal = HutangPiutang::DARI_RETUR_BELI;
            $hutang->nomor_dokumen_asal = $this->nomor;
            if (!$hutang->save()) {
               throw new Exception("Gagal simpan hutang");
            }

            /*
             * Hutang Detail
             */
            $hutangDetail = new HutangPiutangDetail;
            $hutangDetail->hutang_piutang_id = $hutang->id;
            $hutangDetail->keterangan = 'Retur Beli: '.$this->nomor;
            $hutangDetail->jumlah = $jumlahReturBeli;
            if (!$hutangDetail->save()) {
               throw new Exception("Gagal simpan hutang detail");
            }

				/*
				 * Simpan hutang_id ke retur pembelian
				 */
				if (!ReturPembelian::model()->updateByPk($this->id, array('hutang_piutang_id' => $hutang->id)) > 1) {
					throw new Exception("Gagal simpan hutang_id");
				}
            
            $transaction->commit();
            return true;
         } else {
            throw new Exception("Gagal Simpan Retur Pembelian");
         }
      } catch (Exception $e) {
         $transaction->rollback();
         throw $e;
      }
   }

   public function afterFind() {
      $this->tanggal = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->tanggal), 'd-m-Y H:i:s') : '0';
      return parent::afterFind();
   }

}
