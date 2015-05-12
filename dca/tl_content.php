<?php

$dc = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Palettes
 */

$dc['palettes']['block'] = '{type_legend},type;{include_legend},block;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

/**
 * Fields
 */
$arrFields = array
(
	'block' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_content']['block'],
		'exclude'                 => true,
		'inputType'               => 'select',
		'options_callback'        => array('tl_content_block', 'getBlocks'),
		'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
		'wizard' => array
		(
			array('tl_content_block', 'editBlock')
		),
		'sql'                     => "int(10) unsigned NOT NULL default '0'"
	)
);

$dc['fields'] = array_merge($dc['fields'], $arrFields);


/**
 * Dynamically add the permission check and parent table
 */
if (Input::get('do') == 'themes')
{
	$dc['config']['ptable'] = 'tl_block_module';
	$dc['config']['onload_callback'][] = array('tl_content_block', 'checkPermission');
}

$GLOBALS['TL_DCA']['tl_content']['fields']['module']['wizard'] = array(
	array('tl_content_block', 'editModule')
);



class tl_content_block extends \Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function editBlock()
	{

	}


	public function getBlocks()
	{
		$arrBlocks = array();
		$objBlocks = $this->Database->prepare("SELECT m.id, m.title, t.name AS theme FROM tl_block_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE type=? ORDER BY t.name, m.title")->execute('content');

		if($objBlocks->numRows < 1) return $arrBlocks;

		while ($objBlocks->next())
		{
			$arrBlocks[$objModules->theme][$objBlocks->id] = $objBlocks->title . ' (ID ' . $objBlocks->id . ')';
		}

		return $arrBlocks;
	}

	/**
	 * Check permissions to edit table tl_content
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}


		// TODO
	}

	public function editModule(DataContainer $dc)
	{
		if($dc->value < 1)
		{
			return '';
		}

		$objModule = $this->Database->prepare("SELECT * FROM tl_module WHERE id = ? AND type = 'block'")->execute($dc->value);

		if($objModule->numRows)
		{
			$this->loadLanguageFile('tl_block');

			return ' <a href="contao/main.php?do=themes&amp;table=tl_block_module&amp;id=' . $objModule->block . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_block']['edit'][1]), $objModule->block).'" style="padding-left:3px">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
		}

		return ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value).'" style="padding-left:3px">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editalias'][0], 'style="vertical-align:top"') . '</a>';
	}
}