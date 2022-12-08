<?php

require "vendor/autoload.php";
use PHPHtmlParser\Dom;

class OrderParser
{
    protected $dom;
    protected $trackingNumber;
    protected $PONumber;
    protected $scheduledTime;
    protected $customer;
    protected $trade;
    protected $nte;
    protected $storeId;
    protected $addressStreet;
    protected $addressCity;
    protected $addressState;
    protected $addressZipcode;


    public function __construct()
    {
        $this->dom = new Dom();
    }

    public function source($fileName)
    {
        $this->dom->loadFromFile('storage/'.$fileName);
    }

    public function parse()
    {
        $this->orderDetails();

        return [
            'tracking_number' => $this->trackingNumber,
            'po_number' => $this->PONumber,
            'scheduled_time' => $this->formatTime($this->scheduledTime),
            'customer' => $this->customer,
            'trade' => $this->trade,
            'nte' => $this->formatPrice($this->nte),
            'store_id' => $this->storeId,
            'address_street' => $this->addressStreet,
            'address_city' => $this->addressCity,
            'address_state' => $this->addressState,
            'address_zipcode' => $this->addressZipcode,
            'phone' => $this->formatPhone($this->phone)
        ];
    }

    protected function formatTime($time)
    {
        $dateTime = new DateTime($time);

        return $dateTime->format('Y-m-d H:i');
    }

    protected function formatPrice($price)
    {
        $price = preg_replace('/[^a-zA-Z0-9_.]/', '', $price);

        return $price;
    }

    public function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        return $phone;
    }

    protected function orderDetails()
    {
        $this->trackingNumber = $this->getTrackingNumber();
        $this->PONumber = $this->getPONumber();
        $this->scheduledTime = $this->getScheduledTime();
        $this->customer = $this->getCustomer();
        $this->trade = $this->getTrade();
        $this->nte = $this->getNTE();
        $this->storeId = $this->getStoreId();
        $address = $this->getAddress();
        $this->addressStreet = $address['street'];
        $this->addressCity = $address['city'];
        $this->addressState = $address['state'];
        $this->addressZipcode = $address['zipcode'];
        $this->phone = $this->getPhone();
    }

    protected function getTrackingNumber()
    {
        return $this->dom->find('#wo_number')->innerText;
    }

    protected function getPONumber()
    {
        return $this->dom->find('#po_number')->innerText;
    }

    protected function getScheduledTime()
    {
        return $this->dom->find('#scheduled_date')->innerText;
    }

    protected function getCustomer()
    {
        return $this->dom->find('#customer')->innerText;
    }

    protected function getTrade()
    {
        return $this->dom->find('#trade')->innerText;
    }

    protected function getNTE()
    {
        return $this->dom->find('#nte')->innerText;
    }

    protected function getStoreId()
    {
        return $this->dom->find('#location_name')->innerText;
    }

    protected function getAddress()
    {
        $address = explode('<br />', $this->dom->find('a#location_address')->innerHtml);

        $address[1] = explode(' ', trim($address[1]));

        $zipCode = $address[1][count($address[1]) - 1];
        $state = $address[1][count($address[1]) - 2];
        $city = [];
        for ($i = 0; $i <= count($address[1]) - 3; $i++) {
            if (strlen($address[1][$i]) > 0) {
                $city[] = $address[1][$i];
            }
        }

        return [
            'street' => trim($address[0]),
            'city' => implode(' ', $city),
            'state' => $state,
            'zipcode' => $zipCode
        ];
    }

    protected function getPhone()
    {
        return $this->dom->find('#location_phone')->innerText;
    }
}

$parser = new OrderParser();
$parser->source('wo_for_parse.html');
echo '<pre>';
print_r($parser->parse());
echo '</pre>';