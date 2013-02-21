<?php

/**
 * httpstatusbot.php 
 * @author tagamy
 */

error_reporting(E_ALL & ~E_DEPRECATED);

require_once 'Net/SmartIRC.php';
require_once 'HTTP/Request2.php';

define('MYIRC_CHANNEL',  "#hoge");
define('MYIRC_USERNAME', "httpstatusbot");

class mybot {
    
    var $ch;
    var $code = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',                      // RFC 2518 (WebDAV)
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',                 // RFC 2518 (WebDAV)
        208 => 'Already Reported',             // RFC 5842
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Request Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',            // RFC 2324
        422 => 'Unprocessable Entity',     // RFC 2518 (WebDAV)
        423 => 'Locked',                   // RFC 2518 (WebDAV)
        424 => 'Failed Dependency',        // RFC 2518 (WebDAV)
        425 => 'No code',                  // WebDAV Advanced Collections
        426 => 'Upgrade Required',         // RFC 2817
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry with',                      // unofficial Microsoft
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',         // RFC 2295
        507 => 'Insufficient Storage',            // RFC 2518 (WebDAV)
        509 => 'Bandwidth Limit Exceeded',        // unofficial
        510 => 'Not Extended',                    // RFC 2774
        511 => 'Network Authentication Required'
    );

    function __construct() {

      $this->ch = MYIRC_CHANNEL;
        
   }


    function httpstatus($irc, $data) {
        $status = $data->message;
        var_dump($data);
        if (isset($this->code[$status])) {
            $irc->message(SMARTIRC_TYPE_NOTICE, $data->channel, $status . ' ' . $this->code[$status]);
        }
    }
    function onjoin($irc, $data) {        
        if ($data->nick == $irc->_nick) {
            //$irc->message(SMARTIRC_TYPE_NOTICE, $this->ch, $this->text_onjoin);
        }

        if ($irc->_nick != MYIRC_USERNAME) {
            $irc->disconnect();
        }

        
    }
    
    
}



$bot = new mybot();
$irc = new Net_SmartIRC();
//$irc->setDebug(SMARTIRC_DEBUG_ALL);


$irc->registerActionHandler(SMARTIRC_TYPE_CHANNEL, '^[0-9]{3}$', $bot, 'httpstatus');
$irc->registerActionhandler(SMARTIRC_TYPE_JOIN, '.*', $bot, 'onjoin');

$irc->connect("ssl://irc.sample.com", 6667);
$irc->setChannelSyncing(true);

$irc->login(MYIRC_USERNAME, MYIRC_USERNAME, 0, null, "password");

$irc->join(array(MYIRC_CHANNEL));

$irc->listen();

