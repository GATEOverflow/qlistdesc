<?php
/*
   Question2Answer by Gideon Greenspan and contributors
   http://www.question2answer.org/

   File: qa-plugin/mouseover-layer/qa-mouseover-layer.php
   Description: Theme layer class for mouseover layer plugin


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

class qa_html_theme_layer extends qa_html_theme_base
{
	//public function q_list_and_form($q_list)
	public function q_list($q_list)
	{
		if (!empty($q_list['qs']) && qa_opt('qlist_desc_on') && qa_opt('site_theme')!='Polaris') { // first check it is not an empty list and the feature is turned on

			// Collect the question ids of all items in the question list (so we can do this in one DB query)

			$postids = array();
			foreach ($q_list['qs'] as $question) {
				if (isset($question['raw']['postid']))
					$postids[] = $question['raw']['postid'];
			}

			if (!empty($postids)) {
				$nquestions = false;
				// Retrieve the content for these questions from the database
				$parts=explode('/',$this->request);
				$maxlength = qa_opt('qlist_desc_max_len');
				$minlength = qa_opt('qlist_desc_min_len');
				if($this->template === 'blogs' ||
					$this->template === 'user-blogs' ||
					(count($parts) >=1 && $parts[0]=='blog') ||
					(count($parts) >=1 && $parts[0] == 'admin' && $parts[1]=='blog')
				) {
					$nquestions = true;

					$result = qa_db_query_sub('SELECT postid, content, title, format FROM ^blogs WHERE postid IN (#)', $postids);
				}
				else if($this->template === 'exams' ||(count($parts) >=1 && $parts[0]==='exam')){
					$nquestions = true;
					$result = qa_db_query_sub('SELECT postid, "" as content,title, "html" as  format FROM ^exams WHERE postid IN (#)', $postids);
				}

				else
					$result = qa_db_query_sub('SELECT postid,title, content, format FROM ^posts WHERE postid IN (#)', $postids);
				$postinfo = qa_db_read_all_assoc($result, 'postid');

				// Get the regular expression fragment to use for blocked words and the maximum length of content to show

				$blockwordspreg = qa_get_block_words_preg();

				// Now add the popup to the title for each question
				if(!$nquestions) {
					foreach ($q_list['qs'] as $index => $question) {
						if (isset($postinfo[$question['raw']['postid']])) {
							$thispost = $postinfo[$question['raw']['postid']];
							$text = qa_viewer_text($thispost['content'], $thispost['format'], array('blockwordspreg' => $blockwordspreg));
							$text = htmlspecialchars(preg_replace('/\s+/', ' ', $text));  // Remove duplicated blanks, new line characters, tabs, etc
							//$text = qa_shorten_string_line($text, $maxlength);
							$posttitle =  $thispost['title'];
							$title = $posttitle ? $posttitle : '';

							if(strlen($title) < $minlength)
							{
								$text = '<br>'. qa_shorten_string_line($text, $maxlength -strlen($title));
								$q_list['qs'][$index]['title'] .= $text;
								//file_put_contents("/tmp/out2.txt", json_encode($q_list['qs'][$index]['title'] ));
							}
						}
					}
				}
			}
		}

		//parent::q_list_and_form($q_list); // call back through to the default function
		parent::q_list($q_list); // call back through to the default function
	}

}
