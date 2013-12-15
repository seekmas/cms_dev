<p class="uk-form-controls-condensed">
    <select data-style-selector name="<?php echo $name ?>" data-selected="<?php echo $value ?>">
        <?php
            if ($path = $this['path']->path('theme:styles')) {
                foreach (glob("$path/*") as $dir) {
                    echo str_replace('%s', basename($dir), '<option value="%s">%s</option>');
                }
            }
        ?>
    </select>
</p>