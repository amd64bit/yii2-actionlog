<?php

namespace atans\actionlog\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\web\Application as WebApplication;

/**
 * Class ActionLog
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $category
 * @property string $message
 * @property string $level
 * @property string $ip
 * @property string $created_at
 *
 * @property \yii\web\IdentityInterface $user
 */
class ActionLog extends ActiveRecord
{
    const LEVEL_INFO    = 'info';
    const LEVEL_ERROR   = 'error';
    const LEVEL_SUCCESS = 'success';
    const LEVEL_WARNING = 'warning';

    /**
     * Default category
     */
    const CATEGORY_DEFAULT = 'application';

    /**
     * @var string
     */
    //private $data;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actionlog}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'      => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value'      => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * Set data
     *
     * @param array $data
     */
    public function setData(array $data = null)
    {
        $this->data = is_null($data) ? null : json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'userIdPattern' => ['user_id', 'integer'],

            'levelRequired' => ['level', 'required'],
            'levelLength'   => ['level', 'string', 'max' => 50],
            'levelRange'    => ['level', 'in', 'range' => self::getLevels()],

            'categoryRequired' => ['category', 'required'],
            'categoryLength' => ['category', 'string', 'max' => 255],

            'messageRequired' => ['message', 'required'],
            'messageLength' => ['message', 'string', 'max' => 255],

            'ipLength'         => ['ip', 'string', 'max' => 42],

            'createdAtPattern' => ['created_at', 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * Get level name
     *
     * @return null|string
     */
    public function getLevelName()
    {
        $levelItems = self::getLevelItems();
        if (isset($levelItems[$this->level])) {
            return $levelItems[$this->level];
        }

        return null;
    }

    /**
     * Get levels
     *
     * @return array
     */
    public function getLevels()
    {
        return array_keys($this->getLevelItems());
    }

    /***
     * Get level items
     *
     * @return array
     */
    public static function getLevelItems()
    {
        return [
            self::LEVEL_ERROR   => Yii::t('actionlog', 'Error'),
            self::LEVEL_INFO    => Yii::t('actionlog', 'Info'),
            self::LEVEL_SUCCESS => Yii::t('actionlog', 'Success'),
            self::LEVEL_WARNING => Yii::t('actionlog', 'Warning'),
        ];
    }

    /**
     * Add error
     *
     * @param string $message
     * @param null|array $data
     * @param string $category
     * @return ActionLog|null
     */
    public static function error($message, array $data = null, $category = self::CATEGORY_DEFAULT)
    {
        return self::add(self::LEVEL_ERROR, $message, $data, $category);
    }

    /**
     * Add info
     *
     * @param string $message
     * @param null|array $data
     * @param string $category
     * @return ActionLog|null
     */
    public static function info($message, array $data = null, $category = self::CATEGORY_DEFAULT)
    {
        return self::add(self::LEVEL_INFO, $message, $data, $category);
    }

    /**
     * Add success
     *
     * @param string $message
     * @param null|array $data
     * @param string $category
     * @return ActionLog|null
     */
    public static function success($message, array $data = null, $category = self::CATEGORY_DEFAULT)
    {
        return self::add(self::LEVEL_SUCCESS, $message, $data, $category);
    }

    /**
     * Add warning
     *
     * @param string $message
     * @param null|array $data
     * @param string $category
     * @return ActionLog|null
     */
    public static function warning($message, array $data = null, $category = self::CATEGORY_DEFAULT)
    {
        return self::add(self::LEVEL_WARNING, $message, $data, $category);
    }

    /**
     * Add message
     *
     * @param string $level
     * @param string $message
     * @param null|array $data
     * @param string $category
     * @return ActionLog|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function add($level, $message, array $data = null, $category = self::CATEGORY_DEFAULT)
    {
        /* @var $actionLog ActionLog */
        $actionLog = Yii::createObject(static::className());

        $transaction = Yii::$app->getDb()->beginTransaction();

        try {
            $actionLog->message = $message;
            $actionLog->level = $level;
            $actionLog->category = $category;
            $actionLog->setData($data);

            $actionLog->save();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            return null;
        }

        return $actionLog;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (! $this->getIsNewRecord()) {
            throw new NotSupportedException('Update is not allowed');
        }

        if (Yii::$app instanceof WebApplication) {
            $this->ip = Yii::$app->request->userIP;

            if (! Yii::$app->user->getIsGuest() && ($userId = Yii::$app->getUser()->getId())) {
                $this->user_id = $userId;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }
}
