<?php

/**
 * This is the model class for table "tag_barang".
 *
 * The followings are the available columns in table 'tag_barang':
 * @property string $id
 * @property string $tag_id
 * @property string $barang_id
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Tag $tag
 * @property User $updatedBy
 */
class TagBarang extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tag_barang';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tag_id, barang_id', 'required'),
            array('tag_id, barang_id, updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, tag_id, barang_id, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
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
            'barang' => array(self::BELONGS_TO, 'Barang', 'barang_id'),
            'tag' => array(self::BELONGS_TO, 'Tag', 'tag_id'),
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
            'tag_id' => 'Tag',
            'barang_id' => 'Barang',
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
        $criteria->compare('tag_id', $this->tag_id, true);
        $criteria->compare('barang_id', $this->barang_id, true);
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
     * @return TagBarang the static model class
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

    /**
     * Update Tag Barang
     * @param int $barangId ID Barang yang akan diupdate tags nya
     * @param array $tags Bisa berupa nama atau int (id)
     */
    public function updateTags($barangId, $tags)
    {
        TagBarang::model()->deleteAll('barang_id=:barangId', [':barangId' => $barangId]);

        foreach ($tags as $tag) {
            if ($tag > 0) {
                $barangTag = new TagBarang;
                $barangTag->barang_id = $barangId;
                $barangTag->tag_id = $tag;
                $barangTag->save();
            } else {
                $produkTag = Tag::model()->find('nama=:namaTag', [':namaTag' => $tag]);
                if ($produkTag) {
                    $tagId = $produkTag->id;
                } else {
                    $produkTag = new Tag;
                    $produkTag->nama = $tag;
                    $produkTag->save();
                    $tagId = $produkTag->id;
                }
                //if (!is_null($tagId)) {
                $barangTag = new TagBarang;
                $barangTag->barang_id = $barangId;
                $barangTag->tag_id = $tagId;
                if (!$barangTag->save()) {
                    echo 'Gagal simpan itemTag. TagId=' . $produkTag->id . '. ItemId=' . $barangId . '\n';
                }
            }
        }
    }

}
