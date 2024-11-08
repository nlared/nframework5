<?php

use PhpOffice\PhpWord\Element\TextBox;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\TemplateProcessor as PhpWordTemplateProcessor;
use PhpOffice\PhpWord\Writer\Word2007\Element\Container;

/**
 * Custom PhpWord template processor.
 *
 * Extends the generic template processor of PhpWord by means to
 * replace a macro with HTML markup content.
 */
class TemplateProcessor extends PhpWordTemplateProcessor {

    /**
     * Replaces a macro block with the given HTML markup.
     *
     * PhpWord's variables replacing doesn't allow to use HTML markup as
     * macro replacement content.
     *
     * This method is a workaround that uses the PhpWord Html service to
     * parse Html into PhpWord elements, adds them as children to a
     * container element (TextBox), then uses the Container writer to
     * write its children elements only.
     *
     * @param string $search
     *   The macro (variable) name.
     * @param string $markup
     *   The HTML markup as a string.
     */
    public function setHtmlBlockValue($search, $markup)
    {
      // Create a dummy container element for the content.
      $wrapper = new TextBox();

      // Parse the given HTML markup and add it as child elements
      // to the container.
      Html::addHtml($wrapper, $markup);

      // Render the child elements of the container.
      $xmlWriter = new XMLWriter();
      $containerWriter = new Container($xmlWriter, $wrapper, false);
      $containerWriter->write();

      // Replace the macro parent block with the rendered contents.
      $this->replaceXmlBlock($search, $xmlWriter->getData(), 'w:p');
    }

}

?>