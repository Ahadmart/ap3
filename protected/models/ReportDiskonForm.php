<?php

/**
 * ReportDiskonForm class.
 * ReportDiskonForm is the data structure for keeping
 * report diskon form data. It is used by the 'diskon' action of 'ReportController'.
 *
 */
class ReportDiskonForm extends CFormModel
{

    public $profilId;
    public $userId;
    public $tipeDiskonId;
    public $dari;
    public $sampai;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, userId, tipeDiskonId', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId' => 'Profil',
            'userId' => 'User',
            'tipeDiskonId' => 'Tipe Diskon',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
        ];
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function getNamaUser()
    {
        $user = User::model()->findByPk($this->userId);
        return $user->nama;
    }
    
    public function listTipeDiskon(){
        return DiskonBarang::model()->listTipe();
    }

    public function reportDiskon()
    {
        $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
        $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');
    }

}
