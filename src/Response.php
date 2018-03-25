<?php

namespace OwenMelbz\IllumiPress;

use \Illuminate\Http\Response as IlluminateResponce;

/**
 * Class Response
 * @package OwenMelbz\IllumiPress
 */
class Response extends IlluminateResponce
{

    /**
     * Stores the meta to be associated with the json response
     *
     * @var array
     */
    public $meta = [
        'success' => true
    ];

    /**
     * Delivers an Illuminate\Http\Response | Symfony\Http\Response in a consistent format
     * and terminates the script, unless it is set up to return the response class.
     *
     * @param int|null $status
     * @param bool $return
     * @return $this
     */
    private function ajaxTransform(int $status = null, bool $return = false)
    {
        if ($status) {
            $this->setStatusCode($status);
        }

        if (!$data = json_decode($this->getContent())) {
            $data = $this->getContent();
        }

        if (!is_numeric($data) && empty($data)) {
            $data = null;
        }

        $content = [
            'data' => $data,
            'meta' => $this->getMeta(),
        ];

        $this->setContent($content);

        if ($return) {
            return $this;
        }


        return $this->send();
    }

    /**
     * Returns the applied meta data
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Sets the meta data of the response
     *
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Merges a new array of meta, to the existing meta data
     *
     * @param array $meta
     * @return $this
     */
    public function addMeta(array $meta)
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * Dispatches a preformatted response to the client
     *
     * @param int|null $status
     * @param bool $return
     * @return Response
     */
    public function ajax(int $status = null, bool $return = false)
    {
        return $this->ajaxTransform($status, $return);
    }

    /**
     * An alias of ajax()
     *
     * @param int|null $status
     * @param bool $return
     * @return Response
     */
    public function success(int $status = null, bool $return = false)
    {
        return $this->ajaxTransform($status, $return);
    }

    /**
     * Dispatches a preformatted response to the client, with bad response headers and meta
     *
     * @param int $status
     * @param bool $return
     * @return Response
     */
    public function error(int $status = 400, bool $return = false)
    {
        $this->addMeta([
            'success' => false
        ]);
        
        return $this->ajaxTransform($status, $return);
    }
}
