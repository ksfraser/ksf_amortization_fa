<?php

/**
 * FrontAccounting Amortization Module Controller
 * 
 * Routes requests to appropriate views/actions using Ksfraser\HTML library
 * - Default: List loans
 * - ?action=admin: Admin settings
 * - ?action=create: Create new loan
 * - ?action=report: Generate reports
 * 
 * @package AmortizationModule
 */

use Ksfraser\HTML\Elements\Div;
use Ksfraser\HTML\Elements\Heading;
use Ksfraser\HTML\Elements\HtmlA;
use Ksfraser\HTML\Elements\HtmlParagraph;
use Ksfraser\HTML\Elements\HtmlString;
use Ksfraser\HTML\HtmlAttribute;
use Ksfraser\Amortizations\FA\AmortizationMenuBuilder;

global $path_to_root, $db;

// Get action from query parameter
$action = isset($_GET['action']) ? $_GET['action'] : 'default';

// Get FrontAccounting table prefix (TB_PREF is defined by FA, typically '0_')
$dbPrefix = defined('TB_PREF') ? TB_PREF : '0_';

// Require Composer autoloaders
// Load both main project autoloader AND module autoloader for FA-specific classes
$mainAutoload = __DIR__ . '/../../vendor/autoload.php';
$moduleAutoload = __DIR__ . '/vendor/autoload.php';

// Load main project autoloader (required for core classes)
if (file_exists($mainAutoload)) {
    require_once $mainAutoload;
}

// Load module autoloader (required for FA-specific classes like FADataProvider)
if (file_exists($moduleAutoload)) {
    require_once $moduleAutoload;
}

// Load local menu builder class
require_once __DIR__ . '/MenuBuilder.php';

// Start FA page (includes header, sets up page context)
// Only call if running within FrontAccounting
if (function_exists('page')) {
    page(_("Amortization Module"));
}

// Display navigation menu on all pages
$menuBuilder = new AmortizationMenuBuilder($path_to_root);
echo $menuBuilder->build();

// Route to appropriate view based on action
switch ($action) {
    case 'admin':
        // Admin settings for GL account mappings
        include __DIR__ . '/views/admin_settings.php';
        break;
        
    case 'admin_selectors':
        // Manage selector options
        include __DIR__ . '/views/admin_selectors.php';
        break;
        
    case 'create':
        // Create new loan
        include __DIR__ . '/views/user_loan_setup.php';
        break;
        
    case 'report':
        // Generate reports
        $reportingPath = __DIR__ . '/../../src/Ksfraser/Amortizations/reporting.php';
        if (file_exists($reportingPath)) {
            include $reportingPath;
        } else {
            echo (new Heading(3))->setText('Amortization Reports')->render();
            $p = new HtmlParagraph(new HtmlString('Reports feature coming soon...'));
            echo $p->getHtml();
        }
        break;
        
    case 'default':
    default:
        // List loans and provide navigation
        echo (new Heading(2))->setText('Amortization Loans')->render();
        
        // Include loan list view
        $viewPath = __DIR__ . '/../../src/Ksfraser/Amortizations/view.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            $p = new HtmlParagraph(new HtmlString('Loan list view coming soon...'));
            echo $p->getHtml();
        }
        break;
}

// End FA page (includes footer, closes page wrapper)
// Only call if running within FrontAccounting
if (function_exists('end_page')) {
    end_page();
}
?>
