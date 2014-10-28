<?php

namespace Backend;

use Subbly\Subbly;

class SettingsController extends BaseController
{
    /**
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('@processAuthentication');
    }


    /**
     * Get Setting list
     *
     * @route GET /backend/settings
     * @authentication required
     */
    public function index()
    {
        $settings = Subbly::api('subbly.setting')->all();

        return $this->jsonResponse(array(
            'settings'  => $settings,
        ));
    }
}
