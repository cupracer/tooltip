# Example implementation

```HTML+PHP
<?php

require (dirname(__FILE__) . '/vendor/autoload.php');

use ThumbSniper\common\TooltipSettings;
use ThumbSniper\tooltip\Tooltip;


TooltipSettings::setPreview("marked");
TooltipSettings::setWidth(182);
TooltipSettings::setEffect("fade1");
TooltipSettings::setPosition("top");
TooltipSettings::setSiteUrl((isset($_SERVER['HTTPS'])?'https':'http').'://' . $_SERVER['HTTP_HOST']);

$thumbsniper = new Tooltip();

?>

<html>
<head>
    <title>Example</title>
    <?php
    echo $thumbsniper->getQtipCssHtmlTag();
    echo $thumbsniper->getInlineCss();
    ?>
</head>
<body>
<div style="margin-left: 400px; margin-top: 100px;">
    <h2>Test 1: external</h2>
    <a href="http://www.google.de">Google</a><br>
    <a href="http://www.wikipedia.org">Wikipedia</a><br>
    <a href="http://www.apple.com">Apple</a><br>
</div>
<div style="margin-left: 400px">
    <h2>Test 2: internal</h2>
    <a href="<?php echo TooltipSettings::getSiteUrl(); ?>">SELF</a><br>
</div>
<div style="margin-left: 400px">
    <h2>Test 3: marked</h2>
    <a href="http://www.google.de" class="thumbsniper">Google</a><br>
    <a href="http://www.wikipedia.org" class="thumbsniper">Wikipedia</a><br>
    <a href="http://www.apple.com" class="thumbsniper">Apple</a><br>
</div>
<div style="margin-left: 400px">
    <h2>Test 4: excluded</h2>
    <a href="http://www.google.de" class="nothumbsniper">Google</a><br>
    <a href="http://www.wikipedia.org" class="nothumbsniper">Wikipedia</a><br>
    <a href="http://www.apple.com" class="nothumbsniper">Apple</a><br>
</div>

<?php
echo $thumbsniper->getJqueryJavaScriptHtmlTag();
echo $thumbsniper->getQtipJavaScriptHtmlTags();
echo $thumbsniper->getInlineScripts();
?>
</body>
</html>
```
