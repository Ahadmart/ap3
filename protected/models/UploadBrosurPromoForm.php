<?php

class UploadBrosurPromoForm extends CFormModel
{
    public $fileGambar;

    public function rules()
    {
        return [
            [
                'fileGambar',
                'file',
                'types'      => 'jpg, png',
                'allowEmpty' => true,
            ],
            ['fileGambar', 'cekUploadNotEmpty']
        ];
    }

    public function cekUploadNotEmpty($attr)
    {
        $this->fileGambar = CUploadedFile::getInstance($this, 'fileGambar');
        if (empty($this->uploadFile)) {
            $this->addError($attr, Yii::t('main', 'Pilih file gambar untuk diunggah.'));
            return false;
        }
        return true;
    }
}
