<?php
// User Loan Setup Form using SelectorRepository for dropdown options
use Ksfraser\HTML\Elements\HtmlInput;
use Ksfraser\HTML\Elements\HtmlForm;
use Ksfraser\HTML\Elements\HtmlSelect;
use Ksfraser\HTML\Elements\HtmlOption;
use Ksfraser\HTML\Elements\HtmlString;
use Ksfraser\HTML\Elements\Heading;
use Ksfraser\HTML\Elements\HtmlParagraph;
use Ksfraser\Amortizations\Repository\SelectorRepository;

global $db;

// Get database table prefix
$dbPrefix = defined('TB_PREF') ? TB_PREF : '';

// Check if we're in a proper FA environment
if (!isset($db) || !$db) {
    echo (new Heading(2))->setText('Create New Loan')->render();
    echo '<p>Database connection not available. This view must be accessed through FrontAccounting.</p>';
    return;
}

echo (new Heading(2))->setText('Create New Loan')->render();

// Initialize repository and fetch options
$selectorRepo = new SelectorRepository($db, 'ksf_selectors', $dbPrefix);
$paymentFrequencies = $selectorRepo->getBySelectorName('payment_frequency');
$borrowerTypes = $selectorRepo->getBySelectorName('borrower_type');

// Build form with proper labels
echo '<form method="post" action="">';

echo '<div style="margin-bottom: 15px;">';
echo '<label for="loan_term_years">Loan Term (Years):</label><br>';
echo '<input type="number" name="loan_term_years" id="loan_term_years" min="1" value="1" required style="width: 200px;">';
echo '</div>';

echo '<div style="margin-bottom: 15px;">';
echo '<label for="payment_frequency">Payment Frequency:</label><br>';
echo '<select name="payment_frequency" id="payment_frequency" required style="width: 200px;">';
foreach ($paymentFrequencies as $opt) {
    echo '<option value="' . htmlspecialchars($opt['option_value']) . '">' . htmlspecialchars($opt['option_name']) . '</option>';
}
echo '</select>';
echo '</div>';

echo '<div style="margin-bottom: 15px;">';
echo '<label for="borrower_type">Borrower Type:</label><br>';
echo '<select name="borrower_type" id="borrower_type" required style="width: 200px;">';
foreach ($borrowerTypes as $opt) {
    echo '<option value="' . htmlspecialchars($opt['option_value']) . '">' . htmlspecialchars($opt['option_name']) . '</option>';
}
echo '</select>';
echo '</div>';

echo '<div>';
echo '<input type="submit" name="submit" value="Create Loan" class="button">';
echo '</div>';

echo '</form>';

