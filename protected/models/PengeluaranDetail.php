<?php

/**
 * This is the model class for table "pengeluaran_detail".
 *
 * The followings are the available columns in table 'pengeluaran_detail':
 * @property string $id
 * @property string $pengeluaran_id
 * @property string $item_id
 * @property string $hutang_piutang_id
 * @property string $keterangan
 * @property string $jumlah
 * @property integer $posisi
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property HutangPiutang $hutangPiutang
 * @property ItemKeuangan $item
 * @property Pengeluaran $pengeluaran
 * @property User $updatedBy
 */
class PengeluaranDetail extends CActiveRecord {

	const POSISI_DEBET = 0;
	const POSISI_KREDIT = 1;

	public $namaItem;
	public $nomorDokumenHutangPiutang;

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'pengeluaran_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			 array('pengeluaran_id, item_id', 'required'),
			 array('posisi', 'numerical', 'integerOnly' => true),
			 array('pengeluaran_id, item_id, hutang_piutang_id, updated_by', 'length', 'max' => 10),
			 array('keterangan', 'length', 'max' => 255),
			 array('jumlah', 'length', 'max' => 18),
			 array('created_at, updated_at, updated_by', 'safe'),
			 // The following rule is used by search().
			 // @todo Please remove those attributes that should not be searched.
			 array('id, pengeluaran_id, item_id, hutang_piutang_id, nomor_dokumen, keterangan, jumlah, posisi, updated_at, updated_by, created_at, namaItem, nomorDokumenHutangPiutang', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			 'item' => array(self::BELONGS_TO, 'ItemKeuangan', 'item_id'),
			 'pengeluaran' => array(self::BELONGS_TO, 'Pengeluaran', 'pengeluaran_id'),
			 'hutangPiutang' => array(self::BELONGS_TO, 'HutangPiutang', 'hutang_piutang_id'),
			 'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			 'id' => 'ID',
			 'pengeluaran_id' => 'Pengeluaran',
			 'item_id' => 'Item',
			 'hutang_piutang_id' => 'Hutang Piutang',
			 'keterangan' => 'Keterangan',
			 'jumlah' => 'Jumlah',
			 'posisi' => 'Posisi',
			 'updated_at' => 'Updated At',
			 'updated_by' => 'Updated By',
			 'created_at' => 'Created At',
			 'namaItem' => 'Item',
			 'nomorDokumenHutangPiutang' => 'Nomor Dokumen'
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
		$criteria->compare('pengeluaran_id', $this->pengeluaran_id, true);
		$criteria->compare('item_id', $this->item_id, true);
		$criteria->compare('hutang_piutang_id', $this->hutang_piutang_id, true);
		$criteria->compare('t.keterangan', $this->keterangan, true);
		$criteria->compare('t.jumlah', $this->jumlah, true);
		$criteria->compare('posisi', $this->posisi);
		$criteria->compare('updated_at', $this->updated_at, true);
		$criteria->compare('updated_by', $this->updated_by, true);
		$criteria->compare('created_at', $this->created_at, true);

		$criteria->with = array('item', 'hutangPiutang');
		$criteria->compare('item.nama', $this->namaItem, true);
		$criteria->compare('hutangPiutang.nomor', $this->nomorDokumenHutangPiutang, true);

		$sort = array(
			 'defaultOrder' => 't.id desc',
			 'attributes' => array(
				  '*',
				  'namaItem' => array(
						'asc' => 'item.nama',
						'desc' => 'item.nama desc'
				  ),
				  'nomorDokumenHutangPiutang' => array(
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
	 * @return PengeluaranDetail the static model class
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
		$this->jumlah = $this->posisi == 1 ? -$this->jumlah : $this->jumlah;
		return parent::afterFind();
	}

	public function beforeValidate() {
		// $this->item_id = empty($this->item_id) ? null : $this->item_id;
		$this->hutang_piutang_id = empty($this->hutang_piutang_id) ? null : $this->hutang_piutang_id;
		$this->posisi = $this->cariPosisi();
		return parent::beforeValidate();
	}

	public function cariPosisi() {
		$itemKeu = ItemKeuangan::model()->findByPk($this->item_id);
		return $itemKeu->jenis == ItemKeuangan::ITEM_PENGELUARAN ? self::POSISI_DEBET : self::POSISI_KREDIT;
	}

}
