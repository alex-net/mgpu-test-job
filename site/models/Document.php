<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use Yii;

/**
 * модель документа ...
 * @property $title  string  Заголовк документа
 * @property $author_id int  Автор документа
 * @property $body string  Заголовок
 * @property $status string Статус
 */

class Document extends ActiveRecord
{
    const DOCS_STATUS_DRAFT = 'draft';
    const DOCS_STATUS_VERIFICATION = 'verification';
    const DOCS_STATUS_PROVED = 'proved';

    const DOCS_STATUSES = [
        self::DOCS_STATUS_DRAFT => 'Черновик',
        self::DOCS_STATUS_VERIFICATION => 'На проверке',
        self::DOCS_STATUS_PROVED => 'Проверен',
    ];

    public static function tableName()
    {
        return '{{%docs}}';
    }

    public static function list()
    {
        return new ActiveDataProvider([
            'query' => static::find(),
        ]);
    }


    public function rules()
    {
        return [
            [['title', 'body'], 'trim'],
            ['title', 'string', 'max' => 50],
            ['title', 'required'],
            ['title', 'unique', 'targetAttribute' => 'title'],
            ['body', 'string'],
            ['author_id', 'default', 'value' => Yii::$app->user->id],
            ['status', 'in', 'range' => array_keys(static::DOCS_STATUSES)],
            ['status', 'default', 'value' => static::DOCS_STATUS_DRAFT],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'body' => 'Содержимое',
            'status' => 'Статус',

        ];
    }

    /**
     * Запрос текстового представления статуса ...
     */
    public function getStatusDescr($key = null)
    {
        if ($key && isset(static::DOCS_STATUSES[$key])) {
            return static::DOCS_STATUSES[$key];
        }
        return static::DOCS_STATUSES[$this->status];
    }

    /**
     * Проверяем достигнут ли последний статус у документа ..
     */
    public function getIsLastStatus(): bool
    {
        $statusList = array_keys(static::DOCS_STATUSES);
        $index = array_search($this->status, $statusList);
        return $index == count($statusList) - 1;
    }

    public function getNextStatus()
    {
        if ($this->isLastStatus) {
            return false;
        }

        $statusList = array_keys(static::DOCS_STATUSES);
        $index = array_search($this->status, $statusList);
        return $statusList[$index + 1];
    }

    /**
     * перевести документ  новый статус
     */
    public function goToNextSt()
    {
        $newSt = $this->nextStatus;
        if (!$newSt) {
            return false;
        }

        $this->status = $newSt;
        return $this->save();

    }
}