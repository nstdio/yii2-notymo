<?php
namespace nstdio\yii2notymo;

use nstdio\notymo\MessageInterface;
use nstdio\notymo\PushNotification as PushImpl;
use nstdio\yii2notymo\provider\DataProvider;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Class PushNotification
 *
 * @author Edgar Asatryan <nstdio@gmail.com>
 */
class PushNotification extends Object
{
    /**
     * @var DataProvider
     */
    public $dataProvider;

    /**
     * @var PushImpl
     */
    protected $push;

    public function __construct($config = [])
    {
        if (!isset($config['push'])) {
            throw new InvalidConfigException("push configuration required.");
        }
        if (!isset($config['dataProvider'])) {
            throw new InvalidConfigException("dataProvider configuration required.");
        }
        if (!isset($config['dataProvider']['class'])) {
            $config['dataProvider']['class'] = 'nstdio\yii2notymo\provider\SQLDataProvider';
        }
        if (isset($config['push']['skipApns'])) {
            $config['dataProvider']['apns'] = null;
        }
        if (isset($config['push']['skipGcm'])) {
            $config['dataProvider']['gcm'] = null;
        }

        $this->push = new PushImpl($config['push']);

        $this->dataProvider = Yii::createObject($config['dataProvider']);
        unset($config['push'], $config['dataProvider']);

        parent::__construct($config);
    }

    public function send(MessageInterface $message, $userId)
    {
        $oldTokens = $message->getToken();
        $tokens = $this->dataProvider->getTokens($userId);

        if ($this->dataProvider->apns !== null) {
            $apnsTokens = ArrayHelper::getColumn($tokens, $this->dataProvider->apns);
            if (!empty($apnsTokens)) {
                $message->setType(MessageInterface::TYPE_IOS);
                $message->addToken($apnsTokens);
                $this->push->enqueue($message);
            }
        }

        if ($this->dataProvider->gcm !== null) {
            $gcmTokens = ArrayHelper::getColumn($tokens, $this->dataProvider->gcm);

            if (!empty($gcmTokens)) {
                $gcmMessage = clone $message;
                $gcmMessage->setType(MessageInterface::TYPE_ANDROID);
                $gcmMessage->setToken(ArrayHelper::merge($oldTokens, $gcmTokens));

                $this->push->enqueue($gcmMessage);
            }
        }

        $this->push->send();
    }
}