<?php


namespace App\JsonRpc;


interface ConfigServiceInterface
{
    public function getAbout();
    public function updateAbout($params);
}