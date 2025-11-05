<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 09.05.2017
 * Time: 12:34
 */

namespace DibukEu\Entity;

class Item
{
    /**
     * @var int|null
     */
    public $id;
    /**
     * @var int|null
     */
    public $order_id;
    /**
     * @var int|null
     */
    public $payment_id;
    /**
     * @var float|null
     */
    public $price;
    /**
     * @var string|null
     */
    public $currency;
    /**
     * @var int|null
     */
    public $unique_id;
    /**
     * @var bool|null
     */
    public $license_created;
    /**
     * @var array
     */
    public $download_links = [];
    /**
     * @var array
     */
    public $attachments_links = [];
    /**
     * @var int
     */
    public $purchased;

    /**
     * Item constructor.
     *
     * @param array $item
     */
    public function __construct($item)
    {
        $item += [
            'id' => null,
            'order_id' => null,
            'payment_id' => null,
            'price' => null,
            'currency' => null,
            'unique_id' => null,
            'license_created' => null,
            'purchased' => null,
        ];

        $this->id = $item['id'];
        $this->order_id = $item['order_id'];
        $this->payment_id = $item['payment_id'];
        $this->price = $item['price'];
        $this->currency = $item['currency'];
        $this->unique_id = $item['unique_id'];
        $this->license_created = $item['license_created'];
        $this->purchased = $item['purchased'];

        /* default values not accessible from construct */
        $this->download_links = [];
    }

    /**
     * @param array $links
     * @return void
     */
    public function setDownloadLinks($links)
    {
        $this->download_links = $links;
    }

    /**
     * @param array $links
     * @return void
     */
    public function setAttachmentsLinks($links)
    {
        $this->attachments_links = $links;
    }

    /**
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    public function checkValid($type = 'minimal')
    {
        if ($type == 'full') {
            if ($this->isValidId() && $this->isValidOrderId() && $this->isValidPaymentId() && $this->isValidPrice()) {
                return true;
            } else {
                throw new \Exception('Item is not properly setted (' . $type . '), required fields are: id, order_id, payment_id and price. ');
            }
        }

        if ($type == 'order') {
            if ($this->isValidId() && $this->isValidOrderId()) {
                return true;
            } else {
                throw new \Exception('Item is not properly setted (' . $type . '), required fields are: id, order_id.');
            }
        }

        if ($type == 'minimal') {
            if ($this->isValidId()) {
                return true;
            } else {
                throw new \Exception('Item is not properly setted (' . $type . '), required field is: id.');
            }
        }

        throw new \InvalidArgumentException('Invalid valid type');
    }

    /**
     * @return void
     */
    public function setLicenseCreated()
    {
        $this->license_created = true;
    }

    /**
     * @return bool
     */
    private function isValidId()
    {
        return !is_null($this->id) && is_numeric($this->id);
    }

    /**
     * @return bool
     */
    private function isValidOrderId()
    {
        return !is_null($this->order_id) && is_numeric($this->order_id);
    }

    /**
     * @return bool
     */
    private function isValidPaymentId()
    {
        return !is_null($this->payment_id) && is_numeric($this->payment_id);
    }

    /**
     * @return bool
     */
    private function isValidPrice()
    {
        return !is_null($this->price) && is_numeric($this->price) && in_array($this->currency, ['EUR', 'CZK']);
    }
}
