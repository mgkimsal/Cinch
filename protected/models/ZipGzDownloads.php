<?php

/**
 * This is the model class for table "zip_gz_downloads".
 *
 * The followings are the available columns in table 'zip_gz_downloads':
 * @property integer $id
 * @property integer $user_id
 * @property string $archive_path
 * @property integer $downloaded
 * @property string $creationdate
 */
class ZipGzDownloads extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ZipGzDownloads the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zip_gz_downloads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, archive_path, creationdate', 'required'),
			array('user_id, downloaded', 'numerical', 'integerOnly'=>true),
			array('archive_path', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, archive_path, downloaded, creationdate', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'archive_path' => 'Archive Path',
			'downloaded' => 'Downloaded',
			'creationdate' => 'Creationdate',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('archive_path',$this->archive_path,true);
		$criteria->compare('downloaded',$this->downloaded);
		$criteria->compare('creationdate',$this->creationdate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}