<?php
/*
	Question2Answer by Gideon Greenspan and contributors
	http://www.question2answer.org/

	File: qa-plugin/mouseover-layer/qa-mouseover-admin-form.php
	Description: Generic module class for mouseover layer plugin to provide admin form and default option


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

class qlist_desc_admin_form
{
	public function option_default($option)
	{
		if ($option === 'qlist_desc_max_len')
			return 80;
		if ($option === 'qlist_desc_min_len')
			return 30;
		return null;
	}


	public function admin_form(&$qa_content)
	{
		$saved = qa_clicked('qlist_desc_save_button');

		if ($saved) {
			qa_opt('qlist_desc_on', (int) qa_post_text('qlist_desc_on_field'));
			qa_opt('qlist_desc_max_len', (int) qa_post_text('qlist_desc_max_len_field'));
			qa_opt('qlist_desc_min_len', (int) qa_post_text('qlist_desc_min_len_field'));
		}


		return array(
			'ok' => $saved ? 'Settings saved' : null,

			'fields' => array(
				array(
					'label' => 'Show question description as part of question listing?',
					'type' => 'checkbox',
					'value' => qa_opt('qlist_desc_on'),
					'tags' => 'name="qlist_desc_on_field" id="qlist_desc_on_field"',
				),

				array(
					'id' => 'qlist_desc_max_len_display',
					'label' => 'Maximum length:',
					'suffix' => 'characters',
					'type' => 'number',
					'value' => (int) qa_opt('qlist_desc_max_len'),
					'tags' => 'name="qlist_desc_max_len_field"',
				),
				array(
					'id' => 'qlist_desc_min_len_display',
					'label' => 'Minimum length:',
					'suffix' => 'characters',
					'type' => 'number',
					'value' => (int) qa_opt('qlist_desc_min_len'),
					'tags' => 'name="qlist_desc_min_len_field"',
				),
			),

			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'name="qlist_desc_save_button"',
				),
			),
		);
	}
}
