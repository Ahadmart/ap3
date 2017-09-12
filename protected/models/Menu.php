<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id
 * @property string $parent_id
 * @property string $nama
 * @property string $icon
 * @property string $link
 * @property string $keterangan
 * @property integer $urutan
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Menu $parent
 * @property Menu[] $menus
 * @property User $updatedBy
 */
class Menu extends CActiveRecord
{

    const STATUS_UNPUBLISH = 0;
    const STATUS_PUBLISH = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'menu';
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
            ['urutan, status', 'numerical', 'integerOnly' => true],
            ['parent_id, updated_by', 'length', 'max' => 10],
            ['nama', 'length', 'max' => 45],
            ['icon', 'length', 'max' => 100],
            ['link', 'length', 'max' => 512],
            ['keterangan', 'length', 'max' => 30],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, parent_id, nama, icon, link, keterangan, urutan, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'parent' => [self::BELONGS_TO, 'Menu', 'parent_id'],
            'menus' => [self::HAS_MANY, 'Menu', 'parent_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent',
            'nama' => 'Nama',
            'icon' => 'Icon',
            'link' => 'Link',
            'keterangan' => 'Keterangan',
            'urutan' => 'Urutan',
            'status' => 'Status',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('icon', $this->icon, true);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('keterangan', $this->keterangan, true);
        $criteria->compare('urutan', $this->urutan);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Menu the static model class
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
        $this->parent_id = empty($this->parent_id) ? NULL : $this->parent_id;
        $this->icon = empty($this->icon) ? NULL : $this->icon;
        $this->link = empty($this->link) ? NULL : $this->link;
        $this->keterangan = empty($this->keterangan) ? NULL : $this->keterangan;
        // $this->urutan = empty($this->urutan) ? NULL : $this->urutan;
        return parent::beforeValidate();
    }

}
