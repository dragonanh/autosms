<?php

/*
 * Created on Oct 11, 2010
 * Author: huypv5@viettel.com.vn
 * Copyright 2010 Viettel Telecom. All rights reserved.
 * VIETTEL PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */

/**
 * Lop mo rong cua SoapClient
 * Co kha nang handle timeout: connect, response
 * Connect timeout = Thoi gian connect toi WSDL
 * Response timeout = Thoi gian cho ket qua goi ham tu service
 * @author huypv5@viettel.com.vn
 */
class TimeoutSoapClient extends SoapClient {
  private $timeout;
  public function __construct($wsdl, $options) {
    $connectTimeout = isset($options['connect_timeout']) ? intval($options['connect_timeout']) : 0;
    $responseTimeout = isset($options['timeout']) ? intval($options['timeout']) : 0;
    if (strpos($wsdl, 'http://') === 0) {
      if ($connectTimeout > 0) {
        $curl = curl_init($wsdl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
          throw new Exception('Khong ket noi duoc toi WSDL ' . $wsdl . ' sau ' . $connectTimeout . ' giay');
        } else {
          $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          if ($httpCode != 200) {
            throw new Exception('Unexpected HTTP CODE ' . $httpCode . ' for ' . $wsdl);
          }
        }
        curl_close($curl);
      }
    } else {
      # We load WSDL from file local
    }

    if ($responseTimeout > 0) {
      $this->timeout = $responseTimeout;
    }

    parent::SoapClient($wsdl, $options);
  }

  public function __setTimeout($timeout) {
    if (!is_int($timeout) && !is_null($timeout)) {
      throw new Exception("Invalid timeout value");
    }
    $this->timeout = $timeout;
  }

  public function __doRequest($request, $location, $action, $version, $one_way = FALSE) {
    if (!$this->timeout) {
      // Call via parent because we require no timeout
      $response = parent::__doRequest($request, $location, $action, $version, $one_way);
    } else {
      // Call via Curl and use the timeout
      $curl = curl_init($location);

      curl_setopt($curl, CURLOPT_VERBOSE, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, TRUE);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_HTTPHEADER,
              array(
                  "SOAPAction: xyz", "Content-Type: text/xml"
      ));
      curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
      
      $response = curl_exec($curl);

      if (curl_errno($curl)) {
        throw new Exception(curl_error($curl));
      }

      curl_close($curl);
    }

    // Return?
    if (!$one_way) {
      return ($response);
    }
  }
}
