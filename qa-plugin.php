<?php


if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}


qa_register_plugin_layer('qlist-desc-layer.php', 'QList Desc Layer');
qa_register_plugin_module('module', 'qlist-desc-admin-form.php', 'qlist_desc_admin_form', 'QList Desc');
