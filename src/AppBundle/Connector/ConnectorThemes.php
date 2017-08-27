<?php
/**
 * @file
 *   Connector theme api file.
 */
namespace AppBundle\Connector;

use AppBundle\Connector\Connector;

/**
 * Class Connector
 */
class ConnectorThemes
{
    const VERSION = '/api/v1/theme/';
    protected $connector;

    /**
     * Connector constructor.
     *
     * @param \AppBundle\Connector\Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Send theme notification.
     *
     * @param string $scope     The scope.
     * @param string $event     The event.
     * @param null   $nid       The node ID of the related entity (site or group).
     * @param null   $theme     The system name of the theme.
     * @param null   $timestamp A Unix timestamp of when the event occurred.
     * @param null   $uid       The user id owning the notification and who should get notified if an error occurs during processing.
     *
     * @return mixed|string
     */
    public function sendNotification($scope, $event, $nid = null, $theme = null, $timestamp = null, $uid = null)
    {
        $url = $this->connector->getURL().self::VERSION.'notification';
        $params = [
            'scope' => $scope,
            'event' => $event,
            'nid' => $nid,
            'theme' => $theme,
            'timestamp' => $timestamp,
            'uid' => $uid,
        ];
        $response = $this->connector->connecting($url, $params, 'POST');
        if (isset($response->notification)) {
            return $response->notification;
        }

        return $response;
    }
}
