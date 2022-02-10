<?php

/**
 * MembershipRegistrationForm class.
 * MembershipRegistrationForm is the data structure for keeping
 * member registration form data. It is used by the 'registrasi' action of 'MembershipController'.
 */
class MembershipRegistrationForm extends CFormModel
{
    public $username;
    public $noTelp;
    public $namaLengkap;
    public $tanggalLahir;
    public $pekerjaan;

    /**
     * Declares the validation rules.
     * The rules state that noTelp and namaLengkap are required
     */
    public function rules()
    {
        return [
            ['noTelp, namaLengkap', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['tanggalLahir, pekerjaan, username', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'noTelp'   => 'Nomor Telp/Hp'
        ];
    }

    public function registrasi()
    {
    }
}
