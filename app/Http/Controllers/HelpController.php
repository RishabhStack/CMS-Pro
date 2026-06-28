<?php

namespace App\Http\Controllers;

class HelpController extends BaseController
{
    public function index()
    {
        try {
            return $this->view('help.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load help page.', $e->getMessage());
        }
    }
}
