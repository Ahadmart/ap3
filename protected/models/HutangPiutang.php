<?php

/**
 * This is the model class for table "hutang_piutang".
 *
 * The followings are the available columns in table 'hutang_piutang':
 * @property string $id
 * @property string $nomor
 * @property string $profil_id
 * @property string $jumlah
 * @property integer $tipe
 * @property integer $status
 * @property integer $asal
 * @property string $nomor_dokumen_asal
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Profil $profil
 * @property User $updatedBy
 * @property HutangPiutangDetail[] $hutangPiutangDetails
 * @property Pembelian[] $pembelians
 * @property PenerimaanDetail[] $penerimaanDetails
 * @property PengeluaranDetail[] $pengeluaranDetails
 * @property Penjualan[] $penjualans
 * @property ReturPenjualan[] $returPenjualans
 */
class HutangPiutang extends CActiveRecord
{
    /* Definisi field `tipe` */

    const TIPE_HUTANG = 0;
    const TIPE_PIUTANG = 1;

    /* Kode dokumen untuk Hutang Piutang */
    const KODE_DOKUMEN_HUTANG = '06';
    const KODE_DOKUMEN_PIUTANG = '07';

    /* Defenisi field `asal` */
    const DARI_PEMBELIAN = 1;
    const DARI_RETUR_BELI = 2;
    const DARI_PENJUALAN = 3;
    const DARI_RETUR_JUAL = 4;

    /* Status Hutang Piutang. Field `status` */
    const STATUS_BELUM_LUNAS = 0;
    const STATUS_LUNAS = 1;

    /* Item Keuangan ID untuk mencatat pembayaran hutang pembelian */
    const ITEM_KEU_BAYAR_HUTANG_PEMBELIAN = 2;

    /* Item Keuangan ID untuk mencatat pembayaran piutang penjualan */
    const ITEM_KEU_BAYAR_PIUTANG_PENJUALAN = 4;

    /* Item Keuangan ID untuk mencatat pembayaran piutang retur beli */
    const ITEM_KEU_BAYAR_PIUTANG_RETUR_BELI = 5;

    /* Item Keuangan ID untuk mencatat pembayaran hutang retur jual */
    const ITEM_KEU_BAYAR_HUTANG_RETUR_JUAL = 6;

