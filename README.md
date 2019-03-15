# Blocks

Contao blocks module is a site-dependent container, that manipulates the visibility of elements.

## Features

### Hooks

Name | Arguments | Expected return value | Description
---- | --------- | --------------------- | -----------
renderCustomBlockModule | $objBlockModule, $strReturn | the rendered module as string | Add custom block module type rendering

### Modules

Name | Description
---- | -----------
ModuleBlock | Checks the visibility of the block on the current page and renders it according to his type (article, content, module)

## Help

### Get auto_item title in breadcrumb

Due a limitation in contao it is not possible to set the auto_item title from the breadcrumb hook (instead the containing page is shown twice), so you need to change the `mod_breadcrumb` template instead:

```php
// Find this lines:
 
<?php if ($item['isActive']): ?>
    <li class="active<?php if ($item['class']): ?> <?= $item['class'] ?><?php endif; ?> last"><?= $item['pageTitle'] ?: $item['title'] ?></li>

// Replace '<?= $item['pageTitle'] ?: $item['title'] ?>' with '{{page::pageTitle}}'

<?php if ($item['isActive']): ?>
    <li class="active<?php if ($item['class']): ?> <?= $item['class'] ?><?php endif; ?> last">{{page::pageTitle}}</li>
```