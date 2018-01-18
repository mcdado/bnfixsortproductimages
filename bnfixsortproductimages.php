<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class BNFixSortProductImages extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'bnfixsortproductimages';
        $this->tab = 'administration';
        $this->version = '0.1.1';
        $this->author = 'Brand New srl';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Fix Sort Product Images');
        $this->description = $this->l('See https://github.com/PrestaShop/PrestaShop/pull/8666.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->prefix = Tools::strtoupper($this->name);
    }
}
