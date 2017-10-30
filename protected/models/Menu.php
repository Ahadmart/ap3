<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id
 * @property string $parent_id
 * @property string $root_id
 * @property string $nama
 * @property string $icon
 * @property string $link
 * @property string $keterangan
 * @property integer $level
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
    const STATUS_RESERVE = 2;

    public $parentNama;

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
            ['level, urutan, status', 'numerical', 'integerOnly' => true],
            ['parent_id, root_id, updated_by', 'length', 'max' => 10],
            ['nama', 'length', 'max' => 128],
            ['icon, link, keterangan', 'length', 'max' => 512],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, parent_id, root_id, nama, icon, link, keterangan, level, urutan, status, updated_at, updated_by, created_at, parentNama', 'safe', 'on' => 'search'],
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
            'level' => 'Level',
            'urutan' => 'Urutan',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'parentNama' => 'Parent'
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
    public function search($subMenu = false)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);

        // Jika parent_id = NULL dan bukan $subMenu, maka tambahkan kondisi (WHERE)
//        if (!empty($this->parent_id)) {
//            $criteria->compare('parent_id', $this->parent_id);
//        } else {
//            if ($subMenu) {
//                $criteria->compare('parent_id', $this->parent_id);
//            } else {
//                $criteria->addCondition('parent_id IS NULL');
//            }
//        }

        if (empty($this->parent_id) && !($subMenu)) {
            $criteria->addCondition('t.parent_id IS NULL');
        } else {
            $criteria->compare('t.parent_id', $this->parent_id);
        }
        $criteria->compare('t.root_id', $this->root_id);
        $criteria->compare('t.nama', $this->nama, true);
        $criteria->compare('t.icon', $this->icon, true);
        $criteria->compare('t.link', $this->link, true);
        $criteria->compare('t.keterangan', $this->keterangan, true);
        $criteria->compare('t.level', $this->level);
        $criteria->compare('t.urutan', $this->urutan);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['parent'];
        $criteria->compare('parent.nama', $this->parentNama, true);

        $sort = [
            'attributes' => [
                '*',
                'parentNama' => [
                    'asc' => 'parent.nama',
                    'desc' => 'parent.nama desc'
                ]
            ]
        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => $sort
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
        $this->level = empty($this->parent_id) ? 0 : $this->parent->level + 1;
        // $this->urutan = empty($this->urutan) ? NULL : $this->urutan;
        return parent::beforeValidate();
    }

    public function getListChild()
    {
        return $this->getListChildF($this->id);
    }

    public function getTreeListChild()
    {
        return $this->getListChildR($this->id);
    }

    private function _buatNama($nama, $level)
    {
        $r = '';
        for ($i = 1; $i < $level; $i++) {
            $r .= '⇥'; // &#8677;
        }
        return $r . $nama;
    }

    /**
     * Daftar Sub Menu Recursive
     * @param int $parentId Parent ID dari Sub Menu
     * @return array Hasil dalam array 1 dimensi (tidak ada level/sub menu)
     */
    public function getListChildF($parentId)
    {
        $query = "SELECT id, nama, level FROM menu WHERE parent_id=:parentId";
        $command = Yii::app()->db->createCommand($query);
        $command->bindValue(':parentId', $parentId);
        $r = $command->queryAll();
        $result = [];
        $sub = NULL;
        if (!empty($r)) {
            foreach ($r as $row) {
                if ($row['nama'] != '-') {
                    $result[$row['id']] = $this->_buatNama($row['nama'], $row['level']);
                }
                $sub = $this->getListChildF($row['id']);
                if (!empty($sub)) {
                    $result = $result + $sub;
                }
            }
        }
        return $result;
    }

    /**
     * Daftar Sub Menu Recursive
     * @param int $parentId Parent ID dari Sub Menu
     * @return array Hasil dalam array multi dimensi (ada level/sub menu)
     */
    public function getListChildR($parentId)
    {
        $query = "
            SELECT 
                id,
                nama,
                link,
                CONCAT(IFNULL(CONCAT(icon, ' '), ''), nama) label
            FROM
                menu
            WHERE
                parent_id = :parentId AND status = :status
            ORDER BY urutan              
            ";
        $command = Yii::app()->db->createCommand($query);
        $command->bindValues([':parentId' => $parentId, ':status' => self::STATUS_PUBLISH]);
        $r = $command->queryAll();
        $result = [];
        foreach ($r as $row) {
            $result[$row['id']] = [
                'id' => $row['id'],
                'nama' => $row['nama'],
                'label' => $row['label'],
                'url' => empty($row['link']) ? '' : [$row['link']],
                'items' => $this->getListChildR($row['id'])
            ];
        }
        return $result;
    }

    public static function listStatus()
    {
        return [
            self::STATUS_PUBLISH => 'Aktif',
            self::STATUS_UNPUBLISH => 'Tidak Aktif'
        ];
    }

    public function getNamaStatus()
    {
        return $this->listStatus()[$this->status];
    }

    public function getNamaParent()
    {
        return is_null($this->parent) ? '' : $this->parent->nama;
    }

    public function listMenuRoot()
    {
        $list = Yii::app()->db->createCommand()->
                select("id, concat(nama, ' | ',keterangan) nama_menu")->
                from($this->tableName())->
                where("parent_id is null and status=:publish", [':publish' => Menu::STATUS_PUBLISH])->
                order('nama')->
                queryAll();
        return CHtml::listData($list, 'id', 'nama_menu');
    }

    public function getDeskripsi()
    {
        return $this->nama . ' | ' . $this->keterangan;
    }

    public function listMenuRootSimple()
    {
        $list = Yii::app()->db->createCommand()->
                select("id, nama nama_menu")->
                from($this->tableName())->
                where("parent_id is null and status=:publish", [':publish' => Menu::STATUS_PUBLISH])->
                order('nama')->
                queryAll();
        return CHtml::listData($list, 'id', 'nama_menu');
    }

}
