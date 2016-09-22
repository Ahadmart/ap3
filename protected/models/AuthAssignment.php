<?php

/**
 * This is the model class for table "AuthAssignment".
 *
 * The followings are the available columns in table 'AuthAssignment':
 * @property string $itemname
 * @property string $userid
 * @property string $bizrule
 * @property string $data
 *
 * The followings are the available model relations:
 * @property AuthItem $itemname0
 */
class AuthAssignment extends CActiveRecord
{

    public $nama;
    public $nama_lengkap;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'AuthAssignment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('itemname, userid', 'required'),
            array('itemname, userid', 'length', 'max' => 64),
            array('bizrule, data', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('itemname, userid, bizrule, data, nama, nama_lengkap', 'safe', 'on' => 'search'),
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
            'itemname0' => array(self::BELONGS_TO, 'AuthItem', 'itemname'),
            'user' => array(self::BELONGS_TO, 'User', 'userid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'itemname' => 'Assigned item',
            'userid' => 'User ID',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('itemname', $this->itemname, true);
        $criteria->compare('userid', $this->userid, true);
        $criteria->compare('bizrule', $this->bizrule, true);
        $criteria->compare('data', $this->data, true);

        $criteria->with = array('user');
        $criteria->compare('user.nama', $this->nama, false);
        $criteria->compare('user.nama_lengkap', $this->nama_lengkap, false);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'nama' => array(
                        'asc' => 'user.nama',
                        'desc' => 'user.nama desc'
                    ),
                    'nama_lengkap' => array(
                        'asc' => 'user.nama_lengkap',
                        'desc' => 'user.nama_lengkap desc'
                    ),
                    '*'
                )
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AuthAssignment the static model class
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

    public function primaryKey()
    {
        return array('itemname', 'userid');
    }

    public function getAuthTypeName()
    {
        $type = array('0' => 'Operation', '1' => 'Task', '2' => 'Role');
        return $type[$this->itemname0->type];
    }

    public function getAssignedList()
    {
        return Yii::app()->db->createCommand()
                        ->selectDistinct("t.itemname, i.type,
                                      (case i.type
                                            when 0 then 'operation'
                                            when 1 then 'task'
                                            when 2 then 'role'
                                            end)  as typename")
                        ->from($this->tableName() . ' t')
                        ->join(AuthItem::model()->tableName() . ' i', 'i.name=t.itemname')
                        ->where("userid=:userid", array(':userid' => $this->userid))
                        ->order('i.type desc, t.itemname')
                        ->query()
                        ->readAll();
    }

    public function assignedList($userId)
    {
        return Yii::app()->db->createCommand()
                        ->selectDistinct("t.itemname, i.type,
                                      (case i.type
                                            when 0 then 'operation'
                                            when 1 then 'task'
                                            when 2 then 'role'
                                            end)  as typename")
                        ->from($this->tableName() . ' t')
                        ->join(AuthItem::model()->tableName() . ' i', 'i.name=t.itemname')
                        ->where("userid=:userid", array(':userid' => $userId))
                        ->order('i.type desc, t.itemname')
                        ->query()
                        ->readAll();
    }

    public function listUsers()
    {
        $user = new User;
        $criteria = new CDbCriteria;

        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('nama_lengkap', $this->nama_lengkap, true);

        $criteria->join = 'left join AuthAssignment a on a.userid = t.id';
        $criteria->select= 'id, nama, nama_lengkap';

        $criteria->group = 't.id';

        return new CActiveDataProvider($user, array(
            'criteria' => $criteria,
        ));
    }

//	public function getNama(){
//		return isset($this->user0) ? $this->user0->nama : '' ;
//	}
//	public function getNama_Lengkap(){
//		return isset($this->user0) ? $this->user0->nama_lengkap : '' ;
//	}
}
