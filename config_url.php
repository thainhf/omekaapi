<?php$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];$root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);$config['base_url'] = $root;$config['omekas_url'] = 'https://omeka.p-set.org/api/';$config['omekas_url_item'] = 'https://omeka.p-set.org/s/Prawase/item/';$config['omekas_url_img'] = 'https://omeka.p-set.org/';$config['nd'] ="n.d.";?>