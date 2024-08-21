<?php

/**
 * This is the model class for table "label_barang_cetak".
 *
 * The followings are the available columns in table 'label_barang_cetak':
 * @property string $id
 * @property string $barang_id
 * @property string $qty
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property User $updatedBy
 */
class LabelBarangCetak extends CActiveRecord
{
    const LAYOUT_DEFAULT              = 10;
    const LAYOUT_DEFAULT_RRP_TERAKHIR = 20;
    const LAYOUT_DEFAULT_HJ_TERAKHIR  = 30;

    public $barcode;
    public $namaBarang;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'label_barang_cetak';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['barang_id, qty', 'required'],
            ['barang_id, qty, updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, barang_id, qty, updated_at, updated_by, created_at, barcode, namaBarang', 'safe', 'on' => 'search'],
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
            'barang'    => [self::BELONGS_TO, 'Barang', 'barang_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'barang_id'  => 'Barang',
            'qty'        => 'Qty',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaBarang' => 'Nama',
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
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('barang_id', $this->barang_id);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('t.updated_by', $this->updated_by);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->with = ['barang'];
        $criteria->compare('barang.barcode', $this->barcode, true);
        $criteria->compare('barang.nama', $this->namaBarang, true);

        $sort = [
            'defaultOrder' => 'barang.nama',
            'attributes'   => [
                'namaBarang' => [
                    'asc'  => 'barang.nama',
                    'desc' => 'barang.nama desc',
                ],
                'barcode'    => [
                    'asc'  => 'barang.barcode',
                    'desc' => 'barang.barcode desc',
                ],
                '*',
            ],
        ];

        return new CActiveDataProvider(
            $this,
            [
                'criteria'   => $criteria,
                'sort'       => $sort,
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param  string           $className active record class name.
     * @return LabelBarangCetak the static model class
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
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public static function tambahPembelian($id)
    {
        $pembelianDetail = PembelianDetail::model()->findAll('pembelian_id = :pembelianId', [':pembelianId' => $id]);

        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
            self::_tambahDetail($pembelianDetail);
            $transaction->commit();
            return [
                'sukses' => true,
            ];
        } catch (Exception $ex) {
            $transaction->rollback();
            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ],
            ];
        }
    }

    private static function _tambahDetail($detail)
    {
        foreach ($detail as $barang) {
            self::_insertBarangDariPembelian($barang);
        }
    }

    private static function _insertBarangDariPembelian($barang)
    {
        $sql = 'SELECT * FROM label_barang_cetak WHERE barang_id=:barangId AND updated_by=:userId';
        $r   = Yii::app()->db->createCommand($sql)
            ->bindValues(
                [
                    ':barangId' => $barang->barang_id,
                    ':userId'   => Yii::app()->user->id,
                ]
            )
            ->queryRow();
        if ($r === false) {
            $newLabel            = new LabelBarangCetak();
            $newLabel->barang_id = $barang->barang_id;
            $newLabel->qty       = $barang->qty;
            if (!$newLabel->save()) {
                throw new Exception('Gagal tambah barang ' . $barang->barang->nama, 500);
            }
        } else {
            $newQty = $r['qty'] + $barang->qty;
            if (!LabelBarangCetak::model()->updateByPk($r['id'], ['qty' => $newQty])) {
                throw new Exception('Gagal update qty barang ' . $barang->barang->nama, 500);
            }
        }
    }

    public static function cetak($items, $printerId, $layoutId)
    {
        $condition = 'id in (';
        $i         = 1;
        $params    = [];
        $wahid     = true;
        foreach ($items as $item) {
            $key = ':item' . $i;
            if (!$wahid) {
                $condition .= ',';
            }
            $condition .= $key;
            $params[$key] = $item;
            $wahid        = false;
            $i++;
        }
        $condition .= ')';
        $labels = LabelBarangCetak::model()->findAll($condition, $params);

        $r = self::generateZPL($labels, $layoutId);

        $printer = Device::model()->findByPk($printerId);
        $printer->printLpr($r['command']);
        // print_r($r['command']);
        return [
            'sukses'     => true,
            'labelCount' => $r['labelCount'],
            'itemCount'  => $r['itemCount'],
        ];
    }