    public $max; // Untuk mencari untuk nomor surat;
    public $namaProfil;
    public $noRef;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'hutang_piutang';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('jumlah, tipe, asal', 'required'),
            array('tipe, status, asal', 'numerical', 'integerOnly' => true),
            array('nomor', 'length', 'max' => 45),
            array('jumlah', 'length', 'max' => 18),
            array('updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nomor, jumlah, tipe, status, asal, nomor_dokumen_asal, updated_at, updated_by, created_at, namaProfil, noRef', 'safe', 'on' => 'search'),
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
            'profil' => array(self::BELONGS_TO, 'Profil', 'profil_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'hutangPiutangDetails' => array(self::HAS_MANY, 'HutangPiutangDetail', 'hutang_piutang_id'),
            'pembelians' => array(self::HAS_MANY, 'Pembelian', 'hutang_piutang_id'),
            'penerimaanDetails' => array(self::HAS_MANY, 'PenerimaanDetail', 'hutang_piutang_id'),
            'pengeluaranDetails' => array(self::HAS_MANY, 'PengeluaranDetail', 'hutang_piutang_id'),
            'penjualans' => array(self::HAS_MANY, 'Penjualan', 'hutang_piutang_id'),
            'returPenjualans' => array(self::HAS_MANY, 'ReturPenjualan', 'hutang_piutang_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nomor' => 'Nomor',
            'profil_id' => 'Profil',
            'jumlah' => 'Jumlah',
            'tipe' => 'Tipe',
            'status' => 'Status',
            'nomor_dokumen_asal' => 'Nomor Dokumen',
            'asal' => 'Asal',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Timestamp',
            'namaProfil' => 'Profil'
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('t.nomor', $this->nomor, true);
        $criteria->compare('profil_id', $this->profil_id, true);
        $criteria->compare('jumlah', $this->jumlah, true);
        $criteria->compare('tipe', $this->tipe);
        $criteria->compare('status', $this->status);
        $criteria->compare('asal', $this->asal);
        $criteria->compare('nomor_dokumen_asal', $this->nomor_dokumen_asal, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('t.created_at', $this->created_at, true);

        $criteria->with = array('profil');
        $criteria->compare('profil.nama', $this->namaProfil, true);
        $criteria->join = 'LEFT JOIN pembelian on pembelian.hutang_piutang_id = t.id';

        if ($this->scenario == 'pilihDokumen') {
            $criteria->addCondition('t.status=' . HutangPiutang::STATUS_BELUM_LUNAS);
        }
        if ($this->noRef != '') {
            $criteria->addCondition("pembelian.referensi like '%{$this->noRef}%'");
        }

        $sort = array(
            'attributes' => array(
                'namaProfil' => array(
                    'asc' => 'profil.nama',
                    'desc' => 'profil.nama desc'
                ),
                'noRef' => array(
                    'asc' => 'pembelian.referensi',
                    'desc' => 'pembelian.referensi desc'
                ),
                '*'
            )
        );

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return HutangPiutang the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {

        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            /* Solusi temporer migrasi ke 6 digit seq num untuk ahadmart */
            $this->nomor = $this->generateNomor6Seq();
        }
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomorTahunan()
    {
        $tahun = date('y');
        $data = $this->find(array(
            'select' => 'max(substring(nomor,9)*1) as max',
            'condition' => "substring(nomor,5,2)='{$tahun}' and tipe={$this->tipe} ")
        );

        $value = is_null($data) ? 0 : $data->max;
        return $value + 1;
    }

    /**
     * Membuat nomor surat, 6 digit sequence number
     * @return string Nomor sesuai format "[KodeCabang][kodeDokumen][Tahun][Bulan][SequenceNumber]"
     */
    public function generateNomor6Seq()
    {
        $config = Config::model()->find("nama='toko.kode'");
        $kodeCabang = $config->nilai;
        $kodeDokumen = $this->tipe == HutangPiutang::TIPE_HUTANG ? KodeDokumen::HUTANG : KodeDokumen::PIUTANG;
        $kodeTahunBulan = date('ym');
        $sequence = substr('00000' . $this->cariNomorTahunan(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }


    public function listNamaAsal()
    {
        return array(
            HutangPiutang::DARI_PEMBELIAN => 'Pembelian',
            HutangPiutang::DARI_RETUR_BELI => 'Retur Beli',
            HutangPiutang::DARI_PENJUALAN => 'Penjualan',
            HutangPiutang::DARI_RETUR_JUAL => 'Retur Jual'
        );
    }

    /**
     * Nama dari asal yang bertipe int
     * @return text Nama dokumen asal terjadinya hutang piutang
     */
    public function getNamaAsal()
    {
        $nama = $this->listNamaAsal();
        return $nama[$this->asal];
    }

    public function listNamaTipe()
    {
        return array(
            HutangPiutang::TIPE_HUTANG => 'Hutang',
            HutangPiutang::TIPE_PIUTANG => 'Piutang',
        );
    }

    /**
     * Nama dari tipe yang berjenis int
     * @return text Nama Tipe
     */
    public function getNamaTipe()
    {
        $nama = $this->listNamaTipe();
        return $nama[$this->tipe];
    }

    /**
     * Keterangan default untuk keuangan (pengeluaran/penerimaan)
     * @return text
     */
    public function keterangan()
    {
        $judul = '';
        switch ($this->asal) {
            case HutangPiutang::DARI_PEMBELIAN:
                $judul = 'Pembelian';
                $to = 'dari';
                break;

            case HutangPiutang::DARI_PENJUALAN:
                $judul = 'Penjualan';
                $to = 'ke';
                break;

            case HutangPiutang::DARI_RETUR_BELI:
                $judul = 'Retur Beli';
                $to = 'ke';
                break;

            case HutangPiutang::DARI_RETUR_JUAL:
                $judul = 'Retur Jual';
                $to = 'dari';
                break;
        }
        $ket = "{$judul} {$this->nomor_dokumen_asal} {$to} {$this->profil->nama}";
        return is_null($this->getNoref()) ? $ket : $ket . ' ' . $this->getNoRef();
    }

    public function itemKeuanganId($asal)
    {
        $itemId = null;
        switch ($asal) {
            case HutangPiutang::DARI_PEMBELIAN:
                $itemId = HutangPiutang::ITEM_KEU_BAYAR_HUTANG_PEMBELIAN;
                break;
            case HutangPiutang::DARI_PENJUALAN:
                $itemId = HutangPiutang::ITEM_KEU_BAYAR_PIUTANG_PENJUALAN;
                break;
            case HutangPiutang::DARI_RETUR_BELI:
                $itemId = HutangPiutang::ITEM_KEU_BAYAR_PIUTANG_RETUR_BELI;
                break;
            case HutangPiutang::DARI_RETUR_JUAL:
                $itemId = HutangPiutang::ITEM_KEU_BAYAR_HUTANG_RETUR_JUAL;
                break;
        }
        return $itemId;
    }

    /**
     * Mendapatkan item keuangan (kode akun) dari dokumen hutang piutang
     * @return array id, nama, & nama parent dari Item Keuangan (kode akun)
     */
    public function getItemBayarHutang()
    {
        $itemId = $this->itemKeuanganId($this->asal);
        $item = ItemKeuangan::model()->findByPk($itemId);

        return array(
            'itemId' => $itemId,
            'itemNama' => $item->nama,
            'itemParent' => $item->parent->nama
        );
    }

    /**
     * Menghitung nominal hutang piutang yang belum lunas
     * @return int Nominal hutang piutang
     */
    public function getSisa()
    {
        return $this->jumlah - Pengeluaran::model()->totalSudahBayar($this->id) - Penerimaan::model()->totalSudahBayar($this->id);
    }

    /**
     * Cek apakah sudah lunas atau belum
     * @return boolean True jika berhasil bayar (ubah status)
     * @throws Exception Jika jumlah bayar > dari hutangpiutang
     */
    public function bayar()
    {
        if ($this->sisa < 1 && $this->sisa >= 0) {
            /* Jika masih ada selisih/sisa dibelakang koma, maka dianggap lunas */
            $this->status = HutangPiutang::STATUS_LUNAS;
            $this->updateStatusDokumenAsal(HutangPiutang::STATUS_LUNAS);
            return true;
        } else
        if ($this->sisa >= 1) {
            $this->status = HutangPiutang::STATUS_BELUM_LUNAS;
            $this->updateStatusDokumenAsal(HutangPiutang::STATUS_BELUM_LUNAS);
            return true;
        } else
        if ($this->sisa < 0) {
            throw new Exception("Jumlah bayar lebih besar dari hutang! hpId: {$this->nomor}");
        }
    }

//	public function namaClassAsal($idClass) {
//		$list = array(
//			 HutangPiutang::DARI_PEMBELIAN => 'Pembelian',
//			 HutangPiutang::DARI_RETUR_BELI => 'ReturBeli',
//			 HutangPiutang::DARI_PENJUALAN => 'Penjualan',
//			 HutangPiutang::DARI_RETUR_JUAL => 'ReturJual'
//		);
//		return $list[$idClass];
//	}

    public function updateStatusDokumenAsal($status)
    {
        switch ($this->asal) {
            case HutangPiutang::DARI_PEMBELIAN:
                $model = Pembelian::model()->findByAttributes(array('hutang_piutang_id' => $this->id));
                $model->status = $status == HutangPiutang::STATUS_LUNAS ? Pembelian::STATUS_LUNAS : Pembelian::STATUS_HUTANG;
                $model->update(array('status'));
                break;
            case HutangPiutang::DARI_RETUR_BELI:
                $model = ReturPembelian::model()->findByAttributes(array('hutang_piutang_id' => $this->id));
                $model->status = $status == HutangPiutang::STATUS_LUNAS ? ReturPembelian::STATUS_LUNAS : ReturPembelian::STATUS_PIUTANG;
                $model->update(array('status'));
                break;
            case HutangPiutang::DARI_PENJUALAN:
                $model = Penjualan::model()->findByAttributes(array('hutang_piutang_id' => $this->id));
                $model->status = $status == HutangPiutang::STATUS_LUNAS ? Penjualan::STATUS_LUNAS : Penjualan::STATUS_PIUTANG;
                $model->update(array('status'));
                break;
            case HutangPiutang::DARI_RETUR_JUAL:
                $model = ReturPenjualan::model()->findByAttributes(array('hutang_piutang_id' => $this->id));
                $model->status = $status == HutangPiutang::STATUS_LUNAS ? ReturPenjualan::STATUS_LUNAS : ReturPenjualan::STATUS_HUTANG;
                $model->update(array('status'));
                break;
        }
    }

    /**
     * Ambil nomor referensi untuk pembelian
     * @return text nomor referensi, null jika selain pembelian
     */
    public function getNoref()
    {
        switch ($this->asal) {
            case HutangPiutang::DARI_PEMBELIAN:
                $model = Pembelian::model()->find('hutang_piutang_id =' . $this->id);
                return $model->referensi;
            default:
                return null;
        }
    }

}
