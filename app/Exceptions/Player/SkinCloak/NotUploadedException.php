<?php


namespace App\Exceptions\Player\SkinCloak;


use App\Exceptions\BaseException;

class NotUploadedException extends BaseException
{
    /**
     * NotUploadedException constructor.
     */
    public function __construct()
    {
        parent::__construct(__('cabinet.response.skin_cloak.errors.not_uploaded'));
    }
}