    public static function generateZPL($labels, $layoutId)
    {
        switch ($layoutId) {
            case self::LAYOUT_DEFAULT_HJ_TERAKHIR:
                return self::generateZPLDefaultHJTerakhir($labels);
                break;

            default:
                return self::generateZPLDefault($labels);
                break;
        }
    }

    public static function generateZPLDefault($labels)
    {
        /* 1 Label ada 2 kolom (kanan dan !kanan)
        Print dimulai dari kanan
         */
        $posisiR   = 427;
        $posisi¬R  = 145;

        $configLabelOffset = Config::model()->find("nama='labelbarang.default.offset'");
        $labelOffset       = explode(',', $configLabelOffset->nilai);
        $offsetR           = $labelOffset[1];
        $offset¬R          = $labelOffset[0];

        $command    = '';
        $itemCount  = 0;
        $labelCount = 0;
        $kanan      = true;
        foreach ($labels as $label) {
            $barcode      = trim($label->barang->barcode);
            $nama         = substr(trim($label->barang->nama), 0, 28); // Maksimum 28 char, potong jika lebih panjang
            $digitBarcode = strlen($barcode);
            // Code 128 Barcode
            $subsetC     = $digitBarcode % 2 === 0 ? '>;' : ''; // baca: Jika jumlah digit genap, maka pakai subsetC
            $sizeBarcode = $digitBarcode % 2 === 0 || $digitBarcode <= 8 ? '2' : '1'; // baca: Jika jumlah digit genap atau <= 8, maka pakai ukuran normal.

            for ($i = 0; $i < $label->qty; $i++) {
                if ($kanan) {
                    $command .= '^XA'; // Init Printer
                    $command .= '^MTT'; // PENTING !! Perintah untuk menggunakan Transfer Ribbon
                    $command .= '^XZ';

                    $command .= '^XA'; // Init Printer
                    $command .= '^LH0';

                    $command .= '~SD25'; // Tebal

                    $command .= '^FT' . $posisiR + $offsetR . ',35';
                    $command .= '^CF0,20,17^FD' . $nama . '^FS';
                    $command .= '^BY' . $sizeBarcode . ',3,50';
                    $command .= '^FT' . $posisiR + $offsetR . ',95^BCN,,Y,N,N';
                    $command .= '^A0N,20,25';
                    $command .= '^FD' . $subsetC . $barcode . '^FS';
                } else {
                    $command .= '^FT' . $posisi¬R + $offset¬R . ',35';
                    $command .= '^CF0,20,17^FD' . $nama . '^FS';
                    $command .= '^BY' . $sizeBarcode . ',3,50';
                    $command .= '^FT' . $posisi¬R + $offset¬R . ',95^BCN,,Y,N,N';
                    $command .= '^A0N,20,25';
                    $command .= '^FD' . $subsetC . $barcode . '^FS';
                    $command .= '^PQ1,1,1,Y';
                    $command .= '^XZ';
                }
                $kanan = !$kanan;
                $labelCount++;
            }
            $itemCount++;
        }

        // Jika label terakhir di posisi kanan (sekarang !kanan), maka tambahkan perintah akhir
        if (!$kanan) {
            $command .= '^PQ1,1,1,Y';
            $command .= '^XZ';
        }
        return [
            'command'    => $command,
            'labelCount' => $labelCount,
            'itemCount'  => $itemCount,
        ];
    }

