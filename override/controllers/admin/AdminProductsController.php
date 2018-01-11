<?php

class AdminProductsController extends AdminProductsControllerCore
{
    public function ajaxProcessUpdateImagePosition()
    {
        if ($this->tabAccess['edit'] === '0') {
            return die(Tools::jsonEncode(array('error' => $this->l('You do not have the right permission'))));
        }

        $res = false;
        if ($json = Tools::getValue('json')) {
            $res = true;
            $json = stripslashes($json);
            $images = Tools::jsonDecode($json, true);
            foreach ($images as $id => $position) {
                // FIX BEGIN
                $res &= Db::getInstance()->execute(
                    'UPDATE `' . _DB_PREFIX_ . 'image` SET `position`= ' . (int)$position .
                    ' WHERE `id_image` = ' . (int)$id
                );
                // FIX END
            }
        }

        if ($res) {
            $this->jsonConfirmation($this->_conf[25]);
        } else {
            $this->jsonError(Tools::displayError('An error occurred while attempting to move this picture.'));
        }
    }

}
