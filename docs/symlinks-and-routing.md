# ImpressCMS Symlinks and Routing System Guide

**A Comprehensive Guide for Junior PHP Developers**

## Table of Contents
1. [What are Symlinks in ImpressCMS?](#what-are-symlinks-in-impresscms)
2. [How Symlinks Work](#how-symlinks-work)
3. [Key Classes and Files](#key-classes-and-files)
4. [Practical Examples](#practical-examples)
5. [Building a Custom Routing System](#building-a-custom-routing-system)
6. [Debugging and Experimentation](#debugging-and-experimentation)
7. [Security Considerations](#security-considerations)
8. [Real-world Use Cases](#real-world-use-cases)

---

## What are Symlinks in ImpressCMS?

In ImpressCMS, **symlinks** (also called "Pages") are **database records** that map human-friendly URLs to specific modules and pages within your site. They are **not** Unix system symlinks (filesystem links), but rather database entries that function as URL aliases or routes.

### Key Characteristics:
- **Database-driven**: Stored in the `icmspage` database table
- **URL mapping**: Map custom URLs to specific module pages
- **SEO-friendly**: Create clean, memorable URLs for your content
- **Centralized management**: Managed through the admin interface (System Module → Symlink Manager)
- **Block positioning**: Enable selective block display based on URL patterns

### Example Use Case:
Instead of accessing a page via:
```
http://yoursite.com/modules/news/article.php?id=123
```

You can create a symlink to access it via:
```
http://yoursite.com/latest-news
```

---

## How Symlinks Work

### The Symlink Lifecycle

#### 1. Database Storage
Symlinks are stored in the `icmspage` table with the following structure:

```sql
CREATE TABLE icmspage (
  `page_id` mediumint(8) unsigned NOT NULL auto_increment,
  `page_moduleid` mediumint(8) unsigned NOT NULL default '1',
  `page_title` varchar(255) NOT NULL default '',
  `page_url` varchar(255) NOT NULL default '',
  `page_status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY (`page_id`)
);
```

#### 2. Field Descriptions

| Field | Type | Description |
|-------|------|-------------|
| `page_id` | mediumint(8) | Unique identifier for the symlink |
| `page_moduleid` | mediumint(8) | ID of the associated module (from modules table) |
| `page_title` | varchar(255) | Human-readable title/description of the page |
| `page_url` | varchar(255) | The URL path or pattern to match |
| `page_status` | tinyint(1) | Status flag: 1 = active/visible, 0 = inactive |

#### 3. Management Interface
Symlinks are managed through:
- **Location**: System Module → Admin → Pages (Symlinks)
- **Access**: `/modules/system/admin.php?fct=pages`
- **Operations**: Create, edit, delete, and toggle status of symlinks

#### 4. Routing Logic Interaction

The routing system interacts with symlinks primarily through the `icms_view_PageBuilder` class:

**File Location**: `/htdocs/libraries/icms/view/PageBuilder.php`

When a request comes in:
1. The `getPage()` method extracts the requested URL
2. It queries the `icmspage` table to find matching symlinks
3. If a match is found, it retrieves the associated `page_moduleid` and `page_id`
4. These values determine which blocks to display and how to route the request
5. If no match is found, default ImpressCMS routing takes over

---

## Key Classes and Files

### Core Classes

#### 1. `icms_data_page_Object`
**Location**: `/htdocs/libraries/icms/data/page/Object.php`

The base object class for symlink/page records.

```php
class icms_data_page_Object extends icms_ipf_Object {
    
    public function __construct(&$handler) {
        parent::__construct($handler);
        
        $this->quickInitVar('page_id', XOBJ_DTYPE_INT);
        $this->quickInitVar('page_moduleid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('page_title', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('page_url', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('page_status', XOBJ_DTYPE_INT, true);
    }
}
```

**Purpose**: Represents a single symlink record with getter/setter methods for each field.

#### 2. `icms_data_page_Handler`
**Location**: `/htdocs/libraries/icms/data/page/Handler.php`

The handler class for managing page/symlink operations.

```php
class icms_data_page_Handler extends icms_ipf_Handler {
    
    public function __construct(&$db) {
        parent::__construct($db, 'page', 'page_id', 'page_title', '', 'icms');
        $this->table = $db->prefix('icmspage');
        $this->className = 'icms_data_page_Object';
    }
    
    // Get list of pages with formatted keys
    public function getList($criteria = null, $limit = 0, $start = 0, $debug = false) {
        // Returns array with keys like "moduleid-pageid" => "page_title"
    }
    
    // Get page selection options for forms
    public function getPageSelOptions($value = null) {
        // Returns HTML options for select dropdowns
    }
}
```

**Purpose**: Provides CRUD operations and query methods for symlink records.

#### 3. `SystemPages`
**Location**: `/htdocs/modules/system/admin/pages/class/pages.php`

Extended object class used in the System module for admin interface.

```php
class SystemPages extends icms_data_page_Object {
    
    public function __construct(&$handler) {
        parent::__construct($handler);
        
        $this->setControl('page_status', 'yesno');
        $this->setControl('page_moduleid', array(
            'itemHandler' => 'pages', 
            'method' => 'getModulesArray', 
            'module' => 'system'
        ));
    }
    
    // Custom button for toggling status
    public function getCustomPageStatus() {
        // Returns HTML for status toggle button
    }
    
    // Get module name for display
    public function getCustomPageModuleid() {
        $modules = $this->handler->getModulesArray();
        return $modules[$this->getVar('page_moduleid')];
    }
    
    // Build preview link
    public function getViewItemLink($onlyUrl = false, $withimage = true, $userSide = false) {
        // Returns link to the symlinked page
    }
}
```

**Purpose**: Adds admin-specific functionality for managing symlinks through the web interface.

#### 4. `SystemPagesHandler`
**Location**: `/htdocs/modules/system/admin/pages/class/pages.php`

Extended handler with admin-specific methods.

```php
class SystemPagesHandler extends icms_data_page_Handler {
    
    public function __construct(&$db) {
        icms_ipf_Handler::__construct($db, 'pages', 'page_id', 'page_title', '', 'system');
        $this->table = $db->prefix('icmspage');
    }
    
    // Get array of installed modules
    public function getModulesArray($full = false) {
        // Returns array of modules with IDs and names
    }
    
    // Toggle symlink status
    public function changeStatus($page_id) {
        $page = $this->get($page_id);
        $page->setVar('page_status', !$page->getVar('page_status'));
        return $this->insert($page, TRUE);
    }
}
```

**Purpose**: Provides module and status management functionality for the admin interface.

### Routing Integration

#### `icms_view_PageBuilder::getPage()`
**Location**: `/htdocs/libraries/icms/view/PageBuilder.php` (lines 152-226)

This method is the heart of the symlink routing system:

```php
static public function getPage() {
    global $icmsConfig;
    
    if (is_array(self::$modid)) return self::$modid;
    
    // Get current URL
    $clean_request = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    $fullurl = icms::$urls['http'] . icms::$urls['httphost'] . $clean_request;
    $url = substr(str_replace(ICMS_URL, '', $fullurl), 1);
    
    // Query for matching symlinks
    $icms_page_handler = icms::handler('icms_data_page');
    $criteria = new icms_db_criteria_Compo(
        new icms_db_criteria_Item('page_url', $fullurl)
    );
    if (!empty($url)) {
        $criteria->add(new icms_db_criteria_Item('page_url', $url), 'OR');
    }
    $pages = $icms_page_handler->getCount($criteria);
    
    if ($pages > 0) {
        // Symlink found - use it for routing
        $pages = $icms_page_handler->getObjects($criteria);
        $page = $pages[0];
        $mid = (int) $page->getVar('page_moduleid');
        $pid = $page->getVar('page_id');
        // ... set module context based on symlink
    } else {
        // No symlink - use default routing
        // ... fall back to standard module detection
    }
    
    return self::$modid;
}
```

**Purpose**: Matches incoming URLs against symlinks and determines which module/page context to use for rendering.

---

## Practical Examples

### Example 1: Fetching Symlink Data

```php
<?php
// Get the page handler
$page_handler = icms::handler('icms_data_page');

// Fetch all active symlinks
$criteria = new icms_db_criteria_Item('page_status', 1);
$active_pages = $page_handler->getObjects($criteria);

foreach ($active_pages as $page) {
    echo "Title: " . $page->getVar('page_title') . "<br>";
    echo "URL: " . $page->getVar('page_url') . "<br>";
    echo "Module ID: " . $page->getVar('page_moduleid') . "<br>";
    echo "Status: " . ($page->getVar('page_status') ? 'Active' : 'Inactive') . "<br><br>";
}
```

### Example 2: Checking Symlink Status and Validity

```php
<?php
/**
 * Check if a URL has an active symlink
 * 
 * @param string $url The URL to check
 * @return bool|icms_data_page_Object Returns page object if found and active, false otherwise
 */
function checkSymlinkForUrl($url) {
    $page_handler = icms::handler('icms_data_page');
    
    // Create criteria for URL and active status
    $criteria = new icms_db_criteria_Compo();
    $criteria->add(new icms_db_criteria_Item('page_url', $url));
    $criteria->add(new icms_db_criteria_Item('page_status', 1));
    
    $pages = $page_handler->getObjects($criteria);
    
    if (count($pages) > 0) {
        return $pages[0];
    }
    
    return false;
}

// Usage
$url = 'about-us';
if ($page = checkSymlinkForUrl($url)) {
    echo "Symlink found!<br>";
    echo "Title: " . $page->getVar('page_title') . "<br>";
    echo "Module ID: " . $page->getVar('page_moduleid') . "<br>";
} else {
    echo "No active symlink for this URL.";
}
```

### Example 3: Creating a Symlink Programmatically

```php
<?php
/**
 * Create a new symlink
 * 
 * @param string $title Page title
 * @param string $url URL pattern
 * @param int $module_id Module ID
 * @param int $status Status (1 = active, 0 = inactive)
 * @return bool Success status
 */
function createSymlink($title, $url, $module_id, $status = 1) {
    $page_handler = icms::handler('icms_data_page');
    
    // Create new page object
    $page = $page_handler->create();
    
    // Set values
    $page->setVar('page_title', $title);
    $page->setVar('page_url', $url);
    $page->setVar('page_moduleid', $module_id);
    $page->setVar('page_status', $status);
    
    // Save to database
    return $page_handler->insert($page);
}

// Usage
$module_handler = icms::handler('icms_module');
$news_module = $module_handler->getByDirname('news');

if ($news_module) {
    $result = createSymlink(
        'Latest News',
        'latest-news',
        $news_module->getVar('mid'),
        1
    );
    
    if ($result) {
        echo "Symlink created successfully!";
    } else {
        echo "Failed to create symlink.";
    }
}
```

### Example 4: Wildcard URL Matching

ImpressCMS supports wildcard symlinks using the asterisk (*) suffix:

```php
<?php
/**
 * Check for wildcard symlink matches
 * This is how ImpressCMS handles wildcard URLs internally
 */
function findWildcardSymlink($current_url) {
    $page_handler = icms::handler('icms_data_page');
    
    // Get all active pages
    $criteria = new icms_db_criteria_Item('page_status', 1);
    $pages = $page_handler->getObjects($criteria);
    
    foreach ($pages as $page) {
        $page_url = $page->getVar('page_url');
        
        // Check if this is a wildcard URL (ends with *)
        if (substr($page_url, -1) == '*') {
            $pattern = substr($page_url, 0, -1);
            
            // Check if current URL starts with the pattern
            if (substr($current_url, 0, strlen($pattern)) == $pattern) {
                return $page;
            }
        }
    }
    
    return null;
}

// Usage
$page = findWildcardSymlink('news/article/123');
if ($page) {
    echo "Matched wildcard symlink: " . $page->getVar('page_title');
}
```

---

## Building a Custom Routing System

### Step 1: Create a Router Class

Build a custom router that leverages ImpressCMS symlinks:

```php
<?php
/**
 * Custom Router Class
 * File: /modules/mymodule/class/router.php
 */
class MyModuleRouter {
    
    private $page_handler;
    private $routes = array();
    
    public function __construct() {
        $this->page_handler = icms::handler('icms_data_page');
    }
    
    /**
     * Register a route manually
     */
    public function addRoute($pattern, $callback, $module_id) {
        $this->routes[] = array(
            'pattern' => $pattern,
            'callback' => $callback,
            'module_id' => $module_id
        );
    }
    
    /**
     * Check current URL against symlinks
     */
    public function matchSymlink($url) {
        // Clean and sanitize URL
        $clean_url = filter_var($url, FILTER_SANITIZE_URL);
        
        // Check for exact match first
        $criteria = new icms_db_criteria_Compo();
        $criteria->add(new icms_db_criteria_Item('page_url', $clean_url));
        $criteria->add(new icms_db_criteria_Item('page_status', 1));
        
        $pages = $this->page_handler->getObjects($criteria);
        
        if (count($pages) > 0) {
            return $pages[0];
        }
        
        // Check for wildcard matches
        return $this->matchWildcardSymlink($clean_url);
    }
    
    /**
     * Match wildcard patterns
     */
    private function matchWildcardSymlink($url) {
        $criteria = new icms_db_criteria_Item('page_status', 1);
        $pages = $this->page_handler->getObjects($criteria);
        
        foreach ($pages as $page) {
            $page_url = $page->getVar('page_url');
            
            if (substr($page_url, -1) == '*') {
                $pattern = substr($page_url, 0, -1);
                if (substr($url, 0, strlen($pattern)) == $pattern) {
                    return $page;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Dispatch to appropriate handler
     */
    public function dispatch($url) {
        // Try symlink routing first
        $page = $this->matchSymlink($url);
        
        if ($page) {
            return $this->handleSymlinkRoute($page);
        }
        
        // Fall back to manual routes
        foreach ($this->routes as $route) {
            if ($this->patternMatch($route['pattern'], $url)) {
                return call_user_func($route['callback']);
            }
        }
        
        // Fall back to default ImpressCMS routing
        return $this->handleDefaultRoute();
    }
    
    /**
     * Handle a symlink-based route
     */
    private function handleSymlinkRoute($page) {
        $module_id = $page->getVar('page_moduleid');
        $page_id = $page->getVar('page_id');
        
        // Get module information
        $module_handler = icms::handler('icms_module');
        $module = $module_handler->get($module_id);
        
        if (!$module) {
            return $this->handleNotFound();
        }
        
        // Set global module context
        global $icmsModule;
        $icmsModule = $module;
        
        // Return routing information
        return array(
            'module' => $module->getVar('dirname'),
            'module_id' => $module_id,
            'page_id' => $page_id,
            'page_title' => $page->getVar('page_title')
        );
    }
    
    /**
     * Handle default routing
     */
    private function handleDefaultRoute() {
        // Let ImpressCMS handle routing normally
        return null;
    }
    
    /**
     * Handle 404 errors
     */
    private function handleNotFound() {
        header("HTTP/1.0 404 Not Found");
        return array('error' => 404);
    }
    
    /**
     * Simple pattern matching
     */
    private function patternMatch($pattern, $url) {
        // Convert pattern to regex
        $regex = '#^' . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $pattern) . '$#';
        return preg_match($regex, $url);
    }
}
```

### Step 2: Integrate Router in Module

Create an integration point in your module:

```php
<?php
/**
 * Module router integration
 * File: /modules/mymodule/index.php
 */

include '../../mainfile.php';
include 'class/router.php';

// Initialize router
$router = new MyModuleRouter();

// Get current URL path
$request_uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
$url_path = str_replace(ICMS_URL . '/', '', icms::$urls['http'] . icms::$urls['httphost'] . $request_uri);

// Add custom routes (optional)
$router->addRoute('custom-page/{id}', function() {
    // Handle custom route
    echo "Custom route handler";
}, 1);

// Dispatch
$route_info = $router->dispatch($url_path);

if ($route_info && isset($route_info['error']) && $route_info['error'] == 404) {
    // Handle 404
    include ICMS_ROOT_PATH . '/header.php';
    echo '<h1>Page Not Found</h1>';
    include ICMS_ROOT_PATH . '/footer.php';
    exit;
}

if ($route_info) {
    // Route matched - handle it
    echo "Routing to module: " . $route_info['module'] . "<br>";
    echo "Page ID: " . $route_info['page_id'] . "<br>";
    echo "Page Title: " . $route_info['page_title'] . "<br>";
    
    // Include appropriate module file based on routing
    // include ICMS_ROOT_PATH . '/modules/' . $route_info['module'] . '/page.php';
}
```

### Step 3: Extend Core Handlers

For advanced functionality, extend the core handler:

```php
<?php
/**
 * Extended page handler with custom methods
 * File: /modules/mymodule/class/CustomPageHandler.php
 */
class CustomPageHandler extends icms_data_page_Handler {
    
    /**
     * Get pages by URL pattern
     */
    public function getPagesByPattern($pattern) {
        $criteria = new icms_db_criteria_Compo();
        $criteria->add(new icms_db_criteria_Item('page_url', '%' . $pattern . '%', 'LIKE'));
        $criteria->add(new icms_db_criteria_Item('page_status', 1));
        
        return $this->getObjects($criteria);
    }
    
    /**
     * Get pages for a specific module
     */
    public function getModulePages($module_id, $active_only = true) {
        $criteria = new icms_db_criteria_Item('page_moduleid', $module_id);
        
        if ($active_only) {
            $criteria->add(new icms_db_criteria_Item('page_status', 1));
        }
        
        return $this->getObjects($criteria);
    }
    
    /**
     * Get navigation array for a module
     */
    public function getModuleNavigation($module_id) {
        $pages = $this->getModulePages($module_id, true);
        $nav = array();
        
        foreach ($pages as $page) {
            $nav[] = array(
                'title' => $page->getVar('page_title'),
                'url' => ICMS_URL . '/' . $page->getVar('page_url'),
                'id' => $page->getVar('page_id')
            );
        }
        
        return $nav;
    }
    
    /**
     * Check if URL is already in use
     */
    public function isUrlAvailable($url, $exclude_id = 0) {
        $criteria = new icms_db_criteria_Item('page_url', $url);
        
        if ($exclude_id > 0) {
            $criteria->add(new icms_db_criteria_Item('page_id', $exclude_id, '!='));
        }
        
        $count = $this->getCount($criteria);
        return ($count == 0);
    }
    
    /**
     * Bulk update status
     */
    public function bulkUpdateStatus($page_ids, $status) {
        $success = true;
        
        foreach ($page_ids as $id) {
            $page = $this->get($id);
            if ($page && !$page->isNew()) {
                $page->setVar('page_status', $status);
                if (!$this->insert($page, true)) {
                    $success = false;
                }
            }
        }
        
        return $success;
    }
}
```

### Step 4: Fallback Strategy

Always provide fallback to default ImpressCMS routing:

```php
<?php
/**
 * Routing with fallback
 */
function routeRequest($url) {
    $router = new MyModuleRouter();
    
    // Try custom routing
    $route = $router->dispatch($url);
    
    if ($route === null) {
        // No custom route matched - use ImpressCMS default
        // The PageBuilder will handle standard routing
        return false; // Let ImpressCMS continue normal flow
    }
    
    return $route;
}
```

---

## Debugging and Experimentation

### Using the Admin Interface

1. **Access Symlink Manager**:
   - Login as admin
   - Navigate to: System → Administration → Pages
   - URL: `/modules/system/admin.php?fct=pages`

2. **Create Test Symlinks**:
   - Click "Create New Page"
   - Fill in:
     - **Title**: Descriptive name (e.g., "Test About Page")
     - **URL**: Path to match (e.g., "about-us" or "test/*")
     - **Module**: Select the target module
     - **Status**: Active (1) or Inactive (0)
   - Save

3. **Toggle Status**:
   - Click the status icon (green checkmark or red X)
   - Use this to quickly enable/disable symlinks for testing

### Inspecting the Database

Connect to your MySQL database and query the `icmspage` table:

```sql
-- View all symlinks
SELECT * FROM icmspage;

-- View active symlinks only
SELECT * FROM icmspage WHERE page_status = 1;

-- Find symlinks for a specific module
SELECT p.*, m.name as module_name, m.dirname
FROM icmspage p
JOIN icms_modules m ON p.page_moduleid = m.mid
WHERE p.page_moduleid = 2;

-- Search by URL pattern
SELECT * FROM icmspage WHERE page_url LIKE '%news%';

-- Check for duplicate URLs
SELECT page_url, COUNT(*) as count
FROM icmspage
GROUP BY page_url
HAVING count > 1;
```

### Writing Test Scripts

Create a test script to experiment with symlinks:

```php
<?php
/**
 * Test script for symlink functionality
 * File: /test-symlinks.php (in your site root, temporary)
 */

include './mainfile.php';

// Test 1: List all symlinks
echo "<h2>Test 1: All Symlinks</h2>";
$page_handler = icms::handler('icms_data_page');
$pages = $page_handler->getObjects();

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Title</th><th>URL</th><th>Module ID</th><th>Status</th></tr>";
foreach ($pages as $page) {
    echo "<tr>";
    echo "<td>" . $page->getVar('page_id') . "</td>";
    echo "<td>" . $page->getVar('page_title') . "</td>";
    echo "<td>" . $page->getVar('page_url') . "</td>";
    echo "<td>" . $page->getVar('page_moduleid') . "</td>";
    echo "<td>" . ($page->getVar('page_status') ? 'Active' : 'Inactive') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test 2: Test URL matching
echo "<h2>Test 2: URL Matching</h2>";
$test_urls = array('about-us', 'news/article', 'contact');

foreach ($test_urls as $test_url) {
    $criteria = new icms_db_criteria_Compo();
    $criteria->add(new icms_db_criteria_Item('page_url', $test_url));
    $criteria->add(new icms_db_criteria_Item('page_status', 1));
    
    $matches = $page_handler->getObjects($criteria);
    
    echo "URL: <strong>$test_url</strong> - ";
    if (count($matches) > 0) {
        echo "MATCHED: " . $matches[0]->getVar('page_title');
    } else {
        echo "No match";
    }
    echo "<br>";
}

// Test 3: Create test symlink
echo "<h2>Test 3: Create Test Symlink</h2>";
$test_page = $page_handler->create();
$test_page->setVar('page_title', 'Test Page ' . time());
$test_page->setVar('page_url', 'test-' . time());
$test_page->setVar('page_moduleid', 1);
$test_page->setVar('page_status', 0); // Inactive by default

if ($page_handler->insert($test_page)) {
    echo "Test symlink created successfully!<br>";
    echo "ID: " . $test_page->getVar('page_id') . "<br>";
    echo "URL: " . $test_page->getVar('page_url') . "<br>";
} else {
    echo "Failed to create test symlink.<br>";
}

echo "<h2>Remember to delete this test file when done!</h2>";
```

### Debugging Routing Issues

Add debug output to track routing:

```php
<?php
/**
 * Debug helper for routing
 */
function debugRouting() {
    echo "<div style='background: #f0f0f0; border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
    echo "<h3>Routing Debug Info</h3>";
    
    // Current URL
    echo "<strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";
    
    // Check PageBuilder state
    $modid = icms_view_PageBuilder::getPage();
    echo "<strong>Module ID:</strong> " . $modid['module'] . "<br>";
    echo "<strong>Page ID:</strong> " . $modid['page'] . "<br>";
    echo "<strong>Is Start Page:</strong> " . ($modid['isStart'] ? 'Yes' : 'No') . "<br>";
    
    // Check for symlink match
    $page_handler = icms::handler('icms_data_page');
    $clean_request = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    $url = substr(str_replace(ICMS_URL, '', ICMS_URL . $clean_request), 1);
    
    $criteria = new icms_db_criteria_Compo();
    $criteria->add(new icms_db_criteria_Item('page_url', $url));
    $criteria->add(new icms_db_criteria_Item('page_status', 1));
    
    $pages = $page_handler->getObjects($criteria);
    
    echo "<strong>Symlink Found:</strong> ";
    if (count($pages) > 0) {
        echo "Yes - " . $pages[0]->getVar('page_title');
    } else {
        echo "No";
    }
    echo "<br>";
    
    echo "</div>";
}

// Call at the top of your page
// debugRouting();
```

### Using Browser Developer Tools

1. **Network Tab**: Monitor HTTP requests to see which URLs are being requested
2. **Console**: Add JavaScript to test URL patterns
3. **Storage**: Check cookies and session data that might affect routing

---

## Security Considerations

Security is paramount when working with URLs and routing. Here are critical security practices:

### 1. Input Validation

**Always validate and sanitize URLs**:

```php
<?php
// BAD - No validation
$url = $_GET['url'];
$criteria = new icms_db_criteria_Item('page_url', $url);

// GOOD - Proper validation
$url = filter_var($_GET['url'], FILTER_SANITIZE_URL);
$url = filter_var($url, FILTER_VALIDATE_URL);

if ($url === false) {
    // Invalid URL - reject
    die('Invalid URL');
}

// Further sanitization
$url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
```

**Use ImpressCMS data filters**:

```php
<?php
// Using built-in ImpressCMS filter
$url = icms_core_DataFilter::checkVar($url, 'url', 'host');

if (!$url) {
    // Validation failed
    header("HTTP/1.0 400 Bad Request");
    exit;
}
```

### 2. Output Encoding

**Always encode output**:

```php
<?php
// BAD - Direct output
echo $page->getVar('page_title');
echo $page->getVar('page_url');

// GOOD - Encoded output
echo htmlspecialchars($page->getVar('page_title'), ENT_QUOTES, 'UTF-8');
echo htmlspecialchars($page->getVar('page_url'), ENT_QUOTES, 'UTF-8');

// BETTER - Use ImpressCMS methods
echo $page->getVar('page_title', 'e'); // 'e' for encoded/escaped output
echo $page->getVar('page_url', 's'); // 's' for sanitized output
```

### 3. Status Verification

**Always check status before routing**:

```php
<?php
function getActiveSymlink($url) {
    $page_handler = icms::handler('icms_data_page');
    
    $criteria = new icms_db_criteria_Compo();
    $criteria->add(new icms_db_criteria_Item('page_url', $url));
    
    // CRITICAL: Check status is active
    $criteria->add(new icms_db_criteria_Item('page_status', 1));
    
    $pages = $page_handler->getObjects($criteria);
    
    if (count($pages) > 0) {
        $page = $pages[0];
        
        // Additional verification
        if ($page->getVar('page_status') != 1) {
            return null; // Double-check status
        }
        
        return $page;
    }
    
    return null;
}
```

### 4. Permission Checks

**Verify user permissions**:

```php
<?php
function canAccessSymlink($page) {
    // Check if user has permission to access the module
    $module_id = $page->getVar('page_moduleid');
    
    $module_handler = icms::handler('icms_module');
    $module = $module_handler->get($module_id);
    
    if (!$module) {
        return false;
    }
    
    // Check module access permissions
    $gperm_handler = icms::handler('icms_member_groupperm');
    $groups = (is_object(icms::$user)) 
        ? icms::$user->getGroups() 
        : array(ICMS_GROUP_ANONYMOUS);
    
    // Check if user's groups have module read access
    if (!$gperm_handler->checkRight('module_read', $module_id, $groups)) {
        return false;
    }
    
    return true;
}

// Usage
$page = getActiveSymlink($url);
if ($page && canAccessSymlink($page)) {
    // Proceed with routing
} else {
    // Access denied or not found
    header("HTTP/1.0 403 Forbidden");
    exit;
}
```

### 5. SQL Injection Prevention

**Use parameterized queries**:

```php
<?php
// BAD - Direct SQL (DO NOT USE)
$sql = "SELECT * FROM " . $db->prefix('icmspage') . " WHERE page_url = '" . $url . "'";

// GOOD - Use handlers (they use prepared statements internally)
$page_handler = icms::handler('icms_data_page');
$criteria = new icms_db_criteria_Item('page_url', $url);
$pages = $page_handler->getObjects($criteria);
```

### 6. Path Traversal Prevention

**Prevent directory traversal attacks**:

```php
<?php
function validateUrl($url) {
    // Remove any path traversal attempts
    $url = str_replace('..', '', $url);
    $url = str_replace('//', '/', $url);
    
    // Ensure URL doesn't start with /
    $url = ltrim($url, '/');
    
    // Remove any null bytes
    $url = str_replace("\0", '', $url);
    
    // Validate characters
    if (!preg_match('/^[a-zA-Z0-9\-_\/\*]+$/', $url)) {
        return false;
    }
    
    return $url;
}
```

### 7. CSRF Protection

**Protect admin operations**:

```php
<?php
// When creating/editing symlinks through forms
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify ICMS security token
    if (!icms::$security->check()) {
        redirect_header($_SERVER['HTTP_REFERER'], 3, _NOPERM);
        exit;
    }
    
    // Proceed with operation
}
```

### 8. Rate Limiting

**Prevent abuse of routing system**:

```php
<?php
/**
 * Simple rate limiter for route lookups
 */
class RouteRateLimiter {
    private $max_requests = 100;
    private $time_window = 60; // seconds
    
    public function checkLimit($identifier) {
        // Use session or cache to track requests
        $key = 'route_limit_' . md5($identifier);
        
        if (isset($_SESSION[$key])) {
            $data = $_SESSION[$key];
            
            if (time() - $data['start_time'] < $this->time_window) {
                if ($data['count'] >= $this->max_requests) {
                    return false; // Rate limit exceeded
                }
                $_SESSION[$key]['count']++;
            } else {
                // Reset counter
                $_SESSION[$key] = array(
                    'start_time' => time(),
                    'count' => 1
                );
            }
        } else {
            $_SESSION[$key] = array(
                'start_time' => time(),
                'count' => 1
            );
        }
        
        return true;
    }
}
```

---

## Real-world Use Cases

### Use Case 1: SEO-Friendly URL Mapping

**Scenario**: You want clean URLs for your news module.

**Without Symlinks**:
```
https://yoursite.com/modules/news/article.php?storyid=42
```

**With Symlinks**:
```
https://yoursite.com/latest-announcement
https://yoursite.com/news/2023/major-update
```

**Implementation**:
```php
<?php
// Create symlinks for popular articles
$page_handler = icms::handler('icms_data_page');
$module_handler = icms::handler('icms_module');
$news_module = $module_handler->getByDirname('news');

// Create a symlink for a specific article
$page = $page_handler->create();
$page->setVar('page_title', 'Latest Announcement');
$page->setVar('page_url', 'latest-announcement');
$page->setVar('page_moduleid', $news_module->getVar('mid'));
$page->setVar('page_status', 1);
$page_handler->insert($page);

// Create wildcard for all news articles
$page2 = $page_handler->create();
$page2->setVar('page_title', 'News Articles');
$page2->setVar('page_url', 'news/*');
$page2->setVar('page_moduleid', $news_module->getVar('mid'));
$page2->setVar('page_status', 1);
$page_handler->insert($page2);
```

### Use Case 2: Block Positioning by URL

**Scenario**: Display different blocks on different symlinked pages.

**Implementation**:
```php
<?php
/**
 * Blocks are automatically positioned based on page_id from symlinks
 * The icms_view_PageBuilder uses the page_id to determine block visibility
 */

// Create symlinks for different sections
$sections = array(
    array('title' => 'About Us', 'url' => 'about-us'),
    array('title' => 'Contact', 'url' => 'contact'),
    array('title' => 'Services', 'url' => 'services')
);

$page_handler = icms::handler('icms_data_page');
$module_handler = icms::handler('icms_module');
$system_module = $module_handler->getByDirname('system');

foreach ($sections as $section) {
    $page = $page_handler->create();
    $page->setVar('page_title', $section['title']);
    $page->setVar('page_url', $section['url']);
    $page->setVar('page_moduleid', $system_module->getVar('mid'));
    $page->setVar('page_status', 1);
    $page_handler->insert($page);
    
    echo "Created symlink with page_id: " . $page->getVar('page_id') . "<br>";
    echo "Configure blocks in admin to show only on this page_id<br><br>";
}

// In block admin, you can now select specific pages for block visibility
// This is handled automatically by the block_module_link table
```

### Use Case 3: Module-Specific Page Routing

**Scenario**: Route specific URLs to custom module pages.

**Implementation**:
```php
<?php
/**
 * Custom module with symlink-based routing
 * File: /modules/portfolio/index.php
 */

include '../../mainfile.php';

// Get routing information from PageBuilder
$page_info = icms_view_PageBuilder::getPage();
$page_handler = icms::handler('icms_data_page');

// Check if we're on a symlinked page
if ($page_info['page'] > 0) {
    $page = $page_handler->get($page_info['page']);
    
    if ($page && $page->getVar('page_moduleid') == $icmsModule->getVar('mid')) {
        // We're on a symlinked page for this module
        $page_url = $page->getVar('page_url');
        
        // Route based on URL pattern
        if (preg_match('/portfolio\/project\/(\d+)/', $page_url, $matches)) {
            $project_id = $matches[1];
            include 'project.php'; // Show project details
        } elseif ($page_url == 'portfolio/gallery') {
            include 'gallery.php'; // Show gallery
        } else {
            include 'default.php'; // Default view
        }
        
        exit;
    }
}

// No symlink routing - use default
include 'default.php';
```

### Use Case 4: Centralized URL Management

**Scenario**: Manage all important URLs from one place.

**Implementation**:
```php
<?php
/**
 * URL Manager class
 * Centralized management of all site URLs via symlinks
 */
class SiteUrlManager {
    
    private $page_handler;
    private $cache = array();
    
    public function __construct() {
        $this->page_handler = icms::handler('icms_data_page');
        $this->loadCache();
    }
    
    /**
     * Load all active symlinks into cache
     */
    private function loadCache() {
        $criteria = new icms_db_criteria_Item('page_status', 1);
        $pages = $this->page_handler->getObjects($criteria);
        
        foreach ($pages as $page) {
            $this->cache[$page->getVar('page_url')] = array(
                'title' => $page->getVar('page_title'),
                'module_id' => $page->getVar('page_moduleid'),
                'page_id' => $page->getVar('page_id')
            );
        }
    }
    
    /**
     * Get full URL for a slug
     */
    public function getUrl($slug) {
        if (isset($this->cache[$slug])) {
            return ICMS_URL . '/' . $slug;
        }
        return null;
    }
    
    /**
     * Get all URLs for a module
     */
    public function getModuleUrls($module_id) {
        $urls = array();
        foreach ($this->cache as $slug => $info) {
            if ($info['module_id'] == $module_id) {
                $urls[$slug] = $info;
            }
        }
        return $urls;
    }
    
    /**
     * Generate navigation menu from symlinks
     */
    public function getNavigationMenu($module_id = null) {
        $menu = array();
        
        foreach ($this->cache as $slug => $info) {
            if ($module_id === null || $info['module_id'] == $module_id) {
                $menu[] = array(
                    'title' => $info['title'],
                    'url' => ICMS_URL . '/' . $slug
                );
            }
        }
        
        return $menu;
    }
    
    /**
     * Update URL (for migrations, etc.)
     */
    public function updateUrl($old_slug, $new_slug) {
        $criteria = new icms_db_criteria_Item('page_url', $old_slug);
        $pages = $this->page_handler->getObjects($criteria);
        
        if (count($pages) > 0) {
            $page = $pages[0];
            $page->setVar('page_url', $new_slug);
            return $this->page_handler->insert($page, true);
        }
        
        return false;
    }
}

// Usage
$url_manager = new SiteUrlManager();

// Get URL
echo $url_manager->getUrl('about-us'); // Output: https://yoursite.com/about-us

// Generate menu
$menu = $url_manager->getNavigationMenu();
foreach ($menu as $item) {
    echo '<a href="' . $item['url'] . '">' . $item['title'] . '</a><br>';
}

// Update URL
$url_manager->updateUrl('old-about', 'about-us');
```

### Use Case 5: Multi-language URL Management

**Scenario**: Different URLs for different languages.

**Implementation**:
```php
<?php
/**
 * Language-specific symlinks
 */
class MultiLanguageRouter {
    
    private $page_handler;
    private $current_language;
    
    public function __construct() {
        $this->page_handler = icms::handler('icms_data_page');
        $this->current_language = icms::$module->config['language'];
    }
    
    /**
     * Create language-specific symlinks
     */
    public function createLanguageSymlink($title, $url, $module_id, $language) {
        // Prefix URL with language code
        $lang_url = $language . '/' . $url;
        
        $page = $this->page_handler->create();
        $page->setVar('page_title', $title . ' [' . strtoupper($language) . ']');
        $page->setVar('page_url', $lang_url);
        $page->setVar('page_moduleid', $module_id);
        $page->setVar('page_status', 1);
        
        return $this->page_handler->insert($page);
    }
    
    /**
     * Get URL for current language
     */
    public function getLocalizedUrl($base_url) {
        $lang_url = $this->current_language . '/' . $base_url;
        
        $criteria = new icms_db_criteria_Compo();
        $criteria->add(new icms_db_criteria_Item('page_url', $lang_url));
        $criteria->add(new icms_db_criteria_Item('page_status', 1));
        
        $pages = $this->page_handler->getObjects($criteria);
        
        if (count($pages) > 0) {
            return ICMS_URL . '/' . $lang_url;
        }
        
        // Fallback to default
        return ICMS_URL . '/' . $base_url;
    }
}

// Usage
$ml_router = new MultiLanguageRouter();

// Create symlinks for multiple languages
$module_id = 2; // Example module ID

$ml_router->createLanguageSymlink('About Us', 'about', $module_id, 'en');
$ml_router->createLanguageSymlink('À Propos', 'about', $module_id, 'fr');
$ml_router->createLanguageSymlink('Über Uns', 'about', $module_id, 'de');

// Get localized URL
echo $ml_router->getLocalizedUrl('about');
// Output: https://yoursite.com/en/about (if current language is English)
```

---

## Conclusion

ImpressCMS symlinks provide a powerful, database-driven routing system that enables:

- **SEO-friendly URLs**: Create memorable, search-engine-optimized URLs
- **Flexible routing**: Map any URL pattern to any module/page
- **Block positioning**: Control block visibility based on URLs
- **Centralized management**: Manage all site URLs from one admin interface
- **Extensibility**: Build custom routing layers on top of the core system

### Key Takeaways:

1. **Symlinks are database records**, not filesystem links
2. **Always validate and sanitize** user input and URLs
3. **Check status and permissions** before routing
4. **Use core handlers** for database operations
5. **Test thoroughly** using the admin interface and debug tools
6. **Extend carefully** - maintain backwards compatibility with core routing
7. **Document your routes** for maintainability

### Further Learning:

- Study the `icms_view_PageBuilder` class for advanced routing techniques
- Explore the ImpressCMS block system for content positioning
- Review the IPF (ImpressCMS Persistable Framework) for data handling patterns
- Examine existing modules to see how they leverage symlinks

### Getting Help:

- **Official Documentation**: [https://www.impresscms.org/modules/simplywiki/](https://www.impresscms.org/modules/simplywiki/)
- **Forums**: [https://www.impresscms.org/modules/iforum/](https://www.impresscms.org/modules/iforum/)
- **GitHub Issues**: [https://github.com/ImpressCMS/impresscms/issues](https://github.com/ImpressCMS/impresscms/issues)

---

**Document Version**: 1.0  
**Last Updated**: December 2023  
**Author**: ImpressCMS Community  
**License**: GPL 2.0
