<?php

namespace app;



class View{
    /**
     * Resource
     * 
     * @var string
     */
    protected string $resource;
    /**
     * View Data
     * 
     * @var array
     */
    protected array $data = [];

    public function __construct()
    {
        $env = env('VIEW.REQUEST_URL');
        $this->resource = $env."/public/static/";
        $this->data["resource"] = $this->resource;
        $this->data["upload"] = $env."/public/upload/";
        $this->data["request"] = $env;
    }
}