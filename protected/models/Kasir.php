<?php

/**
 * This is the model class for table "kasir".
 *
 * The followings are the available columns in table 'kasir':
 * @property string $id
 * @property string $user_id
 * @property string $device_id
 * @property string $waktu_buka
 * @property string $waktu_tutup
 * @property string $saldo_awal
 * @property string $saldo_akhir_seharusnya
 * @property string $saldo_akhir
 * @property string $total_penjualan
 * @property string $total_margin
 * @property string $total_retur
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Device $device
 * @property User $updatedBy
 * @property User $user
 */
class Kasir extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'kasir';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, device_id, waktu_buka, saldo_awal', 'required', 'message' => '{attribute} harus diisi!'),
            array('user_id, device_id, updated_by', 'length', 'max' => 10),
            array('saldo_awal, saldo_akhir_seharusnya, saldo_akhir, total_penjualan, total_margin, total_retur', 'length', 'max' => 18),
            array('waktu_tutup, created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, device_id, waktu_buka, waktu_tutup, saldo_awal, saldo_akhir_seharusnya, saldo_akhir, total_penjualan, total_margin, total_retur, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'device' => array(self::BELONGS_TO, 'Device', 'device_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'device_id' => 'Device',
            'waktu_buka' => 'Sejak',
            'waktu_tutup' => 'Waktu Tutup',
            'saldo_awal' => 'Saldo Awal',
            'saldo_akhir_seharusnya' => 'Saldo Akhir Seharusnya',
            'saldo_akhir' => 'Saldo Akhir',
            'total_penjualan' => 'Total Penjualan',
            'total_margin' => 'Total Margin',
            'total_retur' => 'Total Retur Jual',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('device_id', $this->device_id, true);
        $criteria->compare('waktu_buka', $this->waktu_buka, true);
        $criteria->compare('waktu_tutup', $this->waktu_tutup, true);
        $criteria->compare('saldo_awal', $this->saldo_awal, true);
        $criteria->compare('saldo_akhir_seharusnya', $this->saldo_akhir_seharusnya, true);
        $criteria->compare('saldo_akhir', $this->saldo_akhir, true);
        $criteria->compare('total_penjualan', $this->total_penjualan, true);
        $criteria->compare('total_margin', $this->total_margin, true);
        $criteria->compare('total_retur', $this->total_retur, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        /* Tampilkan hanya kasir yang masih buka (belum ditutup) */
        $criteria->addCondition('waktu_tutup is null');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Kasir the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {

        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->waktu_buka = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    public function totalPenjualan()
    {
        $command = Yii::app()->db->createCommand("
            select sum(d.jumlah) jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal>=:waktu and penjualan.updated_by=:userId
        ");

        $command->bindValues(array(
            ':waktu' => $this->waktu_buka,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
            ':userId' => $this->user_id
        ));

        return $command->queryRow();
    }

    public function totalMargin()
    {
        $command = Yii::app()->db->createCommand("
            select sum(jual_detail.harga_jual * hpp.qty) - sum(hpp.harga_beli * hpp.qty) jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal>=:waktu and penjualan.updated_by=:userId
            join penjualan_detail jual_detail on penjualan.id = jual_detail.penjualan_id
            join harga_pokok_penjualan hpp on jual_detail.id=hpp.penjualan_detail_id
        ");

        $command->bindValues(array(
            ':waktu' => $this->waktu_buka,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
            ':userId' => $this->user_id
        ));

        return $command->queryRow();
    }

    public function totalReturJual()
    {
        $command = Yii::app()->db->createCommand("
            select sum(d.jumlah) jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id = retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal>=:waktu and retur_penjualan.updated_by=:userId
        ");

        $command->bindValues(array(
            ':waktu' => $this->waktu_buka,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':userId' => $this->user_id
        ));

        return $command->queryRow();
    }

}
