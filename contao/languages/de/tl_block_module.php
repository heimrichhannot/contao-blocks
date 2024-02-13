<?php

$lang = &$GLOBALS['TL_LANG']['tl_block_module'];

/**
 * Fields
 */
$lang['type']                    = ['Typ', 'Bitte wählen Sie einen Typ aus.'];
$lang['title']                   = ['Titel', 'Geben Sie einen Titel an.'];
$lang['contentBlockModuleAlias'] = ['Bezogenes Block-Inhaltselement', 'Wählen Sie hier ein Inhaltselement aus, das sich in einem Block befindet.'];
$lang['module']                  = ['Modul', 'Bitte wählen Sie ein Modul aus.'];
$lang['articleAlias']            = ['Bezogener Artikel', 'Bitte wählen Sie den Artikel aus, den Sie einfügen möchten.'];
$lang['imgSRC']                  = ['Vorschaubild', 'Wählen Sie ein Vorschaubild für den Artikel aus.'];
$lang['addVisibility']           = ['Zeige Modul auf bestimmten Seiten', 'Einschränkung der Sichtbarkeit des Moduls für bestimmte Seiten'];
$lang['pages']                   = ['Seitenfilter', 'Seiten angeben auf denen dieses Modul angezeigt werden soll, oder nicht.'];
$lang['addPageDepth']            = ['Seitenvererbung aktivieren', 'Soll der Seitenfilter auch auf untergeordnete Seiten angewandt werden?'];
$lang['hide']                    = ['Modul verstecken', 'Soll das Modul für bestimmte Nutzer versteckt werden (betrifft das Frontend-Login)?'];
$lang['keywords']                = ['Parameter', 'Parameter wie "auto_item" angeben um dieses Modul von Seiten mit diesen Parametern aus- bzw. einzuschließen. Negation mit z.B. "!auto_item" möglich. Mehrere Parameter durch ein Komma trennen.'];
$lang['keywordPages']            = ['Parameter Seitenfilter', 'Legen Sie fest, auf welchen Seiten die Parameter berücksichtigt werden sollen..'];
$lang['feature']                 = ['Block-Element hervorheben', 'Heben Sie das Block-Element hervor und steuern die Anzeige geziehlt über Cookies.'];
$lang['feature_start']           = ['Anzeigen ab', 'Das Element erst ab diesem Tag auf der Webseite anzeigen.'];
$lang['feature_stop']            = ['Anzeigen bis', 'Das Element nur bis zu diesem Tag auf der Webseite anzeigen.'];
$lang['feature_count']           = ['Anzahl Ansichten je Besucher', 'Legen Sie fest wie oft das Block-Element pro Besucher angezeigt wird. Geben Sie 0 ein wenn as Block-Element immer angezeigt werden soll.'];
$lang['feature_cookie_name']     = ['Cookie-Name', 'Geben Sie einen einzigartigen Cookie-Namen an in dem die Anzahl der Ansichten gespeichert wird.'];
$lang['feature_cookie_expire']   = ['Cookie-Dauer', 'Geben Sie die Dauer in Millisekunden an, die der Cookie beim Besucher gespeichert werden soll, danach beginnt das Hervorheben erneut.'];
$lang['feature_cssID']           = ['CSS-ID/Klasse überschreiben', 'Überschreiben Sie die ID und Klassen, wenn das Block-Element hervorgehoben wird.'];
$lang['addWrapper']              = ['Wrapper hinzufügen', 'Erzeugen Sie eine Wrapper DIV mit einer eindeutigen CSS-ID und beliebig viele Klassen.'];
$lang['backgroundSRC']           = ['Hintergrundbild', 'Wählen Sie ein Hintergrundbild aus der Dateiverwaltung aus.'];
$lang['backgroundSize']          = ['Bildgröße', 'Hier können Sie die Abmessungen des Bildes und den Skalierungsmodus festlegen.'];
$lang['headline']                = ['Überschrift', 'Hier können Sie dem Wrapper eine Überschrift hinzufügen.'];
$lang['cssID']                   = ['CSS-ID/Klasse', 'Hier können Sie eine ID und beliebig viele Klassen eingeben.'];
$lang['space']                   = ['Abstand davor und dahinter', 'Hier können Sie den Abstand vor und nach dem Block-Element in Pixeln eingeben. Sie sollten Inline-Styles jedoch nach Möglichkeit vermeiden und den Abstand in einem Stylesheet definieren.'];
$lang['customTpl']               = ['Individuelles Wrapper-Template', 'Hier können Sie das Wrapper-Template überschreiben (Standard: blocks_wrapper).'];
$lang['customBlockTpl']          = ['Individuelles Block-Template', 'Hier können Sie das Block-Template überschreiben (Standard: block_unsearchable).'];
$lang['uncached']                = ['Vom Cache ausschließen', 'Diesen Bereich bei aktiven Seitencache nicht zwischenspeichern.'];
$lang['start']                   = ['Anzeigen ab', 'Block-Element erst ab diesem Tag auf der Webseite anzeigen.'];
$lang['stop']                    = ['Anzeigen bis', 'Block-Element nur bis zu diesem Tag auf der Webseite anzeigen.'];
$lang['published']               = ['Veröffentlichen', 'Wählen Sie diese Option zum Veröffentlichen.'];

/**
 * Legends
 */
$lang['type_legend']                 = 'Typ';
$lang['title_legend']                = 'Titel';
$lang['article_legend']              = 'Artikel';
$lang['content_block_module_legend'] = 'Block-Inhaltselement';
$lang['module_legend']               = 'Modul';
$lang['page_legend']                 = 'Seiten';
$lang['feature_legend']              = 'Hervorheben';
$lang['hide_legend']                 = 'Modul verstecken';
$lang['expert_legend']               = 'Experten-Einstellungen';


/**
 * Buttons
 */
$lang['new']        = ['Neues Block-Element', 'Neues Block-Element erstellen'];
$lang['edit']       = ['Inhaltselemente bearbeiten', 'Block-Element ID Inhaltselemente %s bearbeiten'];
$lang['editheader'] = ['Block-Element bearbeiten', 'Block-Element ID %s bearbeiten'];
$lang['copy']       = ['Block-Element duplizieren', 'Block-Element ID %s duplizieren'];
$lang['delete']     = ['Block-Element löschen', 'Block-Element ID %s löschen'];
$lang['show']       = ['Block-Element anzeigen', 'Block-Element ID %s anzeigen'];
$lang['toggle']     = ['Block-Element veröffentlichen', 'Block ID %s veröffentlichen/verstecken'];

/**
 * References
 */
$lang['type_reference']['default']          = 'Modul';
$lang['type_reference']['article']          = 'Artikel';
$lang['type_reference']['content']          = 'Inhaltselemente';
$lang['type_reference']['included_content'] = 'Bezogenes Block-Inhaltselement';
$lang['exclude']                            = 'Alle Seiten außer den folgenden';
$lang['include']                            = 'Nur auf den folgenden Seiten';
$lang['dont_hide']                          = 'Nicht verstecken';
$lang['hide_logged_in']                     = 'Für eingeloggte User verstecken';
$lang['hide_not_logged_in']                 = 'Für nicht eingeloggte User verstecken';

/**
 * Misc
 */
$lang['contentElements'] = 'Inhaltselemente';
$lang['includedContentElements'] = 'Bezogene Inhaltselemente';
