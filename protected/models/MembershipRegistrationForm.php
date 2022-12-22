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

    public $kodeNegara;
    public $noTelp;
    public $namaLengkap;
    public $tanggalLahir;
    public $umur;
    public $umurOld;
    public $jenisKelamin;
    public $pekerjaanId;
    public $alamat;
    public $keterangan;
    public $userName;

    /**
     * Declares the validation rules.
     * The rules state that noTelp and namaLengkap are required
     */
    public function rules()
    {
        return [
            ['noTelp, namaLengkap, umur', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['kodeNegara, tanggalLahir, umurOld, jenisKelamin, pekerjaanId, alamat, keterangan, userName', 'safe'],
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

    public function beforeValidate()
    {
        if (!empty($this->umur) && empty($this->umurOld)) {
            // Ini kondisi data baru (registrasi)
            $time = new DateTime(date('Y-m-d', strtotime('first day of january this year')));
            $this->tanggalLahir = $time->modify("-{$this->umur} year")->format('Y-m-d');
        }

        if (!empty($this->umur) && !empty($this->umurOld) && $this->umurOld != $this->umur) {
            // Ini untuk kondisi update
            $time = new DateTime(date('Y-m-d', strtotime('first day of january this year')));
            $this->tanggalLahir = $time->modify("-{$this->umur} year")->format('Y-m-d');
        }
        return parent::beforeValidate();
    }
}
