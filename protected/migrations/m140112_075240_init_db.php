<?php

class m140112_075240_init_db extends CDbMigration
{
    /*
      public function up() {


      }

      public function down() {
      echo "m140112_075240_init_db does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('AuthAssignment', array(
            'itemname' => 'varchar(64) NOT NULL',
            'userid' => 'varchar(64) NOT NULL',
            'bizrule' => 'text',
            'data' => 'text',
            'PRIMARY KEY (`itemname`,`userid`)'
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insert('AuthAssignment', array(
            'itemname' => 'admin',
            'userid' => '1',
            'bizrule' => NULL,
            'data' => 'N;'
        ));

        $this->createTable('AuthItem', array(
            'name' => 'varchar(64) NOT NULL',
            'type' => 'int(11) NOT NULL',
            'description' => 'text',
            'bizrule' => 'text',
            'data' => 'text',
            'PRIMARY KEY (`name`)'
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insert('AuthItem', array('name' => 'admin', 'type' => '2', 'description' => '', 'bizrule' => NULL, 'data' => 'N;'));
        $this->insert('AuthItem', array('name' => 'authenticated', 'type' => '2', 'description' => 'User Biasa', 'bizrule' => 'return !Yii::app()->user->isGuest;', 'data' => 'N;'));
        $this->insert('AuthItem', array('name' => 'updateOwnProfile', 'type' => '1', 'description' => 'update a profile by user himself', 'bizrule' => "return Yii::app()->user->id==Yii::app()->request->getParam('id');", 'data' => 'N;'));
        $this->insert('AuthItem', array('name' => 'user.index', 'type' => '0', 'description' => '', 'bizrule' => NULL, 'data' => 'N;'));
        $this->insert('AuthItem', array('name' => 'user.tambah', 'type' => '0', 'description' => '', 'bizrule' => NULL, 'data' => 'N;'));
        $this->insert('AuthItem', array('name' => 'user.ubah', 'type' => '0', 'description' => '', 'bizrule' => NULL, 'data' => 'N;'));

        $this->createTable('AuthItemChild', array(
            'parent' => 'varchar(64) NOT NULL',
            'child' => 'varchar(64) NOT NULL',
            'PRIMARY KEY (`parent`,`child`)',
            'KEY `child` (`child`)'
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insert('AuthItemChild', array(
            'parent' => 'authenticated',
            'child' => 'updateOwnProfile'
        ));
        $this->insert('AuthItemChild', array(
            'parent' => 'updateOwnProfile',
            'child' => 'user.ubah'
        ));

        $this->createTable('template 1', array(
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'updated_by' => 'int(11) NOT NULL',
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)'
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

        $this->createTable('user', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'nama_lengkap' => 'varchar(100) DEFAULT NULL',
            'password' => 'varchar(512) NOT NULL',
            'salt' => 'varchar(512) NOT NULL',
            'last_logon' => "timestamp NULL DEFAULT NULL",
            'last_ipaddress' => 'int(10) unsigned DEFAULT NULL',
            'theme_id' => 'tinyint(3) unsigned DEFAULT NULL',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_user_theme_idx` (`theme_id`)'
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2');

        $this->insert('user', array(
            'id' => 1,
            'nama' => 'admin',
            'nama_lengkap' => 'Administrator',
            'password' => 'JDJ5JDEwJGVSU29DMUlkWERMQTl3VVhPRTg0d2VISjB6WWJvclBuNkhHQlptY3Jza0FUUGl4TVVBUTlH',
            'salt' => 'eRSoC1IdXDLA9wUXOE84wjTPy3VVFg==',
            'theme_id' => 2, //'default dark'
            'created_at' => '2000-01-01 00:00:00',
            'updated_at' => '2000-01-01 00:00:00',
            'updated_by' => 1
        ));

        $this->addForeignKey('AuthAssignment_ibfk_1', 'AuthAssignment', 'itemname', 'AuthItem', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('AuthItemChild_ibfk_1', 'AuthItemChild', 'parent', 'AuthItem', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('AuthItemChild_ibfk_2', 'AuthItemChild', 'child', 'AuthItem', 'name', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_user_updatedby', 'user', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m140112_075240_init_db does not support migration down.\n";
        return false;
    }

}
