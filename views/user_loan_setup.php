<?php
use Ksfraser\HTML\Elements\HtmlInput;
use Ksfraser\HTML\Elements\HtmlForm;

// User Loan Setup Form with modern patterns
// Note: Selector data would come from database via controller injection
// For now, show fallback UI

$form = (new HtmlForm())->setMethod('post');

// Loan term field
$form->addChild(new HtmlInput()
    ->setType('number')
    ->setName('loan_term_years')
    ->setId('loan_term_years')
    ->setAttributes(['min' => '1', 'value' => '1', 'required' => 'required']));

// Submit button
$form->addChild(new HtmlInput()
    ->setType('submit')
    ->setName('submit')
    ->setAttribute('value', 'Submit'));

echo $form->toHtml();
