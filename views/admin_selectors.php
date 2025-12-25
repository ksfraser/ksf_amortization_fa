<?php
// Admin Selectors View - Manage Selector Options
// This view handles management of loan types and interest calculation frequencies

use Ksfraser\HTML\Elements\Heading;
use Ksfraser\HTML\Elements\HtmlParagraph;

echo (new Heading(2))->setText('Manage Selectors')->render();
echo (new HtmlParagraph())->setText('Selector management coming soon...')->getHtml();
?>
