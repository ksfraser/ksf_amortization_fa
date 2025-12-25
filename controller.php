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

global $path_to_root, $db;

// Get action from query parameter
$action = isset($_GET['action']) ? $_GET['action'] : 'default';

// Get FrontAccounting table prefix (TB_PREF is defined by FA, typically '0_')
$dbPrefix = defined('TB_PREF') ? TB_PREF : '0_';

// Require Composer autoloader - check multiple locations
// 1. FA module's own vendor (if composer install run here)
// 2. Parent project vendor (main repo)
$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',  // FA module vendor
    __DIR__ . '/../../vendor/autoload.php',  // Main project vendor
];

foreach ($autoloadPaths as $autoload) {
    if (file_exists($autoload)) {
        require_once $autoload;
        break;
    }
}

// Start FA page - wraps output with header, nav, and footer
// Only call if running within FrontAccounting (page() is a FA function)
if (function_exists('page')) {
    page(_("Amortization Module"));
}

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
        if (file_exists(__DIR__ . '/reporting.php')) {
            include __DIR__ . '/reporting.php';
        } else {
            echo (new Heading(3))->setText('Amortization Reports')->render();
            $p = new HtmlParagraph(new HtmlString('Reports feature coming soon...'));
            echo $p->getHtml();
            // TODO: Implement reporting interface
        }
        break;
        
    case 'default':
    default:
        // List loans and provide navigation
        echo (new Heading(2))->setText('Amortization Loans')->render();
        
        $nav = (new Div())
            ->addClass('module-nav')
            ->setAttribute('style', 'margin-bottom: 20px; text-align: right;');
        
        // Create links using HtmlA
        $createLink = new HtmlA(new HtmlString(''));
        $createLink->addHref($path_to_root . '/modules/amortization/controller.php?action=create', 'Add New Loan');
        $createLink->addAttribute(new HtmlAttribute('class', 'button'));
        
        $adminLink = new HtmlA(new HtmlString(''));
        $adminLink->addHref($path_to_root . '/modules/amortization/controller.php?action=admin', 'Admin Settings');
        $adminLink->addAttribute(new HtmlAttribute('class', 'button'));
        
        $selectorsLink = new HtmlA(new HtmlString(''));
        $selectorsLink->addHref($path_to_root . '/modules/amortization/controller.php?action=admin_selectors', 'Manage Selectors');
        $selectorsLink->addAttribute(new HtmlAttribute('class', 'button'));
        
        $reportLink = new HtmlA(new HtmlString(''));
        $reportLink->addHref($path_to_root . '/modules/amortization/controller.php?action=report', 'Reports');
        $reportLink->addAttribute(new HtmlAttribute('class', 'button'));
        
        // Build nav with links
        echo '<div class="module-nav" style="margin-bottom: 20px; text-align: right;">';
        echo $createLink->getHtml();
        echo ' ';
        echo $adminLink->getHtml();
        echo ' ';
        echo $selectorsLink->getHtml();
        echo ' ';
        echo $reportLink->getHtml();
        echo '</div>';
        
        // TODO: Implement loan list view
        $p = new HtmlParagraph(new HtmlString('Loan list view coming soon...'));
        echo $p->getHtml();
        break;
}

// End FA page - outputs footer and closes page wrapper
// Only call if running within FrontAccounting (end_page is a FA function)
if (function_exists('end_page')) {
    end_page();
}
?>
