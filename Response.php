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
     * @var array
     */
    public $meta = [
        'success' => true
    ];

    /**
     * @param int|null $status
     * @return $this
     */
    private function ajaxTransform(int $status = null)
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

        return $this->send();
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function addMeta(array $meta)
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * @param int|null $status
     * @return Response
     */
    public function ajax(int $status = null)
    {
        return $this->ajaxTransform($status);
    }

    /**
     * @param int|null $status
     * @return Response
     */
    public function success(int $status = null)
    {
        return $this->ajaxTransform($status);
    }

    /**
     * @param int $status
     * @return Response
     */
    public function error(int $status = 400)
    {
        $this->addMeta([
            'success' => false
        ]);
        
        return $this->ajaxTransform($status);
    }
}
