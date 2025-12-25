<?php
// Admin Selectors View - Manage Selector Options
// This view handles management of loan types and interest calculation frequencies

use Ksfraser\HTML\Elements\Heading;
use Ksfraser\HTML\Elements\HtmlParagraph;
use Ksfraser\Amortizations\Repository\SelectorRepository;

global $db;
$dbPrefix = defined('TB_PREF') ? TB_PREF : '';

// Check if we're in a proper FA environment
if (!isset($db) || !$db) {
    echo (new Heading(2))->setText('Manage Selectors')->render();
    echo '<p>Database connection not available. This view must be accessed through FrontAccounting.</p>';
    return;
}

echo (new Heading(2))->setText('Manage Selectors')->render();

$selectorRepo = new SelectorRepository($db, 'ksf_selectors', $dbPrefix);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_option'])) {
        $selectorName = $_POST['selector_name'] === '_new' ? $_POST['new_selector_name'] : $_POST['selector_name'];
        $selectorRepo->add(
            $selectorName,
            $_POST['option_name'],
            $_POST['option_value']
        );
        echo '<p style="color: green;">Option added successfully.</p>';
    } elseif (isset($_POST['delete_option'])) {
        $selectorRepo->delete($_POST['option_id']);
        echo '<p style="color: green;">Option deleted successfully.</p>';
    }
}

// Get all selector names
$allSelectors = $selectorRepo->getAllSelectorNames();

// Display existing selectors
echo '<h3>Existing Selector Options</h3>';
foreach ($allSelectors as $selectorName) {
    echo '<div style="margin-bottom: 30px; border: 1px solid #ccc; padding: 15px;">';
    echo '<h4>' . htmlspecialchars($selectorName) . '</h4>';
    
    $options = $selectorRepo->getBySelectorName($selectorName);
    if (!empty($options)) {
        echo '<table border="1" cellpadding="5" style="border-collapse: collapse;">';
        echo '<tr><th>Option Name</th><th>Option Value</th><th>Action</th></tr>';
        foreach ($options as $opt) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($opt['option_name']) . '</td>';
            echo '<td>' . htmlspecialchars($opt['option_value']) . '</td>';
            echo '<td>';
            echo '<form method="post" style="display:inline;">';
            echo '<input type="hidden" name="option_id" value="' . $opt['id'] . '">';
            echo '<input type="submit" name="delete_option" value="Delete" onclick="return confirm(\'Are you sure?\');">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p><em>No options defined</em></p>';
    }
    echo '</div>';
}

// Add new option form
echo '<h3>Add New Selector Option</h3>';
echo '<form method="post" style="border: 1px solid #ccc; padding: 15px; max-width: 500px;">';

echo '<div style="margin-bottom: 10px;">';
echo '<label for="selector_name">Selector Name:</label><br>';
echo '<select name="selector_name" id="selector_name" required style="width: 100%;">';
foreach ($allSelectors as $name) {
    echo '<option value="' . htmlspecialchars($name) . '">' . htmlspecialchars($name) . '</option>';
}
echo '<option value="_new">-- Create New Selector --</option>';
echo '</select>';
echo '</div>';

echo '<div style="margin-bottom: 10px;" id="new_selector_div" style="display:none;">';
echo '<label for="new_selector_name">New Selector Name:</label><br>';
echo '<input type="text" name="new_selector_name" id="new_selector_name" style="width: 100%;">';
echo '</div>';

echo '<div style="margin-bottom: 10px;">';
echo '<label for="option_name">Option Display Name:</label><br>';
echo '<input type="text" name="option_name" id="option_name" required style="width: 100%;">';
echo '</div>';

echo '<div style="margin-bottom: 10px;">';
echo '<label for="option_value">Option Value:</label><br>';
echo '<input type="text" name="option_value" id="option_value" required style="width: 100%;">';
echo '</div>';

echo '<div>';
echo '<input type="submit" name="add_option" value="Add Option" class="button">';
echo '</div>';

echo '</form>';

echo '<script>
document.getElementById("selector_name").addEventListener("change", function() {
    var newDiv = document.getElementById("new_selector_div");
    if (this.value === "_new") {
        newDiv.style.display = "block";
        document.getElementById("new_selector_name").required = true;
    } else {
        newDiv.style.display = "none";
        document.getElementById("new_selector_name").required = false;
    }
});
</script>';
?>
