<?php

namespace App\Services;

class IpAnonymizerService
{
    /**
     * @var string IPv4 netmask used to anonymize IPv4 address.
     */
    public $ipv4NetMask = '255.255.255.0';

    /**
     * @var string IPv6 netmask used to anonymize IPv6 address.
     */
    public $ipv6NetMask = 'ffff:ffff:ffff:ffff:0000:0000:0000:0000';

    /**
     * Anonymize an IPv4 or IPv6 address. This method will return the
     * anonymized IP address or an empty string when the IP address
     * is invalid.
     *
     * @param string $address
     * @return string
     */
    public static function anonymizeIp($address)
    {
        $anonymizer = new self();

        return $anonymizer->anonymize($address);
    }

    /**
     * Anonymize an IPv4 or IPv6 address. This method will return the
     * anonymized IP address or an empty string when the IP address
     * is invalid.
     *
     * @param string $address
     * @return string
     */
    public function anonymize($address)
    {
        $packedAddress = inet_pton($address);

        if (strlen($packedAddress) == 4) {
            return $this->anonymizeIPv4($address);
        } elseif (strlen($packedAddress) == 16) {
            return $this->anonymizeIPv6($address);
        } else {
            return '';
        }
    }

    /**
     * Anonymize an IPv4 address.
     *
     * @param string $address
     * @return string
     */
    public function anonymizeIPv4($address)
    {
        return inet_ntop(inet_pton($address) & inet_pton($this->ipv4NetMask));
    }

    /**
     * Anonymize an IPv6 address.
     *
     * @param string $address
     * @return string
     */
    public function anonymizeIPv6($address)
    {
        return inet_ntop(inet_pton($address) & inet_pton($this->ipv6NetMask));
    }
}
