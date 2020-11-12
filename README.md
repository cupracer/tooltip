# Deprecation notice:
**This project is closed and no longer maintained. Updates are not expected.**

**The project code will remain publicly available, but could disappear at any time.**

# Example implementation

```HTML+PHP
<?php

require (dirname(__FILE__) . '/vendor/autoload.php');

use ThumbSniper\common\TooltipSettings;
use ThumbSniper\tooltip\Tooltip;


TooltipSettings::setPreview("all");
TooltipSettings::setWidth(182);
TooltipSettings::setEffect("fade1");
TooltipSettings::setPosition("top");
TooltipSettings::setStyle('jtools');
TooltipSettings::setSiteUrl((isset($_SERVER['HTTPS'])?'https':'http').'://' . $_SERVER['HTTP_HOST']);
TooltipSettings::setShowTitle(true);

$thumbsniper = new Tooltip();

?>

<html>
<head>
    <title>Example</title>
    <?php
    echo $thumbsniper->getQtipCssHtmlTag();
    echo $thumbsniper->getInlineCssHtmlTag();
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

<div style="margin-left: 400px">
    <h2>Test 5: protocol-relative</h2>
    <a href="//www.google.de">Google</a><br>
    <a href="//www.wikipedia.org">Wikipedia</a><br>
    <a href="//www.apple.com">Apple</a><br>
</div>

<div style="margin-left: 400px">
    <h2>Test 6: show title</h2>
    <a href="http://www.google.de" title="This is Google">Google</a><br>
    <a href="http://www.wikipedia.org" title="This is Wikipedia">Wikipedia</a><br>
    <a href="http://www.apple.com" title="This is Apple">Apple</a><br>
    <a href="http://www.apple.com" title="This is a very very very very very very very very very very very long title">Apple (long title)</a><br>
</div>

<?php
echo $thumbsniper->getJqueryJavaScriptHtmlTag();
echo $thumbsniper->getQtipJavaScriptHtmlTags();
echo $thumbsniper->getInlineScripts();
?>
</body>
</html>
```
