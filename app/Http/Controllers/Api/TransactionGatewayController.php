<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionGatewayController extends Controller
{
    public $security_key;


    public function __construct($security_key)
    {
        $this->security_key = $security_key;
    }

    public function doSale($amount, $ccnumber, $ccexp, $cvv = "")
    {

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($this->security_key) . "&";
        // Sales Information
        $query .= "ccnumber=" . urlencode($ccnumber) . "&";
        $query .= "ccexp=" . urlencode($ccexp) . "&";
        $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
        $query .= "cvv=" . urlencode($cvv) . "&";
        $query .= "type=sale";
        return $this->_doPost($query);
    }

    public function _doPost($query)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://paylanez.transactiongateway.com/api/transact.php");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!($data = curl_exec($ch))) {
            return 'error';
        }
        curl_close($ch);
        unset($ch);
        //print "\n$data\n";
        $data = explode("&", $data);
        for ($i = 0; $i < count($data); $i++) {
            $rdata = explode("=", $data[$i]);
            $this->responses[$rdata[0]] = $rdata[1];
        }
        return $this->responses;
    }

}
