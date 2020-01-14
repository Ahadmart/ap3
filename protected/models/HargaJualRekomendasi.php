<?php

/**
 * This is the model class for table "barang_harga_jual_rekomendasi".
 *
 * The followings are the available columns in table 'barang_harga_jual_rekomendasi':
 * @property string $id
 * @property string $barang_id
 * @property string $harga
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property User $updatedBy
 */
class HargaJualRekomendasi extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'barang_harga_jual_rekomendasi';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('barang_id, harga', 'required'),
            array('barang_id, updated_by', 'length', 'max' => 10),
            array('harga', 'length', 'max' => 18),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, barang_id, harga, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'barang' => array(self::BELONGS_TO, 'Barang', 'barang_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'barang_id' => 'Barang',
            'harga' => 'Harga',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Sejak',
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
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('harga', $this->harga, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $sort = array(
            'defaultOrder' => 't.id desc'
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
     * @return HargaJualRekomendasi the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {

        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function afterFind() {
        $this->harga = number_format($this->harga, 0, ',', '.');
        $this->created_at = date_format(date_create_from_format('Y-m-d H:i:s', $this->created_at), 'd-m-Y H:i:s');
        return parent::afterFind();
    }


    /**
     * Update Harga Jual, insert harga jual baru jika berbeda dengan harga jual saat ini. Jika update harga jual
     * dengan transaction tersendiri, gunakan updateHargaJualTrx
     * @param int $barangId
     * @param decimal $hargaJual
     * @return boolean False jika tabel nya tidak ada atau gagal simpan ke tabel harga jual
     */
    public function updateHarga($barangId, $hargaJual) {
        $return = false;
        // Cari harga jual terakhir
        $hasil = Yii::app()->db->createCommand()
                ->select('harga')
                ->from($this->tableName())
                ->where('barang_id=:barangId', array(':barangId' => $barangId))
                ->order('id desc')
                ->limit(1, 0)
                ->queryRow();

        if ($hasil && $hasil['harga'] != $hargaJual && !is_null($hargaJual)) {
            // Jika tidak sama atau belum ada maka: insert harga jual baru
            $hargaJualModel = new HargaJualRekomendasi;
            $hargaJualModel->barang_id = $barangId;
            $hargaJualModel->harga = $hargaJual;
            if ($hargaJualModel->save()) {
                $return = true;
            }
        }
        else {
            $return = true;
        }
        return $return;
    }

    /**
     * Wrap updateHargaJual dalam transaction. Diperlukan untuk update harga jual sendiri, tanpa proses yang lain
     * @param int $barangId
     * @param decimal $hargaJual
     * @throws Exception
     */
    public function updateHargaJualTrx($barangId, $hargaJual) {

        $transaction = $this->dbConnection->beginTransaction();
        try {
            if ($this->updateHarga($barangId, $hargaJual)) {
                $transaction->commit();
                return true;
            }
            else {
                throw new Exception("Gagal Update Harga Jual RRP");
            }
        }
        catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

	/**
	 * Harga Jual Rekomendasi (RRP) terakhir/terbaru/terkini
	 * @param int $barangId ID Barang yang akan dicari harga jual rekomendasi (rrp) terkini nya
	 * @return dec Harga jual terakhir
	 */
	public function terkini($barangId) {
		$query = Yii::app()->db->createCommand()
				  ->select('harga')
				  ->from($this->tableName())
				  ->where('id = (select max(id) from '.$this->tableName().' where barang_id = :barangId)')
				  ->bindValues(array(':barangId' => $barangId))
				  ->queryRow();
        return $query ? $query['harga'] : null;
    }
}
