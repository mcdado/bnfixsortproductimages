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
            // If there is an exception, at least the response is in JSON format.
            $this->json = true;

            $res = true;
            $json = stripslashes($json);
            $images = Tools::jsonDecode($json, true);
            foreach ($images as $id => $position) {
                /*
                 * If the the image is not associated with the currently selected shop, the fields that are also in the
                 * image_shop table (like id_product and cover) cannot be loaded properly, so we have to load them
                 * separately.
                 */
                $img = new Image((int)$id);
                $def = $img::$definition;
                $sql = 'SELECT * FROM `' . _DB_PREFIX_ . $def['table'] . '` WHERE `' . $def['primary'] . '` = ' . (int)$id;
                $fields_from_table = Db::getInstance()->getRow($sql);
                foreach ($def['fields'] as $key => $value) {
                    if (!$value['lang']) {
                        $img->{$key} = $fields_from_table[$key];
                    }
                }
                $img->position = (int)$position;
                $res &= $img->update();
            }
        }
        if ($res) {
            $this->jsonConfirmation($this->_conf[25]);
        } else {
            $this->jsonError(Tools::displayError('An error occurred while attempting to move this picture.'));
        }
    }

    public function ajaxProcessUpdateCover()
    {
        if ($this->tabAccess['edit'] === '0') {
            return die(Tools::jsonEncode(array('error' => $this->l('You do not have the right permission'))));
        }
        Image::deleteCover((int)Tools::getValue('id_product'));
        $id_image = (int)Tools::getValue('id_image');

        /*
         * If the the image is not associated with the currently selected shop, the fields that are also in the
         * image_shop table (like id_product and cover) cannot be loaded properly, so we have to load them separately.
         */
        $img = new Image($id_image);
        $def = $img::$definition;
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . $def['table'] . '` WHERE `' . $def['primary'] . '` = ' . $id_image;
        $fields_from_table = Db::getInstance()->getRow($sql);
        foreach ($def['fields'] as $key => $value) {
            if (!$value['lang']) {
                $img->{$key} = $fields_from_table[$key];
            }
        }
        $img->cover = 1;

        @unlink(_PS_TMP_IMG_DIR_.'product_'.(int)$img->id_product.'.jpg');
        @unlink(_PS_TMP_IMG_DIR_.'product_mini_'.(int)$img->id_product.'_'.$this->context->shop->id.'.jpg');

        if ($img->update()) {
            $this->jsonConfirmation($this->_conf[26]);
        } else {
            $this->jsonError(Tools::displayError('An error occurred while attempting to update the cover picture.'));
        }
    }
}
