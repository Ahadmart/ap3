<?php

/**
 * This is the model class for table "barang_struktur".
 *
 * The followings are the available columns in table 'barang_struktur':
 * @property string $id
 * @property string $parent_id
 * @property string $kode
 * @property string $nama
 * @property integer $level
 * @property integer $urutan
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property StrukturBarang $parent
 * @property StrukturBarang[] $barangStrukturs
 * @property User $updatedBy
 */
class StrukturBarang extends CActiveRecord
{

    const STATUS_UNPUBLISH = 0;
    const STATUS_PUBLISH   = 1;
    const STATUS_RESERVE   = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'barang_struktur';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['nama', 'required'],
            ['level, urutan, status', 'numerical', 'integerOnly' => true],
            ['parent_id, updated_by', 'length', 'max' => 10],
            ['kode, nama', 'length', 'max' => 128],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, parent_id, kode, nama, level, urutan, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'parent'          => [self::BELONGS_TO, 'StrukturBarang', 'parent_id'],
            'barangStrukturs' => [self::HAS_MANY, 'StrukturBarang', 'parent_id'],
            'updatedBy'       => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'parent_id'  => 'Parent',
            'kode'       => 'Kode',
            'nama'       => 'Nama',
            'level'      => 'Level',
            'urutan'     => 'Urutan',
            'status'     => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
        ];
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

        $criteria->compare('id', $this->id);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('kode', $this->kode, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('urutan', $this->urutan);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $sort = [
            'defaultOrder' => 'urutan'
        ];

        return new CActiveDataProvider($this, [
            'criteria'   => $criteria,
            'sort'       => $sort,
            'pagination' => false,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StrukturBarang the static model class
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
        $this->updated_at = date('Y-m-d H:i:s');
        // Model ini juga diakses oleh console app (syncstruktur: sync struktur dari DC)
        // update pertama untuk console, jika lewat web app: update dengan user yang login
        $this->updated_by = 1;
        if (Yii::app() instanceof CWebApplication) {
            $this->updated_by = Yii::app()->user->id;
        }
        return parent::beforeSave();
    }

    public function scopes()
    {
        return [
            'aktif' => [
                'condition' => 'status = ' . self::STATUS_PUBLISH
            ]
        ];
    }

    public static function listStatus()
    {
        return [
            self::STATUS_PUBLISH   => 'Aktif',
            self::STATUS_UNPUBLISH => 'Tidak Aktif'
        ];
    }

    public function getNamaStatus()
    {
        return $this->listStatus()[$this->status];
    }

    public function getFullPath()
    {
        $text      = $this->nama;
        $separator = ">"; //"&raquo";
        $i         = $this->level;
        $struktur  = $this;
        while ($i > 1) {
            $struktur = self::model()->findByPk($struktur->parent_id);
            $text     = $struktur->nama . " " . $separator . " " . $text;
            $i--;
        }
        return $text;
    }

    public static function listStrukLv1()
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'level=1 AND status=:publish';
        $criteria->params    = [':publish' => self::STATUS_PUBLISH];
        $criteria->order     = 'nama';

        return ['' => '[SEMUA]'] + CHtml::listData(self::model()->findAll($criteria), 'id', 'nama');
    }

    public static function listStrukLv2($parentId)
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'level=2 AND status=:publish AND parent_id=:parentId';
        $criteria->params    = [
            ':publish'  => self::STATUS_PUBLISH,
            ':parentId' => $parentId,
        ];
        $criteria->order = 'nama';

        return ['' => '[SEMUA]'] + CHtml::listData(self::model()->findAll($criteria), 'id', 'nama');
    }

    public static function listStrukLv3($parentId)
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'level=3 AND status=:publish AND parent_id=:parentId';
        $criteria->params    = [
            ':publish'  => self::STATUS_PUBLISH,
            ':parentId' => $parentId,
        ];
        $criteria->order = 'nama';

        return ['' => '[SEMUA]'] + CHtml::listData(self::model()->findAll($criteria), 'id', 'nama');
    }

    public static function listChildStruk($id)
    {
        $criteria = new CDbCriteria();
        if (empty($id)) {
            $criteria->condition = 'status=:publish AND parent_id IS NULL';
            $criteria->params    = [
                ':publish' => StrukturBarang::STATUS_PUBLISH,
            ];
        } else {
            $criteria->condition = 'status=:publish AND parent_id=:id';
            $criteria->params    = [
                ':publish' => StrukturBarang::STATUS_PUBLISH,
                ':id'      => $id,
            ];
        }
        $criteria->order = 'nama';

        $childStruk = StrukturBarang::model()->findAll($criteria);

        $r = [];
        foreach ($childStruk as $struk) {
            $r[] = $struk->id;
        }
        return $r;
    }
}
