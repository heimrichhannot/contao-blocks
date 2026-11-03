<?php

declare(strict_types=1);

// Run against an installed Contao application, e.g.:
// php tests/ContentParentContextTest.php /var/www/html theme
use Contao\Controller;
use Contao\Input;
use Contao\ManagerBundle\HttpKernel\ContaoKernel;
use Symfony\Component\HttpFoundation\Request;

$project = $argv[1] ?? throw new RuntimeException('Supply the Contao project directory.');
$context = $argv[2] ?? 'theme';
require $project.'/vendor/autoload.php';
$query = ['do' => 'themes', 'table' => 'tl_content', 'id' => '1'];
if ($context === 'block') {
    $query['ptable'] = 'tl_block_module';
}
$request = Request::create('https://www.familienrecht.dav.hhdev/contao', 'GET', $query);
$kernel = ContaoKernel::fromRequest($project, $request);
$kernel->boot();
$container = $kernel->getContainer();
$container->get('request_stack')->push($request);
$container->get('contao.framework')->initialize();
foreach ($query as $key => $value) {
    Input::setGet($key, $value);
}
Controller::loadDataContainer('tl_content');
$expected = $context === 'block' ? 'tl_block_module' : 'tl_theme';
$actual = $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] ?? null;
if ($actual !== $expected) {
    throw new RuntimeException(sprintf('%s content must use %s, got %s', $context, $expected, $actual ?? 'null'));
}
Controller::loadDataContainer('tl_block_module');
$definition = $GLOBALS['TL_DCA']['tl_block_module']['list']['operations']['children'];
if (($definition['icon'] ?? null) !== 'children.svg' || !isset($definition['button_callback'])) {
    throw new RuntimeException('Block children operation must retain its icon and button callback.');
}
$dc = (new ReflectionClass(Contao\DC_Table::class))->newInstanceWithoutConstructor();
$operation = new Contao\CoreBundle\DataContainer\DataContainerOperation('children', $definition, ['id' => 1, 'type' => 'content'], $dc);
(new HeimrichHannot\Blocks\EventListener\DataContainer\BlockModule\ListOperationsEditButtonListener())($operation);
$href = $operation['href'];
parse_str(html_entity_decode($href), $parameters);
if (($parameters['ptable'] ?? null) !== 'tl_block_module') {
    throw new RuntimeException('Block children link must explicitly select tl_block_module.');
}
printf("PASS: %s content parent and block children link\n", $context);
$kernel->shutdown();