    public static function generateZPLDefaultHJTerakhir($labels)
    {
        /* 1 Label ada 2 kolom (kanan dan !kanan)
        Print dimulai dari kanan
         */
        $posisiR   = 427;
        $posisi¬R  = 145;

        $configLabelOffset = Config::model()->find("nama='labelbarang.default.offset'");
        $labelOffset       = explode(',', $configLabelOffset->nilai);
        $offsetR           = $labelOffset[1];
        $offset¬R          = $labelOffset[0];

        $command    = '';
        $itemCount  = 0;
        $labelCount = 0;
        $kanan      = true;
        foreach ($labels as $label) {
            $barcode      = trim($label->barang->barcode);
            $nama         = substr(trim($label->barang->nama), 0, 28); // Maksimum 28 char, potong jika lebih panjang
            $digitBarcode = strlen($barcode);
            $hj           = $label->barang->getHargaJual();
            // Code 128 Barcode
            $subsetC     = $digitBarcode % 2 === 0 ? '>;' : ''; // baca: Jika jumlah digit genap, maka pakai subsetC
            $sizeBarcode = $digitBarcode % 2 === 0 || $digitBarcode <= 8 ? '2' : '1'; // baca: Jika jumlah digit genap atau <= 8, maka pakai ukuran normal.

            for ($i = 0; $i < $label->qty; $i++) {
                if ($kanan) {
                    $command .= '^XA'; // Init Printer
                    $command .= '^MTT'; // PENTING !! Perintah untuk menggunakan Transfer Ribbon
                    $command .= '^XZ';

                    $command .= '^XA'; // Init Printer
                    $command .= '^LH0';

                    $command .= '~SD25'; // Tebal

                    $command .= '^FT' . $posisiR + $offsetR . ',35';
                    $command .= '^CF0,20,17^FD' . $nama . '^FS';
                    $command .= '^FT' . $posisiR + $offsetR . ',55';
                    $command .= '^CF0,20,25^FD' . $hj . '^FS';
                    $command .= '^BY' . $sizeBarcode . ',3,31';
                    $command .= '^FT' . $posisiR + $offsetR . ',95^BCN,,Y,N,N';
                    $command .= '^A0N,20,25';
                    $command .= '^FD' . $subsetC . $barcode . '^FS';
                } else {
                    $command .= '^FT' . $posisi¬R + $offset¬R . ',35';
                    $command .= '^CF0,20,17^FD' . $nama . '^FS';
                    $command .= '^FT' . $posisi¬R + $offset¬R . ',55';
                    $command .= '^CF0,20,25^FD' . $hj . '^FS';
                    $command .= '^BY' . $sizeBarcode . ',3,31';
                    $command .= '^FT' . $posisi¬R + $offset¬R . ',95^BCN,,Y,N,N';
                    $command .= '^A0N,20,25';
                    $command .= '^FD' . $subsetC . $barcode . '^FS';
                    $command .= '^PQ1,1,1,Y';
                    $command .= '^XZ';
                }
                $kanan = !$kanan;
                $labelCount++;
            }
            $itemCount++;
        }

        // Jika label terakhir di posisi kanan (sekarang !kanan), maka tambahkan perintah akhir
        if (!$kanan) {
            $command .= '^PQ1,1,1,Y';
            $command .= '^XZ';
        }
        return [
            'command'    => $command,
            'labelCount' => $labelCount,
            'itemCount'  => $itemCount,
        ];
    }

    public static function listLayout()
    {
        return [
            self::LAYOUT_DEFAULT             => 'Default (Dua Kolom)',
            self::LAYOUT_DEFAULT_HJ_TERAKHIR => 'Default + HJ terakhir',
        ];
    }

    public static function tambahBarang($id)
    {
        $barang = Barang::model()->findByPk($id);

        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
            self::_insertBarang($barang);
            $transaction->commit();
            return [
                'sukses' => true,
            ];
        } catch (Exception $ex) {
            $transaction->rollback();
            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ],
            ];
        }
    }

    private static function _insertBarang($barang)
    {
        $sql = 'SELECT * FROM label_barang_cetak WHERE barang_id=:barangId AND updated_by=:userId';
        $r   = Yii::app()->db->createCommand($sql)
            ->bindValues(
                [
                    ':barangId' => $barang->id,
                    ':userId'   => Yii::app()->user->id,
                ]
            )
            ->queryRow();
        if ($r === false) {
            $newLabel            = new LabelBarangCetak();
            $newLabel->barang_id = $barang->id;
            $newLabel->qty       = 1;
            if (!$newLabel->save()) {
                throw new Exception('Gagal tambah barang ' . $barang->nama, 500);
            }
        } else {
            throw new Exception('Barang: ' . $barang->nama . ', sudah ada!', 500);
        }
    }
}
