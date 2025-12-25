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

// Check if we have required selector data
if (empty($paymentFrequencies) || empty($borrowerTypes)) {
    echo '<p style="color: red;">Required selector data not found. Please configure selectors in <a href="?action=admin_selectors">Admin → Manage Selectors</a> first.</p>';
    echo '<p>Required selectors: <strong>payment_frequency</strong>, <strong>borrower_type</strong></p>';
    return;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_loan'])) {
    try {
        // TODO: Implement loan creation logic using AmortizationModel
        echo '<p style="color: green; font-weight: bold;">Loan creation functionality coming soon. Data validation passed.</p>';
        echo '<pre>Submitted data: ' . print_r($_POST, true) . '</pre>';
    } catch (Exception $e) {
        echo '<p style="color: red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}

// Build form with all required fields
echo '<form method="post" action="">';

echo '<div style="margin-bottom: 15px;">';
echo '<label for="principal">Principal Amount ($):</label><br>';
echo '<input type="number" name="principal" id="principal" min="1" step="0.01" required style="width: 200px;" placeholder="10000.00">';
echo '</div>';

echo '<div style="margin-bottom: 15px;">';
echo '<label for="interest_rate">Annual Interest Rate (%):</label><br>';
echo '<input type="number" name="interest_rate" id="interest_rate" min="0" step="0.01" required style="width: 200px;" placeholder="5.00">';
echo '</div>';

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

echo '<div style="margin-bottom: 15px;">';
echo '<label for="start_date">Start Date:</label><br>';
echo '<input type="date" name="start_date" id="start_date" required style="width: 200px;" value="' . date('Y-m-d') . '">';
echo '</div>';

echo '<div style="margin-bottom: 15px;">';
echo '<label for="borrower_name">Borrower Name:</label><br>';
echo '<input type="text" name="borrower_name" id="borrower_name" required style="width: 300px;" placeholder="John Doe">';
echo '</div>';

echo '<div>';
echo '<input type="submit" name="create_loan" value="Create Loan" class="button">';
echo ' <a href="?" class="button">Cancel</a>';
echo '</div>';

echo '</form>';

