<?php

/**
 * Class for getting information by SNMP protocol
 * This class has dependency on PHP extension "php_snmp.dll"
 *
 * @version    v0.10    2011-07-13
 * @author     Petr Kohut <me@petrkohut.cz>    -    http://www.petrkohut.cz
 * @category   Kohut
 * @package    Kohut_SNMP
 * @copyright  Copyright (c) 2011 - Petr Kohut
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Kohut_SNMP_Abstract
{

    /**
     * @var string IP address
     */
    protected $ip;

    /**
     * The max number of microseconds for SNMP call
     * Default value is set to 0,1 second
     *
     * @var int microseconds
     */
    protected $maxTimeout = 100000;

    /**
     * Contructor can set the ip address
     * and the maximum timeout in microseconds for SNMP call
     *
     * @param string $ip IP address
     * @param int $timeout microseconds
     * @throws Exception if PHP SNMP extension is not loaded
     */
    public function __construct($ip = null, $timeout = null)
    {
        if(!extension_loaded('snmp')) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('SNMP extension is not loaded');
        }

        if ($ip !== null) {
            $this->setIPAddress($ip);
        }

        if ($timeout !== null) {
            $this->setMaxTimeout($timeout);
        }
    }

    /**
     * Function returns IP address
     *
     * @return string IP address
     * @throws Exception if IP address is not set
     */
    public function __toString()
    {
        /**
         * Check if IP address is set
         */
        if ($this->ip === null) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('IP address was not set.');
        }

        return $this->ip;
    }

    /**
     * Function sets IP address
     *
     * @param string $ip IP address
     * @throws Exception if passed IP address is not in string format
     */
    public function setIPAddress($ip)
    {
        /**
         * Check if IP address is string
         */
        if (!is_string($ip)) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('Passed IP address is not string.');
        }

        $this->ip = $ip;
    }

    /**
     * Function sets maximum timeout in microseconds for SNMP calls
     *
     * @param int $microseconds
     * @throws Exception if passed timeout in microseconds is not in integer format
     */
    public function setMaxTimeout($microseconds)
    {
        /**
         * Check if timeout is integer
         */
        if (!is_int($microseconds)) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('Passed timeout is not int.');
        }

        $this->maxTimeout = $microseconds;
    }

    /**
     * Function gets result of SNMP object id,
     * or returns false if call failed
     *
     * @param string $snmpObjectId
     * @return int|string|boolean
     * @throws Exception if IP address is not set
     * @throws Exception if $snmpObjectId is not in string format
     */
    public function get($snmpObjectId)
    {
        /**
         * Check if IP address is set
         */
        if ($this->ip === null) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('IP address was not set.');
        }

        /**
         * Check if SNMP object ID is in string format
         */
        if (!is_string($snmpObjectId)) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('SNMP Object ID is not string.');
        }

        return @snmpget($this->ip, 'public', $snmpObjectId, $this->maxTimeout);
    }

    /**
     * Function walks through SNMP object id and returns result in array,
     * or returns false of call failed
     *
     * @param string $snmpObjectId
     * @return array
     * @throws Exception if IP address is not set
     * @throws Exception if $snmpObjectId is not in string format
     */
    public function walk($snmpObjectId)
    {
        /**
         * Check if IP address is set
         */
        if ($this->ip === null) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('IP address was not set.');
        }

        /**
         * Check if SNMP object ID is in string format
         */
        if (!is_string($snmpObjectId)) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('SNMP Object ID is not string.');
        }

        return @snmpwalk($this->ip, 'public', $snmpObjectId, $this->maxTimeout);
    }

    /**
     * Function deletes quotation marks chars from string returned by SNMP
     *
     * @param string $snmpString
     * @return string
     * @throws Exception if $snmpString is not in string format
     */
    protected function parseSNMPString($snmpString)
    {
        /**
         * Check if snmpString is string
         */
        if (!is_string($snmpString)) {
            require_once 'Kohut/SNMP/Exception.php';
            throw new Kohut_SNMP_Exception('Passed snmpString value is not string.');
        }

        return str_replace('"', '', $snmpString);
    }

    /**
     * Function gets parsed string result of SNMP object id,
     * or returns false if call failed
     *
     * @param string $snmpObjectId
     * @return string|boolean
     */
    public function getSNMPString($snmpObjectId)
    {
        $result = $this->get($snmpObjectId);

        return ($result !== false) ? $this->parseSNMPString($result) : false;
    }

}