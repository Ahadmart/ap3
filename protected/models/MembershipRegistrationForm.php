<?php

/**
 * MembershipRegistrationForm class.
 * MembershipRegistrationForm is the data structure for keeping
 * member registration form data. It is used by the 'registrasi' action of 'MembershipController'.
 */
class MembershipRegistrationForm extends CFormModel
{
    const JENIS_KELAMIN_PRIA   = 0;
    const JENIS_KELAMIN_WANITA = 1;

    public $username;
    public $noTelp;
    public $namaLengkap;
    public $tanggalLahir;
    public $jenisKelamin;
    public $pekerjaanId;
    public $alamat;
    public $keterangan;

    /**
     * Declares the validation rules.
     * The rules state that noTelp and namaLengkap are required
     */
    public function rules()
    {
        return [
            ['noTelp, namaLengkap', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['tanggalLahir, jenisKelamin, pekerjaanId, alamat, keterangan, username', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'noTelp'      => 'Nomor Telp/Hp (Cth: 8123..)',
            'pekerjaanId' => 'Pekerjaan',
        ];
    }

    public static function listPekerjaan()
    {
        $clientAPI = new AhadMembershipClient;
        $r         = json_decode($clientAPI->infoListPekerjaan());
        if ($r->statusCode == 200) {
            return $r->data;
        }
        return [];
    }

    public static function listJenisKelamin()
    {
        return [
            self::JENIS_KELAMIN_PRIA   => 'Pria',
            self::JENIS_KELAMIN_WANITA => 'Wanita',
        ];
    }
}
