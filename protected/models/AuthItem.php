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
class AuthItem extends CActiveRecord
{
    //    public $authTypeName;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'AuthItem';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, type', 'required'],
            ['type', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 64],
            ['description, bizrule, data', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['name, type, description, bizrule, data', 'safe', 'on' => 'search'],
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
            'authAssignments'   => [self::HAS_MANY, 'AuthAssignment', 'itemname'],
            'authItemChildren'  => [self::HAS_MANY, 'AuthItemChild', 'parent'],
            'authItemChildren1' => [self::HAS_MANY, 'AuthItemChild', 'child'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'name'        => 'Nama',
            'type'        => 'Tipe',
            'description' => 'Keterangan',
            'bizrule'     => 'Bizrule',
            'data'        => 'Data',
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

        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('bizrule', $this->bizrule, true);
        $criteria->compare('data', $this->data, true);

        return new CActiveDataProvider($this, [
            'criteria'   => $criteria,
            'sort'       => [
                'defaultOrder' => 'type desc, name',
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AuthItem the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function primaryKey()
    {
        return 'name';
        // For composite primary key, return an array like the following
        // return array('pk1', 'pk2');
    }

    public function getAuthTypeName()
    {
        $type = ['0' => 'Operation', '1' => 'Task', '2' => 'Role'];
        return $type[$this->type];
    }

    public function listAuthItem($type, $name)
    {
        return Yii::app()->db->createCommand()
            ->selectDistinct('name')
            ->from($this->tableName())
            ->where(
                "name not in(
                                    select distinct name
                                    from AuthItem as item
                                    left join AuthItemChild as c on c.child = item.name
                                    left join AuthItemChild as p on p.parent = item.name
                                    where c.parent=:name or p.child=:name )
                                    and name!=:name and type=:type",
                [':name' => $name, ':type' => $type]
            )
            ->order('name')
            ->query()
            ->readAll();
    }

    public function listNotAssignedItem($userid)
    {
        $result = Yii::app()->db->createCommand()
            ->selectDistinct("name, type")
            ->from($this->tableName())
            ->where(
                "name not in(
                                    select a.itemname
                                    from AuthAssignment as a
                                    join AuthItem as i on i.name = a.itemname
                                    where userid=:userid)",
                [':userid' => $userid]
            )
            ->order('type desc, name')
            ->query()
            ->readAll();

        // Init variable
        $authItem['operation'] = [];
        $authItem['task']      = [];
        $authItem['role']      = [];
        foreach ($result as $item) :
            switch ($item['type']):
                case 0:
                    $authItem['operation'][] = ['name' => $item['name']];
                    break;
                case 1:
                    $authItem['task'][] = ['name' => $item['name']];
                    break;
                case 2:
                    $authItem['role'][] = ['name' => $item['name']];
                    break;
            endswitch;
        endforeach;
        return $authItem;
    }

    /**
     * Returns all the controllers and their actions.
     * @param array $items the controllers and actions.
     */
    public static function getControllerActions($items = null)
    {
        if ($items === null) {
            $items = AuthItem::model()->getAllControllers();
        }

        foreach ($items['controllers'] as $controllerName => $controller) {
            $actions    = [];
            $file       = fopen($controller['path'], 'r');
            $lineNumber = 0;
            while (feof($file) === false) {
                ++$lineNumber;
                $line = fgets($file);
                preg_match('/public[ \t]+function[ \t]+action([A-Z]{1}[a-zA-Z0-9]+)[ \t]*\(/', $line, $matches);
                if ($matches !== []) {
                    $name                       = $matches[1];
                    $actions[strtolower($name)] = [
                        'name' => $name,
                        'line' => $lineNumber,
                    ];
                }
            }

            $items['controllers'][$controllerName]['actions'] = $actions;
        }

        foreach ($items['modules'] as $moduleName => $module) {
            $items['modules'][$moduleName] = self::getControllerActions($module);
        }

        return $items;
    }

    /**
     * Returns a list of all application controllers.
     * @return array the controllers.
     */
    protected function getAllControllers()
    {
        $basePath             = Yii::app()->basePath;
        $items['controllers'] = $this->getControllersInPath($basePath . DIRECTORY_SEPARATOR . 'controllers');
        $items['modules']     = $this->getControllersInModules($basePath);
        return $items;
    }

    /**
     * Returns all controllers under the specified path.
     * @param string $path the path.
     * @return array the controllers.
     */
    protected function getControllersInPath($path)
    {
        $controllers = [];

        if (file_exists($path) === true) {
            $controllerDirectory = scandir($path);
            foreach ($controllerDirectory as $entry) {
                if ($entry[0] !== '.') {
                    $entryPath = $path . DIRECTORY_SEPARATOR . $entry;
                    if (strpos(strtolower($entry), 'controller') !== false) {
                        $name                           = substr($entry, 0, -14);
                        $controllers[strtolower($name)] = [
                            'name' => $name,
                            'file' => $entry,
                            'path' => $entryPath,
                        ];
                    }

                    if (is_dir($entryPath) === true) {
                        foreach ($this->getControllersInPath($entryPath) as $controllerName => $controller) {
                            $controllers[$entry . '/' . $controllerName] = $controller;
                        }
                    }
                }
            }
        }

        return $controllers;
    }

    /**
     * Returns all the controllers under the specified path.
     * @param string $path the path.
     * @return array the controllers.
     */
    protected function getControllersInModules($path)
    {
        $items = [];

        $modulePath = $path . DIRECTORY_SEPARATOR . 'modules';
        if (file_exists($modulePath) === true) {
            $moduleDirectory = scandir($modulePath);
            foreach ($moduleDirectory as $entry) {
                if (substr($entry, 0, 1) !== '.' && $entry !== 'rights') {
                    $subModulePath = $modulePath . DIRECTORY_SEPARATOR . $entry;
                    if (file_exists($subModulePath) === true) {
                        $items[$entry]['controllers'] = $this->getControllersInPath($subModulePath . DIRECTORY_SEPARATOR . 'controllers');
                        $items[$entry]['modules']     = $this->getControllersInModules($subModulePath);
                    }
                }
            }
        }

        return $items;
    }
}
