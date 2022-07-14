<?php
/**
 * --------------------------------------------------------------
 * File: MITS_ImageDate.php
 * Created by PhpStorm
 * Date: 26.07.2018
 * Time: 11:40
 *
 * Author: Hetfield
 * Copyright: (c) 2018 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 *
 * Released under the GNU General Public License
 * --------------------------------------------------------------
 */

class MITS_ImageDate {

  function __construct() {
    $this->code = 'MITS_ImageDate';
    $this->name = 'MODULE_PRODUCT_' . strtoupper($this->code);
    $this->title = 'MITS_ImageDate &copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span>';
    $this->description = 'Dateidatum als timestamp bei Artikelbildern an Bildpfad anh&auml;ngen';
    $this->enabled = defined($this->name . '_STATUS') && constant($this->name . '_STATUS') == 'true' ? true : false;
    $this->sort_order = defined($this->name . '_SORT_ORDER') ? constant($this->name . '_SORT_ORDER') : 0;

    $this->translate();
  }

  function translate() {
    switch ($_SESSION['language_code']) {
      case 'de':
        $this->title = 'MITS_ImageDate &copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span>';
        $this->description = 'Dateidatum als timestamp bei Artikelbildern an Bildpfad anh&auml;ngen';
        break;
      default:
        $this->title = 'MITS_ImageDate &copy; by <span style="padding:2px;background:#ffe;color:#6a9;font-weight:bold;">Hetfield (MerZ IT-SerVice)</span>';
        $this->description = 'Attachatum as a timestamp for article pictures attach to the image path';
        break;
    }
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = xtc_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $this->name . "_STATUS'");
      $this->_check = xtc_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function keys() {
    define($this->name . '_STATUS_TITLE', TEXT_DEFAULT_STATUS_TITLE);
    define($this->name . '_STATUS_DESC', TEXT_DEFAULT_STATUS_DESC);
    define($this->name . '_SORT_ORDER_TITLE', TEXT_DEFAULT_SORT_ORDER_TITLE);
    define($this->name . '_SORT_ORDER_DESC', TEXT_DEFAULT_SORT_ORDER_DESC);

    return array(
      $this->name . '_STATUS',
      $this->name . '_SORT_ORDER',
    );
  }

  function install() {
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . $this->name . "_STATUS', 'true', 6, 1,'xtc_cfg_select_option(array(\'true\', \'false\'), ', now())");
    xtc_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('" . $this->name . "_SORT_ORDER', '100', 6, 2, now())");
  }

  function remove() {
    xtc_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE '" . $this->name . "_%'");
  }

  function productImage($returnName, $name, $type, $path) {

    if (is_file($path . $name)) {
      $imgdate = '?v=' . filemtime(DIR_FS_CATALOG . $path . $name);
      return $returnName . $imgdate;
    } else {
      return $returnName;
    }

  }

}
