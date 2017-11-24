<?php declare(strict_types = 1);

namespace Link0\Bunq\Domain;

final class NotificationFilter
{
    const DELIVERYMETHOD_PUSH = 'PUSH';
    const DELIVERYMETHOD_CALLBACK = 'URL';

    const CATEGORY_BANK_SWITCH_SERVICE = 'BANK_SWITCH_SERVICE';
    const CATEGORY_BANK_SWITCH_SERVICE_PAYMENT = 'BANK_SWITCH_SERVICE_PAYMENT';
    const CATEGORY_BUNQME_FUNDRAISER = 'BUNQME_FUNDRAISER';
    const CATEGORY_BUNQME_TAB = 'BUNQME_TAB';
    const CATEGORY_BILLING = 'BILLING';
    const CATEGORY_CARD_TRANSACTION_FAILED = 'CARD_TRANSACTION_FAILED';
    const CATEGORY_CARD_TRANSACTION_SUCCESSFUL = 'CARD_TRANSACTION_SUCCESSFUL';
    const CATEGORY_CHAT = 'CHAT';
    const CATEGORY_DRAFT_PAYMENT = 'DRAFT_PAYMENT';
    const CATEGORY_IDEAL = 'IDEAL';
    const CATEGORY_SOFORT = 'SOFORT';
    const CATEGORY_FEATURE_ANNOUNCEMENT = 'FEATURE_ANNOUNCEMENT';
    const CATEGORY_FRIEND_SIGN_UP = 'FRIEND_SIGN_UP';
    const CATEGORY_MONETARY_ACCOUNT_PROFILE = 'MONETARY_ACCOUNT_PROFILE';
    const CATEGORY_MUTATION = 'MUTATION';
    const CATEGORY_PAYMENT = 'PAYMENT';
    const CATEGORY_PROMOTION = 'PROMOTION';
    const CATEGORY_REQUEST = 'REQUEST';
    const CATEGORY_SCHEDULE_RESULT = 'SCHEDULE_RESULT';
    const CATEGORY_SCHEDULE_STATUS = 'SCHEDULE_STATUS';
    const CATEGORY_SHARE = 'SHARE';
    const CATEGORY_SUPPORT = 'SUPPORT';
    const CATEGORY_TAB_RESULT = 'TAB_RESULT';
    const CATEGORY_USE_RESPONSE = 'USE_RESPONSE';
    const CATEGORY_USE_RESPONSE_NATIVE_COMMENT = 'USE_RESPONSE_NATIVE_COMMENT';
    const CATEGORY_USE_RESPONSE_NATIVE_TOPIC = 'USE_RESPONSE_NATIVE_TOPIC';
    const CATEGORY_USER_APPROVAL = 'USER_APPROVAL';

    const CATEGORY_SLICE_CHAT = 'SLICE_CHAT';
    const CATEGORY_SLICE_REGISTRY_ENTRY = 'SLICE_REGISTRY_ENTRY';
    const CATEGORY_SLICE_REGISTRY_MEMBERSHIP = 'SLICE_REGISTRY_MEMBERSHIP';
    const CATEGORY_SLICE_REGISTRY_SETTLEMENT = 'SLICE_REGISTRY_SETTLEMENT';

    const CATEGORY_WHITELIST = 'WHITELIST';
    const CATEGORY_WHITELIST_RESULT = 'WHITELIST_RESULT';

    /**
     * @var string
     */
    private $deliveryMethod;

    /**
     * @var string
     */
    private $target;

    /**
     * @var string
     */
    private $category;

    /**
     * @param string $deliveryMethod
     * @param string $target
     * @param string $category
     */
    private function __construct(string $deliveryMethod, string $target, string $category)
    {
        $this->guardDeliveryMethod($deliveryMethod);
        $this->guardCategory($category);

        $this->deliveryMethod = $deliveryMethod;
        $this->target = $target;
        $this->category = $category;
    }

    /**
     * @param array $notificationFilterStruct
     * @return NotificationFilter
     */
    public static function fromArray(array $notificationFilterStruct): NotificationFilter
    {
        // Target is optional (required for callback, not push)
        $notificationTarget = '';
        if (isset($notificationFilterStruct['notification_target'])) {
            $notificationTarget = $notificationFilterStruct['notification_target'];
        }

        return new self(
            $notificationFilterStruct['notification_delivery_method'],
            $notificationTarget,
            $notificationFilterStruct['category']
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'notification_delivery_method' => $this->deliveryMethod(),
            'notification_target' => $this->target(),
            'category' => $this->category(),
        ];
    }

    /**
     * @param string $category
     * @return NotificationFilter
     */
    public static function createPush(string $category)
    {
        return new self(self::DELIVERYMETHOD_PUSH, '', $category);
    }

    /**
     * @param string $callbackUrl
     * @param string $category
     * @return NotificationFilter
     */
    public static function createCallback(string $callbackUrl, string $category)
    {
        return new self(self::DELIVERYMETHOD_CALLBACK, $callbackUrl, $category);
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function guardCategory($category)
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        if (!array_key_exists('CATEGORY_' . $category, $reflectionClass->getConstants())) {
            throw new \Exception("Invalid NotificationFilter->category '{$category}'");
        }
    }

    /**
     * @param $deliveryMethod
     * @return void
     * @throws \Exception
     */
    private function guardDeliveryMethod($deliveryMethod)
    {
        if (!in_array($deliveryMethod, [
            self::DELIVERYMETHOD_PUSH,
            self::DELIVERYMETHOD_CALLBACK,
        ])) {
            throw new \Exception("Invalid NotificationFilter->deliveryMethod '{$deliveryMethod}'");
        }
    }

    /**
     * @return string
     */
    public function deliveryMethod(): string
    {
        return $this->deliveryMethod;
    }

    /**
     * @return string
     */
    public function target(): string
    {
        return $this->deliveryMethod;
    }

    /**
     * @return string
     */
    public function category(): string
    {
        return $this->category;
    }
}
