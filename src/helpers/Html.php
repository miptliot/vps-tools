<?php

	namespace vps\tools\helpers;

	use Yii;

	/**
	 * Class Html
	 *
	 * @package vps\tools\helpers
	 */
	class Html extends \yii\helpers\BaseHtml
	{
		/**
		 * Overwritten method. By default i18n is used.
		 * ```php
		 * Html::a('Google', 'http://google.com', [ 'raw' => true]);
		 * // <a href="http://google.com">Google</a>
		 *
		 * Html::a('Home', Url::toRoute(['site/index']), [ 'raw' => true]);
		 * // <a href="site/index">Home</a>
		 * ```
		 *
		 * @inheritdoc
		 */
		public static function a ($text, $url = null, $options = [])
		{
			if (!isset($options[ 'raw' ]) or $options[ 'raw' ] == false)
				$text = Yii::tr($text);
			unset($options[ 'raw' ]);

			if (!isset($options[ 'title' ]))
				$options[ 'title' ] = $text;

			return parent::a($text, $url, $options);
		}

		/**
		 * Generates link with font-awesome icon as text.
		 * ```php
		 * Html::afa('google', 'http://google.com');
		 * // <a href="http://google.com"><i class="fa fa-google"></i></a>
		 *
		 * Html::afa('edit', Url::toRoute(['user/edit', 'id'=>'2']));
		 * // <a href="user/edit/2"><i class="fa fa-edit"></i></a>
		 * ```
		 *
		 * @inheritdoc
		 */
		public static function afa ($name, $url = null, $options = [])
		{
			if (!isset($options[ 'title' ]))
				$options[ 'title' ] = $name;

			return parent::a(self::fa($name, $options), $url, $options);
		}

		/**
		 * Creates button checkbox or radio group.
		 *```php
		 * Html::buttonGroup('Group', null, ['1'=>'first','2'=>'Second'], 'radio' );
		 * ```
		 *
		 * @link http://getbootstrap.com/javascript/#buttons-checkbox-radio
		 * @param string            $name Name for the inputs.
		 * @param string|array|null $selected Selected values.
		 * @param array             $items The data item used to generate the checkboxes. The array keys are the
		 *                                    input values, while the array values are the corresponding labels.
		 * @param string            $type Input type - checkbox/radio.
		 * @return string
		 */
		public static function buttonGroup ($name, $selected, $items, $type = 'checkbox')
		{
			if ($type == 'checkbox')
				return self::checkboxList($name, $selected, $items, [ 'class' => 'btn-group', 'data-toggle' => 'buttons', 'item' => function ($index, $label, $name, $checked, $value)
				{
					return self::label(self::checkbox($name, $checked, [ 'autocomplete' => 'off', 'value' => $value ]) . ' ' . $label, null, [ 'class' => 'btn btn-default' . ( $checked ? ' active' : '' ) ]);
				} ]);
			else
				return self::radioList($name, $selected, $items, [ 'class' => 'btn-group', 'data-toggle' => 'buttons', 'item' => function ($index, $label, $name, $checked, $value)
				{
					return self::label(self::radio($name, $checked, [ 'autocomplete' => 'off', 'value' => $value ]) . ' ' . $label, null, [ 'class' => 'btn btn-default' . ( $checked ? ' active' : '' ) ]);
				} ]);
		}

		/**
		 * Creates button with FontAwesome icon.
		 *
		 * ```php
		 * Html::buttonFa('Save', 'save', [ 'raw' => true ]);
		 * // <button type="button"><i class="fa fa-save margin"></i>Save</button>
		 * ```
		 *
		 * @param string $text Button text.
		 * @param string $fa Icon name.
		 * @param array  $options Additional options.
		 * @return string
		 */
		public static function buttonFa ($text, $fa, $options = [])
		{
			$class = 'fa fa-' . $fa;
			if (!empty(trim($text)))
				$class .= ' margin';

			$icon = self::tag('i', '', [ 'class' => $class ]);
			if (!isset($options[ 'title' ]))
				$options[ 'title' ] = $text;
			if (isset($options[ 'raw' ]) and $options[ 'raw' ] == true)
			{
				unset($options[ 'raw' ]);

				return parent::button($icon . $text, $options);
			}
			else
			{
				unset($options[ 'raw' ]);

				return parent::button($icon . Yii::t('app', $text), $options);
			}
		}

		/**
		 * Compresses HTML, removes all new lines, tabs and spaces.
		 * ```php
		 * $input =  '<a href="#test" >adsda</a> <div> </div>dcl sskd';
		 * Html::compress($input);
		 * // return '<a href="#test">adsda</a><div></div>dcl sskd'
		 * ```
		 *
		 * @param string $input
		 * @param array  $preserve
		 * @return string Compressed output.
		 */
		public static function compress ($input, $preserve = [ 'textarea', 'pre' ])
		{
			$output = trim($input);

			// First, store tags which should not been minified.
			$holders = [];
			foreach ($preserve as $tag)
			{
				$output = preg_replace_callback('/\\s*(<' . $tag . '\\b[^>]*?>[\\s\\S]*?<\\/' . $tag . '>)\\s*/i', function ($matches) use (&$holders, $tag)
				{
					$holders[ $tag ][] = $matches[ 1 ];

					return '___' . $tag . '_' . count($holders[ $tag ]) . '___';
				}, $output);
			}

			$filters = [
				// remove HTML comments except IE conditions
				'/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s' => '',

				// remove comments in the form /* */
				'/\/+?\s*\*[\s\S]*?\*\s*\/+/'                         => '',

				// remove spaces before closing
				'/\s+\>/'                                             => '>',

				// shorten multiple white spaces between tags
				'/>\s{1,}</'                                          => '><',

				// shorten multiple white spaces
				'/\s{2,}/'                                            => ' ',

				// collapse new lines
				'/(\r?\n)/'                                           => '',
			];
			$output = preg_replace(array_keys($filters), array_values($filters), $output);

			// Put back holders.
			foreach ($preserve as $tag)
			{
				$output = preg_replace_callback('/___' . $tag . '_(\d+)___/i', function ($matches) use ($holders, $tag)
				{
					return $holders[ $tag ][ $matches[ 1 ] - 1 ];
				}, $output);
			}

			return $output;
		}

		/**
		 * Generates Font-Awesome icon.
		 * ```php
		 * Html::fa('icon');
		 * // <i class="fa fa-icon"></i>
		 * ```
		 *
		 * @param string $name Font-Awesome icon name. Will be appended after 'fa-' prefix.
		 * @param array  $options
		 * @return string
		 */
		public static function fa ($name, $options = [])
		{
			if (!isset($options[ 'class' ]))
				$options[ 'class' ] = '';
			$options[ 'class' ] = trim($options[ 'class' ] . ' fa fa-' . $name);

			return self::tag('i', '', $options);
		}

		/**
		 * Generates bootstrap list group with order displayed.
		 * ```php
		 * Html::listGroupOrder([['title'=> 'Title', 'order' => '1']]);
		 * ```
		 *
		 * @param array $items Array of elements with following structure:
		 *                       * title - item title.
		 *                       * order - Item order.
		 *                       * id
		 * @param array $options In addition to common options this could contain:
		 *                       * orderClass - class for the span element that contains item order number.
		 *                       * title - Title for the list group.
		 * @return string
		 */
		public static function listGroupOrder ($items, $options = [])
		{
			$options[ 'class' ] = isset($options[ 'class' ]) ? $options[ 'class' ] . ' list-group' : 'list-group';
			$orderClass = isset($options[ 'orderClass' ]) ? $options[ 'orderClass' ] : 'default';
			$title = isset($options[ 'title' ]) ? $options[ 'title' ] : 'title';

			$options[ 'item' ] = function ($item, $index) use ($orderClass, $title)
			{
				return self::tag('li', self::tag('span', $item[ 'order' ], [ 'class' => 'order order-' . $orderClass ]) . $item[ 'title' ], [ 'class' => 'list-group-item', 'data-id' => $item[ 'id' ], 'title' => $item[ $title ] ]);
			};

			return self::ul($items, $options);
		}

		/**
		 * Generates table.
		 * ```php
		 * Html::table([ 1, 2, 3 ], [ [ 2, 3, 4 ], [ 3, 4, 5 ] ], [ 'class' => 'table' ]);
		 * // <table class="table">
		 * //   <thead>
		 * //       <tr>
		 * //           <td>1</td>
		 * //           <td>2</td>
		 * //           <td>3</td>
		 * //       </tr>
		 * //   </thead>
		 * // <tbody>
		 * //       <tr>
		 * //           <td>2</td>
		 * //           <td>3</td>
		 * //           <td>4</td>
		 * //       </tr>
		 * //       <tr>
		 * //           <td>3</td>
		 * //           <td>4</td>
		 * //           <td>5</td>
		 * //       </tr>
		 * // </tbody>
		 * // </table>
		 * ```
		 *
		 * @param array $head
		 * @param array $body
		 * @param array $options
		 * @return string
		 */
		public static function table ($head, $body, $options = [])
		{
			$table = self::beginTag('table', $options);

			if (!empty($head) and is_array($head))
			{
				$table .= self::beginTag('thead');
				$table .= self::beginTag('tr');
				foreach ($head as $item)
					$table .= self::tag('th', $item);
				$table .= self::endTag('tr');
				$table .= self::endTag('thead');
			}

			$table .= self::beginTag('tbody');
			foreach ($body as $row)
			{
				$table .= self::beginTag('tr');
				foreach ($row as $item)
					$table .= self::tag('td', $item);
				$table .= self::endTag('tr');
			}
			$table .= self::endTag('tbody');

			$table .= self::endTag('table');

			return $table;
		}
	}