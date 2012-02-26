<?php

namespace Davedevelopment\Sismo;

use Sismo\Notifier;
use Sismo\Commit;
use Buzz\Browser;

class GeckoboardNotifier extends Notifier
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $widgetKey;

    /**
     * @var String|Callable
     */
    protected $format = null;

    /**
     * @var callable
     *
     * Just for testing...
     */
    protected $poster = null;

    /**
     * Constructor
     *
     * @param string          $apiKey
     * @param string          $widget
     * @param string|callable $format
     */
    public function __construct($apiKey, $widget, $format = null)
    {
        $this->apiKey    = $apiKey;
        if (0 !== strpos($widget, 'http')) {
            $widget = "https://push.geckoboard.com/v1/send/" . $widget;
        }
        $this->widgetUrl = $widget;

        if ($format !== null) {
            $this->setFormat($format);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function notify(Commit $commit)
    {
        $type = $commit->getStatus() == 'failed' ? 1 : 2;

        $data = array(
            "apiKey" => $this->apiKey,
            "data" => array(
                "item" => array(
                    array(
                        "text" => $this->getMessage($commit),
                        "type" => $type,
                    ),
                ),
            ),
        );

        $response = $this->send($this->widgetUrl, array('Content-type' => 'application/json'), json_encode($data));
        return;
    }

    /**
     * Send a request
     *
     * @param string $url
     * @param array $headers
     * @param string $data
     *
     * @return 
     */
    protected function send($url, array $headers = array(), $data)
    {
        if ($this->poster !== null) {
            return call_user_func($this->poster, $url, $headers, $data);
        }

        /**
         * See
         * http://wezfurlong.org/blog/2006/nov/http-post-from-php-without-curl/
         */
        $params = array('http' => array(
            'method'  => 'POST',
            'content' => $data
        ));
        if (!empty($headers)) {
            array_walk($headers, function(&$value, $key) {
                $value = $key . ':' . $value;
            });
            $params['http']['header'] = implode("\n", $headers);
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            return;
        }
        $response = @stream_get_contents($fp);

        return;
    }


    /**
     * Get format
     *
     * @return string|callable $format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set format. A custom string (with placeholders, as described in
     * Sismo\Notifier, or a callback, taking a Commit as it's only parameter
     *
     * @param string|callable $format
     * @return GeckoboardNotifier
     */
    public function setFormat($format)
    {
        if (!is_string($format) && !is_callable($format)) {
            throw new \InvalidArgumentException(
                sprintf("\$format should be string or callable, %s given", gettype($format))
            );
        }

        $this->format = $format;
        return $this;
    }

    /**
     * Set poster, only really here for testing so we can avoid an actual HTTP
     * post
     *
     * @param callable $poster
     * @return GeckoboardNotifier
     */
    public function setPoster($poster)
    {
        if (!is_callable($poster)) {
            throw new \InvalidArgumentException(
                sprintf("\$poster should be callable, %s given", gettype($poster))
            );
        }

        $this->poster = $poster;
        return $this;
    }

    /**
     * Get Message
     *
     * @param Commit $commit
     * @return string
     */
    protected function getMessage(Commit $commit)
    {
        if ($this->format == null) {
            return $this->getDefaultMessage($commit);
        }

        if (is_string($this->format)) {
            return $this->format($this->format, $commit);
        }

        return call_user_func($this->format, $commit);
    }

    /**
     * Get default message
     *
     * @param Commit $commit
     * @return string
     */
    protected function getDefaultMessage(Commit $commit)
    {
        return $this->format("[%STATUS%]\n%message%\n%author%", $commit);
    }

}


