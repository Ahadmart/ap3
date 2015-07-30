<?php

/**
 * This is the model class for table "AuthItem".
 *
 * The followings are the available columns in table 'AuthItem':
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 *
 * The followings are the available model relations:
 * @property AuthAssignment[] $authAssignments
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren1
 */
class AuthItem extends CActiveRecord {
//	public $authTypeName;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'AuthItem';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, type', 'required'),
            array('type', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 64),
            array('description, bizrule, data', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('name, type, description, bizrule, data', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authAssignments' => array(self::HAS_MANY, 'AuthAssignment', 'itemname'),
            'authItemChildren' => array(self::HAS_MANY, 'AuthItemChild', 'parent'),
            'authItemChildren1' => array(self::HAS_MANY, 'AuthItemChild', 'child'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'name' => 'Nama',
            'type' => 'Tipe',
            'description' => 'Keterangan',
            'bizrule' => 'Bizrule',
            'data' => 'Data',
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

        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('bizrule', $this->bizrule, true);
        $criteria->compare('data', $this->data, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'type desc, name'
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AuthItem the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function primaryKey() {
        return 'name';
        // For composite primary key, return an array like the following
        // return array('pk1', 'pk2');
    }

    public function getAuthTypeName() {
        $type = array('0' => 'Operation', '1' => 'Task', '2' => 'Role');
        return $type[$this->type];
    }

    public function listAuthItem($type, $name) {
        return Yii::app()->db->createCommand()
                        ->selectDistinct('name')
                        ->from($this->tableName())
                        ->where("name not in(
									  select distinct name
									  from AuthItem as item
									  left join AuthItemChild as c on c.child = item.name
									  left join AuthItemChild as p on p.parent = item.name
									  where c.parent=:name or p.child=:name )
									  and name!=:name and type=:type", array(':name' => $name, ':type' => $type)
                        )
                        ->order('name')
                        ->query()
                        ->readAll();
    }

    public function listNotAssignedItem($userid) {
        $result = Yii::app()->db->createCommand()
                ->selectDistinct("name, type")
                ->from($this->tableName())
                ->where("name not in(
							  select a.itemname
							  from AuthAssignment as a
							  join AuthItem as i on i.name = a.itemname
							  where userid=:userid)", array(':userid' => $userid)
                )
                ->order('type desc, name')
                ->query()
                ->readAll();

        // Init variable
        $authItem['operation'] = array();
        $authItem['task'] = array();
        $authItem['role'] = array();
        foreach ($result as $item) :
            switch ($item['type']):
                case 0 :
                    $authItem['operation'][] = array('name' => $item['name']);
                    break;
                case 1:
                    $authItem['task'][] = array('name' => $item['name']);
                    break;
                case 2:
                    $authItem['role'][] = array('name' => $item['name']);
                    break;
            endswitch;
        endforeach;
        return $authItem;
    }

}
