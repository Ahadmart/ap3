<?php

/**
 * This is the model class for table "theme".
 *
 * The followings are the available columns in table 'theme':
 * @property integer $id
 * @property string $nama
 * @property string $deskripsi
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 */
class Theme extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'theme';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nama', 'required'),
            array('nama', 'length', 'max' => 255),
            array('deskripsi', 'length', 'max' => 500),
            array('updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nama, deskripsi, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
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
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nama' => 'Nama',
            'deskripsi' => 'Deskripsi',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('deskripsi', $this->deskripsi, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Theme the static model class
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
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    /**
     * Daftar theme yang ada di database
     * @return array pasangan id, nama theme (kecuali default karena belum jadi.. FIXME)
     */
    public function listTheme()
    {
        $list = Yii::app()->db->createCommand()->
                //select("id, concat(nama, ' (', deskripsi,')') nama_theme")->
                select("id, deskripsi nama_theme")->
                from($this->tableName())->
                where("nama != 'default'")->
                order('nama')->
                queryAll();
        return CHtml::listData($list, 'id', 'nama_theme');
    }

    /**
     * Menyimpan theme saat ini ke cookies. Agar ketika login, menampilkan
     * theme terakhir
     */
    public function toCookies()
    {
        Yii::app()->request->cookies['theme'] = new CHttpCookie('theme', $this->id, ['expire' => time() + 60 * 60 * 24 * 30]);
    }

    /**
     * Get Theme ID from cookies
     * @return int Theme ID
     */
    public function getCookies()
    {
        return !empty(Yii::app()->request->cookies['theme']->value) ? (string) Yii::app()->request->cookies['theme'] : null;
    }

}
