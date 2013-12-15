<meta charset="<?php echo $this['system']->document->getCharset(); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if($this['config']->get('responsive', true)): ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php endif; ?>
<?php if (isset($error)): ?>
<title><?php echo $error; ?> - <?php echo $title; ?></title>
<?php else: ?>
<jdoc:include type="head" />
<?php endif; ?>
<link rel="apple-touch-icon-precomposed" href="<?php echo $this['path']->url('theme:apple_touch_icon.png'); ?>">
<?php

// get html head data
$head = $this['system']->document->getHeadData();

// remove deprecated meta-data (html5)
unset($head['metaTags']['http-equiv']);
unset($head['metaTags']['standard']['title']);
unset($head['metaTags']['standard']['rights']);
unset($head['metaTags']['standard']['language']);

$this['system']->document->setHeadData($head);

// load jQuery
JHtml::_('jquery.framework');

// get styles and scripts
$styles  = $this['asset']->get('css');
$scripts = $this['asset']->get('js');

// load bootstrap styles
if ($this['config']->get('bootstrap', true) && $bootstrap = $this['path']->url('css:bootstrap.css')) {
	$styles->prepend($this['asset']->createFile($bootstrap));
}

// customizer mode
if ($this['config']['customizer']) {
	foreach ($this['config']['less']['files'] as $file => $less) {
		foreach ($styles as $style) {
			if ($url = $style->getUrl() and substr($url, -strlen($file)) == $file) {
				$style['data-file'] = $file;
				break;
			}
		}
	}
}
// developer mode
else if ($this['config']['dev_mode']) {

    // less files
    $files = array();
	foreach ($styles as $style) {

        if (!$style instanceof Warp\Asset\FileAsset) continue;

		$file = sprintf('less:%s.less', basename($style->getPath(), '.css'));

		if ($this['path']->path($file)) {
            $filter  = $this['assetfilter']->create(array('CssImportResolver', 'CssRewriteUrl'));
            $files[] = array('target' => basename($style->getPath()), 'source' => $this['asset']->createString($this['asset']->createFile($file)->getContent($filter).PHP_EOL.$this['asset']->createFile(sprintf('theme:styles/%s/style.less', $this['config']['style']))->getContent(), array_merge($style->getOptions(), array('type' => 'text/less')))->getContent());
            $styles->replace($style, $this['asset']->createString('', array_merge($style->getOptions(), array('data-file' => basename($style->getPath())))));
        }
    }

    $this['asset']->addString('js', 'var less = { env: "development" }, files = '.json_encode($files).';');
    $this['asset']->addFile('js', 'warp:vendor/jquery/jquery-less.js');
    $this['asset']->addFile('js', 'warp:vendor/jquery/jquery-rtl.js');
    $this['asset']->addFile('js', 'warp:vendor/less/less.js');
    $this['asset']->addFile('js', 'warp:js/developer.js');
}
// compress styles and scripts
else if ($compression = $this['config']['compression'] or $this['config']['direction'] == 'rtl') {

	$options = array();
	$filters = array('CssImportResolver', 'CssRewriteUrl');

	// set options
	if ($compression == 3) {
		$options['Gzip'] = true;
	}

	// set filter
	if ($this['config']['direction'] == 'rtl') {
		$filters[] = 'CssRtl';
	}

	if ($compression >= 2 && ($this['useragent']->browser() != 'msie' || version_compare($this['useragent']->version(), '8.0', '>='))) {
		$filters[] = 'CssImageBase64';
	}

	// cache styles and check for remote styles
	if ($styles) {
		$styles = array($this['asset']->cache('theme.css', $styles, array_merge($filters, array('CssCompressor')), $options));
		foreach ($styles[0] as $style) {
			if ($style->getType() == 'File' && !$style->getPath()) {
				$styles[] = $style;
			}
		}
	}

	// cache scripts and check for remote scripts
	if ($scripts) {
		$scripts = array($this['asset']->cache('theme.js', $scripts, array('JsCompressor'), $options));
		foreach ($scripts[0] as $script) {
			if ($script->getType() == 'File' && !$script->getPath()) {
				$scripts[] = $script;
			}
		}
	}

	// compress joomla styles and scripts
	$head = $this['system']->document->getHeadData();
	$data = array('styleSheets' => array(), 'scripts' => array());

	foreach ($head['styleSheets'] as $style => $meta) {

		if (preg_match('/\.css$/i', $style)) {
			$asset = $this['asset']->createFile($style);
			if ($asset->getPath()) {
				$style = $this['asset']->cache(basename($style), $asset, array('CssImportResolver', 'CssRewriteUrl', 'CssCompressor'), $options)->getUrl();
			}
		}

		$data['styleSheets'][$style] = $meta;
	}

	foreach ($head['scripts'] as $script => $meta) {

		if (preg_match('/\.js$/i', $script)) {
			$asset = $this['asset']->createFile($script);
			if ($asset->getPath()) {
				$script = $this['asset']->cache(basename($script), $asset, array('JsCompressor'), $options)->getUrl();
			}
		}

		$data['scripts'][$script] = $meta;
	}

	$this['system']->document->setHeadData(array_merge($head, $data));
}

// add styles
if ($styles) {
	foreach ($styles as $style) {
		if ($url = $style->getUrl()) {
			printf("<link %srel=\"stylesheet\" href=\"%s\">\n", isset($style['data-file']) ? 'data-file="'.$style['data-file'].'" ' : '', $url);
		} else {
			printf("<style %s>%s</style>\n", $this['field']->attributes($style->getOptions(), array('base_path', 'base_url')), $style->getContent());
		}
	}
}

// add scripts
if ($scripts) {
	foreach ($scripts as $script) {
		if ($url = $script->getUrl()) {
			printf("<script src=\"%s\"></script>\n", $url);
		} else {
			printf("<script>%s</script>\n", $script->getContent());
		}
	}
}

$this->output('head');
