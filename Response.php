<?php

namespace OwenMelbz\IllumiPress;

use \Illuminate\Http\Response as IlluminateResponce;

class Response extends IlluminateResponce
{

    public $meta = [
        'success' => true
    ];

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

    public function getMeta()
    {
        return $this->meta;
    }

    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    public function addMeta(array $meta)
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    public function ajax(int $status = null)
    {
        return $this->ajaxTransform($status);
    }

    public function success(int $status = null)
    {
        return $this->ajaxTransform($status);
    }

    public function error(int $status = 400)
    {
        $this->addMeta([
            'success' => false
        ]);
        
        return $this->ajaxTransform($status);
    }
}
