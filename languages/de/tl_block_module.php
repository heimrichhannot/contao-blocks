<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package Blocks
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_block_module']['type'] = array('Typ', 'Bitte wählen Sie einen Typ aus.');
$GLOBALS['TL_LANG']['tl_block_module']['title'] = array('Titel', 'Geben Sie einen Titel an.');
$GLOBALS['TL_LANG']['tl_block_module']['module'] = array('Modul', 'Bitte wählen Sie ein Modul aus.');
$GLOBALS['TL_LANG']['tl_block_module']['articleAlias'] = array('Bezogener Artikel', 'Bitte wählen Sie den Artikel aus, den Sie einfügen möchten.');
$GLOBALS['TL_LANG']['tl_block_module']['imgSRC'] = array('Vorschaubild', 'Wählen Sie ein Vorschaubild für den Artikel aus.');
$GLOBALS['TL_LANG']['tl_block_module']['addVisibility'] = array('Zeige Modul auf bestimmten Seiten', 'Einschränkung der Sichtbarkeit des Moduls für bestimmte Seiten');
$GLOBALS['TL_LANG']['tl_block_module']['pages'] = array('Seitenfilter', 'Seiten angeben auf denen dieses Modul angezeigt werden soll, oder nicht.');
$GLOBALS['TL_LANG']['tl_block_module']['addPageDepth'] = array('Seitenvererbung aktivieren', 'Soll der Seitenfilter auch auf untergeordnete Seiten angewandt werden?');
$GLOBALS['TL_LANG']['tl_block_module']['hide'] = array('Modul verstecken', 'Soll das Modul für bestimmte Nutzer versteckt werden (betrifft das Frontend-Login)?');
$GLOBALS['TL_LANG']['tl_block_module']['keywords'] = array('Parameter', 'Parameter wie "auto_item" angeben um dieses Modul von Seiten mit diesen Parametern aus- bzw. einzuschließen. Negation mit z.B. "!auto_item" möglich. Mehrere Parameter durch ein Komma trennen.');
$GLOBALS['TL_LANG']['tl_block_module']['feature'] = array('Block-Element hervorheben', 'Heben Sie das Block-Element hervor und steuern die Anzeige geziehlt über Cookies.');
$GLOBALS['TL_LANG']['tl_block_module']['feature_start'] = array('Anzeigen ab', 'Das Element erst ab diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_block_module']['feature_stop'] = array('Anzeigen bis', 'Das Element nur bis zu diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG']['tl_block_module']['feature_count'] = array('Anzahl Ansichten je Besucher', 'Legen Sie fest wie oft das Block-Element pro Besucher angezeigt wird. Geben Sie 0 ein wenn as Block-Element immer angezeigt werden soll.');
$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_name'] = array('Cookie-Name', 'Geben Sie einen einzigartigen Cookie-Namen an in dem die Anzahl der Ansichten gespeichert wird.');
$GLOBALS['TL_LANG']['tl_block_module']['feature_cookie_expire'] = array('Cookie-Dauer', 'Geben Sie die Dauer in Millisekunden an, die der Cookie beim Besucher gespeichert werden soll, danach beginnt das Hervorheben erneut.');
$GLOBALS['TL_LANG']['tl_block_module']['feature_cssID'] = array('CSS-ID/Klasse überschreiben', 'Überschreiben Sie die ID und Klassen, wenn das Block-Element hervorgehoben wird.');
$GLOBALS['TL_LANG']['tl_block_module']['addWrapper'] = array('Wrapper hinzufügen', 'Erzeugen Sie eine Wrapper DIV mit einer eindeutigen CSS-ID und beliebig viele Klassen.');
$GLOBALS['TL_LANG']['tl_block_module']['headline'] = array('Überschrift', 'Hier können Sie dem Wrapper eine Überschrift hinzufügen.');
$GLOBALS['TL_LANG']['tl_block_module']['cssID'] = array('CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen eingeben.');
$GLOBALS['TL_LANG']['tl_block_module']['space'] = array('Abstand davor und dahinter', 'Hier können Sie den Abstand vor und nach dem Block-Element in Pixeln eingeben. Sie sollten Inline-Styles jedoch nach Möglichkeit vermeiden und den Abstand in einem Stylesheet definieren.');
$GLOBALS['TL_LANG']['tl_block_module']['customTpl'] = array('Individuelles Wrapper-Template', 'Hier können Sie das Wrapper-Template überschreiben (Standard: blocks_wrapper).');
$GLOBALS['TL_LANG']['tl_block_module']['customBlockTpl'] = array('Individuelles Block-Template', 'Hier können Sie das Block-Template überschreiben (Standard: block_unsearchable).');




/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_legend'] = 'Typ';
$GLOBALS['TL_LANG']['tl_block_module']['title_legend'] = 'Titel';
$GLOBALS['TL_LANG']['tl_block_module']['article_legend'] = 'Artikel';
$GLOBALS['TL_LANG']['tl_block_module']['module_legend'] = 'Modul';
$GLOBALS['TL_LANG']['tl_block_module']['page_legend'] = 'Seiten';
$GLOBALS['TL_LANG']['tl_block_module']['feature_legend'] = 'Hervorheben';
$GLOBALS['TL_LANG']['tl_block_module']['hide_legend'] = 'Modul verstecken';
$GLOBALS['TL_LANG']['tl_block_module']['expert_legend'] = 'Experten-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_block_module']['new'] = array('Neues Block-Element','Neues Block-Element erstellen');
$GLOBALS['TL_LANG']['tl_block_module']['edit']	= array('Inhaltselemente bearbeiten','Block-Element ID Inhaltselemente %s bearbeiten');
$GLOBALS['TL_LANG']['tl_block_module']['editheader']	= array('Block-Element bearbeiten','Block-Element ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_block_module']['copy'] = array('Block-Element duplizieren','Block-Element ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_block_module']['delete']= array('Block-Element löschen','Block-Element ID %s löschen');
$GLOBALS['TL_LANG']['tl_block_module']['show'] = array('Block-Element anzeigen','Block-Element ID %s anzeigen');

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['default'] = 'Modul';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['article'] = 'Artikel';
$GLOBALS['TL_LANG']['tl_block_module']['type_reference']['content'] = 'Inhaltselemente';
$GLOBALS['TL_LANG']['tl_block_module']['exclude'] = 'Alle Seiten außer den folgenden';
$GLOBALS['TL_LANG']['tl_block_module']['include'] = 'Nur auf den folgenden Seiten';
$GLOBALS['TL_LANG']['tl_block_module']['dont_hide'] = 'Nicht verstecken';
$GLOBALS['TL_LANG']['tl_block_module']['hide_logged_in'] = 'Für eingeloggte User verstecken';
$GLOBALS['TL_LANG']['tl_block_module']['hide_not_logged_in'] = 'Für nicht eingeloggte User verstecken';

/**
 * Misc
 */
$GLOBALS['TL_LANG']['tl_block_module']['contentElements'] = 'Inhaltselemente';