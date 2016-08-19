<?php

/**
 * ReportPengeluaranPenerimaanForm class.
 * ReportPengeluaranPenerimaanForm is the data structure for keeping
 * report penjualan form data. It is used by the 'pengeluaranpenerimaan' action of 'ReportController'.
 * 
 */
class ReportPengeluaranPenerimaanForm extends CFormModel
{

    public $profilId;
    public $itemKeuId;
    public $dari;
    public $sampai;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('profilId, itemKeuId', 'safe')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'profilId' => 'Profil',
            'itemKeuId' => 'Item Keuangan',
            'dari' => 'Dari',
            'sampai' => 'Sampai'
        );
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function getNamaItemKeu()
    {
        $itemKeu = ItemKeuangan::model()->findByPk($this->itemKeuId);
        $namaParent = isset($itemKeu->parent) ? '(' . $itemKeu->parent->nama . ')' : '(-)';
        return $namaParent . ' ' . $itemKeu->nama;
    }

    public function reportPengeluaranPenerimaan()
    {
        
    }

}
