<?php

    class WidgetsHelper extends FormHelper {
        var $helpers = array('Html', 'NiceHead', 'Form');

        function TagChooser($fieldName, $options = array()) {
            $this->setEntity($fieldName);
            $view =& ClassRegistry::getObject('view');
            $out = '';
            $div = true;
            $divOptions = array();
            $id = $this->domId($fieldName);

            if (!isset($options['multiple'])) {
                $options['multiple'] = 'multiple';
            }

            if (!isset($options['options'])) {
                    $view =& ClassRegistry::getObject('view');
                    $varName = Inflector::variable(Inflector::pluralize(preg_replace('/_id$/', '', $this->field())));
                    $varOptions = $view->getVar($varName);
                    if (is_array($varOptions)) {
                            $options['options'] = $varOptions;
                    }
            }

            if (array_key_exists('div', $options)) {
                    $div = $options['div'];
                    unset($options['div']);
            }

            if (!empty($div)) {
                    $divOptions['class'] = 'input';
                    $divOptions = $this->addClass($divOptions, $id.'_chooser');
                    if (is_string($div)) {
                            $divOptions['class'] = $div;
                    } elseif (is_array($div)) {
                            $divOptions = array_merge($divOptions, $div);
                    }
                    if (in_array($this->field(), $this->fieldset['validates'])) {
                            $divOptions = $this->addClass($divOptions, 'required');
                    }
                    if (!isset($divOptions['tag'])) {
                            $divOptions['tag'] = 'div';
                    }
            }

            $selected = array();
            if (array_key_exists('selected', $options)) {
                    $selected = $options['selected'];
                    unset($options['selected']);
            }
            if (!empty($selected)) {
                $temp = array();
                foreach ($selected as $s) { $temp[$s] = 0; }
                $selected = $temp;
            }

            $error = null;
            if (isset($options['error'])) {
                    $error = $options['error'];
                    unset($options['error']);
            }

            $options = array_merge(array('options' => array()), $options);
            $list = $options['options'];
            unset($options['options']);

            $out .= "<table><tr><td>";
            $out .= $this->_mySelect($fieldName, null, null, array_merge($options, array('id'=>$id.'_left', 'name'=>$id.'_left', 'class'=>'tc-left')), false);

            $out .= "</td><td style=\"width: 48px;\">".$this->Html->image('/img/arrow_right.png',
                      array('style'=>"float: right;", 'id'=>$id."_add", 'class'=>'hand', 'alt'=>'Tag zufügen'));
            $out .= $this->Html->image('/img/arrow_left.png',
                      array('style'=>"float: left;", 'id'=>$id."_del", 'class'=>'hand', 'alt'=>'Tag entfernen'));
            $out .= "</td><td>";
            $out .= "<input type=\"text\" id=\"".$id."_new\" class=\"tc-new\" /><a id=\"".$id."_addnew\" class=\"tc-newbutton\" >(+)</a><br />";
            $out .= $this->_mySelect($fieldName, null, null, array_merge($options, array('id'=>$id.'_right', 'name'=>$id.'_right', 'class'=>'tc-right')), false);
            $out .= "</td></tr></table>";

            if (isset($divOptions) && isset($divOptions['tag'])) {
                    $tag = $divOptions['tag'];
                    unset($divOptions['tag']);
                    $out = $this->Html->tag($tag, $out, $divOptions);
            }
            $out = $this->input('Tag', array('label'=>false,'div'=>'input', 'error'=>false)).$out;

            $this->NiceHead->js('/js/jquery/jquery.selso.js');
            $this->NiceHead->js('/js/jquery/tagchooser.js');
            $this->NiceHead->jsOnReady("$('#".$id."').tagchooser();");
            return $out;
        }


        function DatePicker($fieldName, $options=array()) {

            $options['type'] = 'text';
            $options['div'] = "input date";
            $out = $this->input($fieldName, $options);

            $this->NiceHead->js('/js/jquery/date.js');
            $this->NiceHead->js('/js/jquery/date_de.js');
            $this->NiceHead->js('/js/jquery/jquery.datePicker.js');
            $this->NiceHead->css('/css/datePicker.css');
            $this->NiceHead->jsOnReady('Date.format="dd.mm.yyyy";
                  $(".date input").datePicker({startDate:"01/01/1996"});');

            return $this->output($out);
        }

        function passwords($fieldName, $options=array()) {
            $options['div'] = 'input text required passmeter';
            $out =  $this->input($fieldName, $options);
            $options['label'] = $options['label'].' wiederholen';
            $out .= $this->input($fieldName.'2', $options);

            $this->NiceHead->js('/js/jquery/jquery.passmeter.js');
            $this->NiceHead->jsOnReady('$(".passmeter input").attachPassMeter({imgsPath:"/links/img/"});');

            return $this->output($out);
        }


        function editor($name, $settings = array()) {
            $config = $this->_build($settings);
            $settings = $config['settings'];
            $default = $config['default'];
            $textarea = array_diff_key($settings, $default);
            $textarea = am($textarea, array('type' => 'textarea'));
            $editor = $this->input($name, $textarea);
            $id = '#'.parent::domId($name);
            $this->NiceHead->jsOnReady('$(function() { $("'.$id.'").markItUp('.$settings['settings'].', { previewParserPath:"'.$settings['parser'].'" } ); });');
            $this->NiceHead->js('/js/jquery/markitup/jquery.markitup.pack.js');
            return $this->output($editor);
        }


        function parse($content, $parser = 'bbcode') {
            switch ($parser) {
                case 'bbcode':
                    App::import('Vendor', 'HTML_BBCodeParser', array('file' => 'BBCodeParser.php'));
                    App::import('Vendor', 'AutoPFormatter', array('file' => 'autop.php'));
                    $engine = new HTML_BBCodeParser();
                    $breaker = new AutoPFormatter();
                    $engine->setText($content);
                    $engine->parse();
                    $parsed = $breaker->Parse($engine->getParsed());
                    break;
            }
            return $parsed;
        }

        function modlink($ruleset, $params=array(), $htmlparams=array(), $escape=true) {
            if (!is_array($ruleset)) $ruleset = array($ruleset);
            $out = '';
            $search = array();

            foreach ($params as $k => $v) { $search[] = '/\{'.$k.'\}/'; }

            foreach ($ruleset as $rule) {
                if (!is_array($rule)) $rule = array($rule);
                switch ($rule['rule'][0]) {
                    case 'true':
                        $out = $this->Html->link($rule['title'], $rule['url'], $htmlparams, false, $escape);
                        continue 2;
                    case 'eq':
                        $lft = preg_replace($search, $params, $rule['rule'][1]);
                        $rgt = preg_replace($search, $params, $rule['rule'][2]);
                        if ($lft == $rgt) $out = $this->Html->link($rule['title'], $rule['url'], $htmlparams, false, $escape);
                        continue 2;
                }

            }

            $out = preg_replace($search, $params, $out);

            return $out;
        }



        function beforeRender() {
            $this->NiceHead->js('/js/jquery/jquery.js');
            $this->NiceHead->js('/js/jquery/jquery.bgiframe.js');
            $this->NiceHead->js('/js/jquery/toggle.js');

            $this->NiceHead->jsOnReady("$('.toggle').mytoggle(false);");

            $this->NiceHead->css('/css/widgets.css');
        }


        function _mySelect($fieldName, $options = array(), $selected = null, $attributes = array(), $showEmpty = '') {
			$select = array();
			$showParents = false;
			$escapeOptions = true;
			$style = null;
			$tag = null;

			if (isset($attributes['escape'])) {
				$escapeOptions = $attributes['escape'];
				unset($attributes['escape']);
			}
			$attributes = $this->_initInputField($fieldName, $attributes);

			if (is_string($options) && isset($this->__options[$options])) {
				$options = $this->__generateOptions($options);
			} elseif (!is_array($options)) {
				$options = array();
			}
			if (isset($attributes['type'])) {
				unset($attributes['type']);
			}
			if (in_array('showParents', $attributes)) {
				$showParents = true;
				unset($attributes['showParents']);
			}

			if (!isset($selected)) {
				$selected = $attributes['value'];
			}

			if (isset($attributes) && array_key_exists('multiple', $attributes)) {
				if ($attributes['multiple'] === 'checkbox') {
					$tag = $this->Html->tags['checkboxmultiplestart'];
					$style = 'checkbox';
				} else {
					$tag = $this->Html->tags['selectmultiplestart'];
				}
			} else {
				$tag = $this->Html->tags['selectstart'];
			}

			if (!empty($tag)) {
				$this->__secure();
				$select[] = sprintf($tag, $attributes['name'], $this->_parseAttributes($attributes, array('name', 'value')));
			}

			if ($showEmpty !== null && $showEmpty !== false && !(empty($showEmpty) && (isset($attributes) && array_key_exists('multiple', $attributes)))) {
				if ($showEmpty === true) {
					$showEmpty = '';
				}
				$options = array_reverse($options, true);
				$options[''] = $showEmpty;
				$options = array_reverse($options, true);
			}
			$select = array_merge($select, $this->__selectOptions(array_reverse($options, true), $selected, array(), $showParents, array('escape' => $escapeOptions, 'style' => $style)));

			if ($style == 'checkbox') {
				$select[] = $this->Html->tags['checkboxmultipleend'];
			} else {
				$select[] = $this->Html->tags['selectend'];
			}
			return $this->output(implode("\n", $select));
		}


        function _build($settings) {
            $default = array(   'set' => 'default',
                                'skin' => 'markitup',
                                'settings' => 'mySettings',
                                'parser' => '');
            $settings = am($default, $settings);
            if ($settings['parser']) {
                $settings['parser'] = $this->Html->url($settings['parser']);
            }
            $this->NiceHead->js('/js/jquery/markitup/sets/'.$settings['set'].'/set.js', false);
            $this->NiceHead->css('/js/jquery/markitup/skins/'.$settings['skin'].'/style.css', null, null, false);
            $this->NiceHead->css('/js/jquery/markitup/sets/'.$settings['set'].'/style.css', null, null, false);

            return array('settings' => $settings, 'default' => $default);
        }

    }

?>