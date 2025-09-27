# ImpressCMS TNG Controller Architecture Documentation

## Overview

The ImpressCMS TNG (The Next Generation) branch introduces a modern, PSR-compliant controller architecture that represents a significant evolution from the traditional procedural approach used in the 2.0.x branch. This system implements a full MVC (Model-View-Controller) pattern with modern PHP practices including dependency injection, middleware support, and annotation-based routing.

## Table of Contents

1. [Controller Class Structure](#controller-class-structure)
2. [Routing Integration](#routing-integration)
3. [HTTP Request Handling](#http-request-handling)
4. [Response System](#response-system)
5. [Middleware Architecture](#middleware-architecture)
6. [Usage Patterns](#usage-patterns)
7. [Practical Examples](#practical-examples)
8. [Comparison with 2.0.x](#comparison-with-20x)
9. [Migration Guide](#migration-guide)

## Controller Class Structure

### Base Controller Architecture

The TNG branch doesn't use a traditional base controller class. Instead, controllers are plain PHP classes that implement specific methods for handling HTTP requests. This approach provides maximum flexibility while maintaining simplicity.

#### Core Controllers

The system includes several core controllers located in `core/Controllers/`:

1. **DefaultController** - Handles basic site functionality (homepage, error pages, images)
2. **AdminController** - Manages administrative interface
3. **LegacyController** - Provides backward compatibility with legacy code
4. **MiscController** - Handles miscellaneous functionality (popups, captcha, etc.)
5. **PasswordController** - Manages password-related operations

#### ModelController

The `ModelController` class (located at `core/ModelController.php`) provides specialized functionality for handling data model operations:

```php
namespace ImpressCMS\Core;

class ModelController
{
    public $handler;

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function postDataToObject(&$icmsObj) { /* ... */ }
    public function storeFromDefaultForm($created_success_msg, $modified_success_msg, $redirect_page = false, $debug = false, $x_param = false) { /* ... */ }
    public function handleObjectDeletion($confirm_msg = false, $op = 'del', $userSide = false) { /* ... */ }
    // ... additional methods
}
```

### Controller Method Signatures

Controllers use PSR-7 compliant method signatures:

```php
public function methodName(ServerRequestInterface $request): ResponseInterface
{
    // Controller logic here
    return $response;
}
```

## Routing Integration

### Annotation-Based Routing

The TNG system uses the Sunrise HTTP Router with annotation-based route definitions:

```php
/**
 * @Route(
 *     name="homepage",
 *     path="/(index.php)",
 *     methods={"GET", "POST"}
 * )
 */
public function getIndex(ServerRequestInterface $request): ResponseInterface
{
    // Method implementation
}
```

### Route Configuration

Routes are automatically discovered through:

1. **Annotation scanning** - Controllers are scanned for `@Route` annotations
2. **Legacy route mapping** - Automatic mapping for backward compatibility
3. **Module controller discovery** - Automatic detection of module-specific controllers

### Router Service Provider

The routing system is configured through `RouterServiceProvider`:

```php
class RouterServiceProvider extends AbstractServiceProvider
{
    protected function getControllerPaths(): array
    {
        $paths = [
            ICMS_ROOT_PATH . '/core/Controllers'
        ];

        foreach (ModuleHandler::getActive() as $moduleDir) {
            $path = ICMS_MODULES_PATH . '/' . $moduleDir . '/Controllers';
            if (is_dir($path)) {
                $paths[] = $path;
            }
        }

        return $paths;
    }
}
```

## HTTP Request Handling

### Request Processing Flow

1. **Request Creation** - PSR-7 ServerRequest created from globals
2. **Middleware Processing** - Request passes through middleware stack
3. **Route Matching** - Router matches request to controller method
4. **Controller Execution** - Controller method processes request
5. **Response Generation** - Controller returns PSR-7 response
6. **Response Processing** - Response passes through middleware stack
7. **Output** - Final response sent to client

### Request Data Access

Controllers access request data through PSR-7 methods:

```php
public function handleRequest(ServerRequestInterface $request): ResponseInterface
{
    $queryParams = $request->getQueryParams();
    $postData = $request->getParsedBody();
    $headers = $request->getHeaders();
    $cookies = $request->getCookieParams();

    // Process request data
    return $response;
}
```

## Response System

### Response Types

The TNG system provides several response types:

#### ViewResponse

Renders templates and handles view logic:

```php
$response = new ViewResponse([
    'template_main' => 'system_error.html',
    'isAdminSide' => false
]);

$response->assign('variable_name', $value);
$response->addStylesheet('/path/to/style.css');

return $response;
```

#### RedirectResponse

Handles HTTP redirections:

```php
return new RedirectResponse(
    '/new-url',
    301,
    'Redirect message',
    true // Allow external links
);
```

#### Standard PSR-7 Response

For API endpoints or custom responses:

```php
return new Response(
    200,
    ['Content-Type' => 'application/json'],
    json_encode($data)
);
```

### Template Integration

ViewResponse integrates with the theme system:

- **Theme Selection** - Automatic theme detection and loading
- **Template Assignment** - Variables assigned to template engine
- **Block Integration** - Automatic block system integration
- **Admin Interface** - Special handling for administrative pages

## Middleware Architecture

### Middleware Stack

The system uses a comprehensive middleware stack:

1. **ServerTimingMiddleware** - Performance monitoring (if logging enabled)
2. **EncryptCookiesMiddleware** - Cookie encryption (if enabled)
3. **AuraSession** - Session management
4. **SetSessionCookieConfigMiddleware** - Session cookie configuration
5. **ChangeThemeMiddleware** - Theme switching support
6. **UserMiddleware** - User authentication and session handling
7. **MultiLoginOnlineInfoUpdaterMiddleware** - Multi-login support
8. **Compression Middleware** - GZIP/Deflate compression
9. **SiteClosedMiddleware** - Site maintenance mode
10. **DetectModuleMiddleware** - Module detection and loading

### Custom Middleware

Modules can add custom middleware through the service container:

```php
// In composer.json or service configuration
"services": {
    "\\MyModule\\CustomMiddleware": {
        "tags": ["middleware.global"]
    }
}
```

## Usage Patterns

### Basic Controller Pattern

```php
namespace MyModule\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ImpressCMS\Core\Response\ViewResponse;
use Sunrise\Http\Router\Annotation\Route;

class MyController
{
    /**
     * @Route(
     *     name="my_action",
     *     path="/my-module/action",
     *     methods={"GET", "POST"}
     * )
     */
    public function handleAction(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        $response = new ViewResponse([
            'template_main' => 'my_template.html'
        ]);

        $response->assign('data', $params);

        return $response;
    }
}
```

### Form Processing Pattern

```php
public function processForm(ServerRequestInterface $request): ResponseInterface
{
    if ($request->getMethod() === 'POST') {
        $data = $request->getParsedBody();

        // Validate and process form data
        if ($this->validateData($data)) {
            // Save data
            return new RedirectResponse('/success', 302, 'Data saved successfully');
        } else {
            // Show form with errors
            $response = new ViewResponse(['template_main' => 'form.html']);
            $response->assign('errors', $this->getErrors());
            $response->assign('data', $data);
            return $response;
        }
    }

    // Show empty form
    return new ViewResponse(['template_main' => 'form.html']);
}
```

### API Endpoint Pattern

```php
/**
 * @Route(
 *     name="api_endpoint",
 *     path="/api/data",
 *     methods={"GET"}
 * )
 */
public function getApiData(ServerRequestInterface $request): ResponseInterface
{
    $data = $this->fetchData();

    return new Response(
        200,
        ['Content-Type' => 'application/json'],
        json_encode($data)
    );
}
```

## Controller Class Hierarchy and Inheritance

### No Traditional Base Class

Unlike many MVC frameworks, the TNG controller system doesn't enforce a common base class. This design decision provides several advantages:

- **Flexibility** - Controllers can implement any interface or extend any class as needed
- **Reduced Coupling** - No forced dependencies on framework-specific base classes
- **Testability** - Easier to unit test without complex inheritance chains
- **Performance** - No overhead from unused base class methods

### Core Controller Classes

#### DefaultController

**Location**: `core/Controllers/DefaultController.php`

**Purpose**: Handles core site functionality including homepage, error pages, and basic utilities.

**Key Methods**:
- `getIndex(ServerRequestInterface $request): ResponseInterface` - Homepage handling
- `getError(ServerRequestInterface $request): ResponseInterface` - Error page rendering
- `getPing(ServerRequestInterface $request): ResponseInterface` - Session ping for AJAX
- `getImage(ServerRequestInterface $request): ?Response` - Database image serving
- `getPrivatePolicy(ServerRequestInterface $request): ViewResponse` - Privacy policy display

**Example Implementation**:
```php
/**
 * @Route(
 *     name="homepage",
 *     path="/(index.php)",
 *     methods={"GET", "POST"}
 * )
 */
public function getIndex(ServerRequestInterface $request): ResponseInterface
{
    global $icmsConfig;

    $member_handler = icms::handler('icms_member');
    $group = $member_handler->getUserBestGroup(
        (!empty(icms::$user) && is_object(icms::$user)) ? icms::$user->uid : 0
    );

    // Handle start page redirection logic
    if (isset($icmsConfig['startpage']) && $icmsConfig['startpage'] != '' && $icmsConfig['startpage'] != '--') {
        return new RedirectResponse(ICMS_MODULES_URL . '/' . $icmsConfig['startpage'] . '/');
    }

    return $this->getDefaultEmptyPage($request);
}
```

#### AdminController

**Location**: `core/Controllers/AdminController.php`

**Purpose**: Manages administrative interface and admin-specific functionality.

**Key Methods**:
- `getDefaultPage(ServerRequestInterface $request)` - Main admin dashboard
- `showRSSFeedPage(ServerRequestInterface $request): ResponseInterface` - RSS feed display

**Security Features**:
- Automatic user authentication checking
- Admin permission validation
- Admin-specific template loading

#### LegacyController

**Location**: `core/Controllers/LegacyController.php`

**Purpose**: Provides backward compatibility with existing procedural PHP files.

**Key Methods**:
- `proxy(ServerRequestInterface $request): ResponseInterface` - Legacy file execution

**Functionality**:
- Executes legacy PHP files within the new framework context
- Maintains global variable compatibility
- Handles output buffering and header management
- Provides module class path registration

#### MiscController

**Location**: `core/Controllers/MiscController.php`

**Purpose**: Handles miscellaneous functionality like popups, captcha, and utility functions.

**Key Methods**:
- `getDefaultPage(ServerRequestInterface $request): ResponseInterface` - Route dispatcher
- `updateCaptcha(ServerRequestInterface $request)` - CAPTCHA image generation
- `showSmilesPopup(ServerRequestInterface $request): ResponseInterface` - Smilies popup
- `showRecommendToFriendPopup(ServerRequestInterface $request): ResponseInterface` - Friend recommendation
- `showAvatarsPopup(ServerRequestInterface $request): ResponseInterface` - Avatar selection
- `showUsersOnlinePopup(ServerRequestInterface $request): ResponseInterface` - Online users display

#### PasswordController

**Location**: `core/Controllers/PasswordController.php`

**Purpose**: Manages all password-related functionality including password recovery.

**Key Methods**:
- `getLostPass(ServerRequestInterface $request): ResponseInterface` - Password recovery handling
- `generatePasswordRetrieval()` - Password recovery email generation
- `generateLostPass()` - New password generation and delivery

### ModelController Class

**Location**: `core/ModelController.php`

**Purpose**: Specialized controller for handling data model operations, particularly for IPF (ImpressCMS Persistable Framework) objects.

**Key Features**:
- Form data processing and validation
- File upload handling
- Object persistence operations
- Permission management integration
- Multi-language support

**Core Methods**:

```php
class ModelController
{
    public $handler; // Handler for the model

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    // Form data processing
    public function postDataToObject(&$icmsObj);

    // Object storage with form data
    public function storeFromDefaultForm($created_success_msg, $modified_success_msg, $redirect_page = false, $debug = false, $x_param = false);

    // Object deletion handling
    public function handleObjectDeletion($confirm_msg = false, $op = 'del', $userSide = false);

    // Link generation methods
    public function getEditItemLink($icmsObj, $onlyUrl = false, $withimage = true, $userSide = false);
    public function getDeleteItemLink($icmsObj, $onlyUrl = false, $withimage = true, $userSide = false);
    public function getViewItemLink($icmsObj, $onlyUrl = false, $withimage = true, $userSide = false);
}
```

### Controller Method Patterns

#### Standard Method Signature

All controller methods follow the PSR-7 pattern:

```php
public function methodName(ServerRequestInterface $request): ResponseInterface
{
    // Method implementation
}
```

#### Route Annotation Pattern

Controllers use annotations for route definition:

```php
/**
 * @Route(
 *     name="route_name",
 *     path="/path/pattern",
 *     methods={"GET", "POST"}
 * )
 */
```

#### Dependency Injection Pattern

Controllers can access services through the container:

```php
public function someAction(ServerRequestInterface $request): ResponseInterface
{
    $translator = icms::getInstance()->get('translator');
    $router = icms::getInstance()->get('router');
    $security = icms::getInstance()->get('security');

    // Use injected services
}
```

### Module Controller Integration

Modules can provide their own controllers by:

1. **Creating Controllers Directory**: `modules/mymodule/Controllers/`
2. **Implementing Controller Classes**: Following the same patterns as core controllers
3. **Using Proper Namespacing**: `namespace MyModule\Controllers;`
4. **Route Registration**: Automatic discovery through annotation scanning

**Example Module Controller**:

```php
<?php
namespace MyModule\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ImpressCMS\Core\Response\ViewResponse;
use Sunrise\Http\Router\Annotation\Route;

class MyModuleController
{
    /**
     * @Route(
     *     name="mymodule_index",
     *     path="/modules/mymodule/",
     *     methods={"GET"}
     * )
     */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $response = new ViewResponse([
            'template_main' => 'mymodule_index.html'
        ]);

        return $response;
    }
}
```

## Routing Integration and HTTP Request Handling

### Router Architecture

The TNG system uses the **Sunrise HTTP Router** with comprehensive annotation support for route definition and automatic controller discovery.

#### Router Service Provider

**Location**: `core/ServiceProviders/RouterServiceProvider.php`

The RouterServiceProvider handles:
- Controller path discovery
- Route annotation scanning
- Legacy route compatibility
- Module controller integration

```php
class RouterServiceProvider extends AbstractServiceProvider
{
    protected function getControllerPaths(): array
    {
        $paths = [
            ICMS_ROOT_PATH . '/core/Controllers'
        ];

        // Add module controller paths
        foreach (ModuleHandler::getActive() as $moduleDir) {
            $path = ICMS_MODULES_PATH . '/' . $moduleDir . '/Controllers';
            if (is_dir($path)) {
                $paths[] = $path;
            }
        }

        return $paths;
    }
}
```

### Route Definition Patterns

#### Basic Route Annotation

```php
/**
 * @Route(
 *     name="route_name",
 *     path="/path/to/resource",
 *     methods={"GET", "POST"}
 * )
 */
public function handleRequest(ServerRequestInterface $request): ResponseInterface
{
    // Controller logic
}
```

#### Route with Parameters

```php
/**
 * @Route(
 *     name="user_profile",
 *     path="/user/{id}",
 *     methods={"GET"},
 *     requirements={"id": "\d+"}
 * )
 */
public function getUserProfile(ServerRequestInterface $request): ResponseInterface
{
    $id = $request->getAttribute('id');
    // Handle user profile display
}
```

#### Optional Parameters

```php
/**
 * @Route(
 *     name="blog_posts",
 *     path="/blog/{page}",
 *     methods={"GET"},
 *     requirements={"page": "\d+"},
 *     defaults={"page": "1"}
 * )
 */
public function getBlogPosts(ServerRequestInterface $request): ResponseInterface
{
    $page = $request->getAttribute('page', 1);
    // Handle paginated blog posts
}
```

### HTTP Request Processing Flow

#### 1. Request Creation

The system creates PSR-7 ServerRequest objects from PHP globals:

```php
// In bootstrap process
$request = ServerRequestFactory::fromGlobals();
```

#### 2. Middleware Stack Processing

Request passes through the middleware stack:
- Session management
- User authentication
- Theme detection
- Module loading
- Security checks

#### 3. Route Matching

The router matches the request URI to controller methods:

```php
$route = $router->match($request);
$controller = $route->getHandler();
$action = $route->getAction();
```

#### 4. Controller Execution

The matched controller method is executed:

```php
$response = $controller->$action($request);
```

#### 5. Response Processing

Response passes back through middleware stack for:
- Output compression
- Header modification
- Cookie encryption
- Performance monitoring

### Request Data Access Patterns

#### Query Parameters

```php
public function handleRequest(ServerRequestInterface $request): ResponseInterface
{
    $queryParams = $request->getQueryParams();
    $page = $queryParams['page'] ?? 1;
    $search = $queryParams['q'] ?? '';

    // Process query parameters
}
```

#### POST Data

```php
public function handleForm(ServerRequestInterface $request): ResponseInterface
{
    if ($request->getMethod() === 'POST') {
        $postData = $request->getParsedBody();
        $username = $postData['username'] ?? '';
        $password = $postData['password'] ?? '';

        // Process form data
    }
}
```

#### Route Parameters

```php
/**
 * @Route(path="/article/{id}/{slug}")
 */
public function getArticle(ServerRequestInterface $request): ResponseInterface
{
    $id = $request->getAttribute('id');
    $slug = $request->getAttribute('slug');

    // Use route parameters
}
```

#### Headers and Cookies

```php
public function handleRequest(ServerRequestInterface $request): ResponseInterface
{
    $headers = $request->getHeaders();
    $userAgent = $request->getHeaderLine('User-Agent');
    $cookies = $request->getCookieParams();
    $sessionId = $cookies['PHPSESSID'] ?? null;

    // Process headers and cookies
}
```

#### File Uploads

```php
public function handleUpload(ServerRequestInterface $request): ResponseInterface
{
    $uploadedFiles = $request->getUploadedFiles();

    if (isset($uploadedFiles['avatar'])) {
        $avatar = $uploadedFiles['avatar'];

        if ($avatar->getError() === UPLOAD_ERR_OK) {
            $filename = $avatar->getClientFilename();
            $avatar->moveTo('/path/to/uploads/' . $filename);
        }
    }
}
```

### Legacy Route Compatibility

The system maintains backward compatibility with existing URLs through:

#### Legacy Controller Proxy

```php
/**
 * @Route(
 *     name="legacy_proxy",
 *     path="/{path}",
 *     methods={"GET", "POST"},
 *     requirements={"path": ".*\.php"}
 * )
 */
public function proxy(ServerRequestInterface $request): ResponseInterface
{
    $path = $request->getAttribute('path');

    // Execute legacy PHP file within framework context
    return $this->executeLegacyFile($path, $request);
}
```

#### Module Route Discovery

Modules are automatically discovered and their routes registered:

```php
// Automatic module route registration
foreach (ModuleHandler::getActive() as $moduleDir) {
    $controllerPath = ICMS_MODULES_PATH . '/' . $moduleDir . '/Controllers';
    if (is_dir($controllerPath)) {
        $this->scanControllersForRoutes($controllerPath);
    }
}
```

## Response System and View Rendering

### Response Architecture

The TNG system provides a comprehensive response system built on PSR-7 standards with specialized response classes for different use cases.

### ViewResponse Class

**Location**: `core/Response/ViewResponse.php`

**Purpose**: Handles template rendering and view-related functionality.

#### Basic Usage

```php
use ImpressCMS\Core\Response\ViewResponse;

public function showPage(ServerRequestInterface $request): ResponseInterface
{
    $response = new ViewResponse([
        'template_main' => 'my_template.html',
        'isAdminSide' => false
    ]);

    return $response;
}
```

#### Template Variable Assignment

```php
$response = new ViewResponse(['template_main' => 'user_profile.html']);

// Assign single variables
$response->assign('username', $user->getVar('uname'));
$response->assign('email', $user->getVar('email'));

// Assign arrays
$response->assign('user_data', [
    'id' => $user->getVar('uid'),
    'name' => $user->getVar('name'),
    'posts' => $user->getVar('posts')
]);

// Assign objects
$response->assign('user_object', $user);
```

#### Asset Management

```php
// Add stylesheets
$response->addStylesheet('/themes/default/css/custom.css');
$response->addStylesheet('https://cdn.example.com/font.css');

// Add JavaScript files
$response->addScript('/themes/default/js/custom.js');
$response->addScript('https://cdn.example.com/library.js');

// Add inline JavaScript
$response->addScript('
    $(document).ready(function() {
        console.log("Page loaded");
    });
', false); // false = inline script
```

#### Meta Tags and SEO

```php
// Set page title
$response->setPageTitle('User Profile - ' . $user->getVar('uname'));

// Add meta tags
$response->addMeta('description', 'User profile page for ' . $user->getVar('uname'));
$response->addMeta('keywords', 'user, profile, community');
$response->addMeta('robots', 'index, follow');

// Add custom meta tags
$response->addMeta('og:title', 'User Profile', 'property');
$response->addMeta('og:type', 'profile', 'property');
```

#### Admin Interface Integration

```php
// For admin pages
$response = new ViewResponse([
    'template_main' => 'admin_dashboard.html',
    'isAdminSide' => true
]);

// Admin-specific features
$response->addAdminBreadcrumb('Dashboard', '/admin/');
$response->addAdminBreadcrumb('Users', '/admin/users/');
$response->setAdminPageTitle('User Management');
```

### RedirectResponse Class

**Location**: `core/Response/RedirectResponse.php`

**Purpose**: Handles HTTP redirections with message support.

#### Basic Redirection

```php
use ImpressCMS\Core\Response\RedirectResponse;

// Simple redirect
return new RedirectResponse('/new-location');

// Redirect with status code
return new RedirectResponse('/new-location', 301);

// Redirect with message
return new RedirectResponse(
    '/success-page',
    302,
    'Operation completed successfully'
);
```

#### External Redirects

```php
// Allow external redirects (disabled by default for security)
return new RedirectResponse(
    'https://external-site.com',
    302,
    'Redirecting to external site',
    true // Allow external
);
```

#### Redirect with Flash Messages

```php
// Redirect with success message
return new RedirectResponse(
    '/dashboard',
    302,
    'Profile updated successfully'
);

// Redirect with error message
return new RedirectResponse(
    '/form',
    302,
    'Please correct the errors and try again'
);
```

### Standard PSR-7 Responses

For API endpoints and custom responses:

#### JSON API Response

```php
use Laminas\Diactoros\Response;

public function getApiData(ServerRequestInterface $request): ResponseInterface
{
    $data = [
        'status' => 'success',
        'data' => $this->fetchData(),
        'timestamp' => time()
    ];

    return new Response(
        200,
        [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache'
        ],
        json_encode($data)
    );
}
```

#### File Download Response

```php
public function downloadFile(ServerRequestInterface $request): ResponseInterface
{
    $filePath = '/path/to/file.pdf';
    $fileContent = file_get_contents($filePath);

    return new Response(
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="document.pdf"',
            'Content-Length' => strlen($fileContent)
        ],
        $fileContent
    );
}
```

#### XML Response

```php
public function getXmlFeed(ServerRequestInterface $request): ResponseInterface
{
    $xml = $this->generateXmlFeed();

    return new Response(
        200,
        ['Content-Type' => 'application/xml'],
        $xml
    );
}
```

### Template Integration

#### Theme System Integration

ViewResponse automatically integrates with the ImpressCMS theme system:

```php
// Theme detection and loading
$response = new ViewResponse(['template_main' => 'content.html']);

// Automatic theme path resolution
// Looks for: /themes/current_theme/templates/content.html
// Falls back to: /themes/default/templates/content.html
```

#### Block System Integration

```php
// Blocks are automatically loaded and assigned
$response = new ViewResponse(['template_main' => 'homepage.html']);

// Access blocks in templates:
// {$xoBlocks.left} - Left sidebar blocks
// {$xoBlocks.right} - Right sidebar blocks
// {$xoBlocks.center} - Center blocks
```

#### Template Variable Scope

```php
// Global variables (available in all templates)
$response->assign('site_name', $icmsConfig['sitename']);
$response->assign('site_url', ICMS_URL);

// Template-specific variables
$response->assign('page_content', $content);
$response->assign('user_data', $userData);

// System variables (automatically assigned)
// {$icms_url} - Site URL
// {$icms_theme_url} - Current theme URL
// {$icms_user} - Current user object
// {$icms_config} - Site configuration
```

### Error Handling in Responses

#### Error Page Response

```php
public function showError(ServerRequestInterface $request): ResponseInterface
{
    $errorCode = $request->getAttribute('error_code', 404);
    $errorMessage = $request->getAttribute('error_message', 'Page not found');

    $response = new ViewResponse([
        'template_main' => 'system_error.html'
    ]);

    $response->assign('error_code', $errorCode);
    $response->assign('error_message', $errorMessage);
    $response->setStatusCode($errorCode);

    return $response;
}
```

#### Exception Handling

```php
public function handleRequest(ServerRequestInterface $request): ResponseInterface
{
    try {
        // Process request
        $data = $this->processData($request);

        $response = new ViewResponse(['template_main' => 'success.html']);
        $response->assign('data', $data);

        return $response;

    } catch (ValidationException $e) {
        return new RedirectResponse(
            '/form',
            302,
            'Validation failed: ' . $e->getMessage()
        );

    } catch (Exception $e) {
        // Log error
        error_log('Controller error: ' . $e->getMessage());

        // Show error page
        $response = new ViewResponse(['template_main' => 'error.html']);
        $response->assign('error', 'An unexpected error occurred');
        $response->setStatusCode(500);

        return $response;
    }
}
```

### Response Caching

#### Cache Headers

```php
public function getCachedContent(ServerRequestInterface $request): ResponseInterface
{
    $response = new ViewResponse(['template_main' => 'cached_content.html']);

    // Set cache headers
    $response = $response->withHeader('Cache-Control', 'public, max-age=3600');
    $response = $response->withHeader('Expires', gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
    $response = $response->withHeader('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');

    return $response;
}
```

#### Conditional Responses

```php
public function getConditionalContent(ServerRequestInterface $request): ResponseInterface
{
    $lastModified = $this->getContentLastModified();
    $etag = md5($lastModified . $this->getContentHash());

    // Check if content has changed
    $ifModifiedSince = $request->getHeaderLine('If-Modified-Since');
    $ifNoneMatch = $request->getHeaderLine('If-None-Match');

    if ($ifNoneMatch === $etag || strtotime($ifModifiedSince) >= $lastModified) {
        return new Response(304); // Not Modified
    }

    // Return full content with cache headers
    $response = new ViewResponse(['template_main' => 'content.html']);
    $response = $response->withHeader('ETag', $etag);
    $response = $response->withHeader('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');

    return $response;
}
```

## Practical Examples and Usage Patterns

### Complete CRUD Controller Example

Here's a comprehensive example of a controller handling Create, Read, Update, Delete operations:

```php
<?php
namespace MyModule\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ImpressCMS\Core\Response\ViewResponse;
use ImpressCMS\Core\Response\RedirectResponse;
use Sunrise\Http\Router\Annotation\Route;
use Laminas\Diactoros\Response;

class ArticleController
{
    private $articleHandler;

    public function __construct()
    {
        $this->articleHandler = icms::handler('mymodule_article');
    }

    /**
     * @Route(
     *     name="article_list",
     *     path="/articles",
     *     methods={"GET"}
     * )
     */
    public function listArticles(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $page = (int)($queryParams['page'] ?? 1);
        $limit = 10;
        $start = ($page - 1) * $limit;

        // Fetch articles with pagination
        $criteria = new \icms_db_criteria_Compo();
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('created_date');
        $criteria->setOrder('DESC');

        $articles = $this->articleHandler->getObjects($criteria);
        $totalCount = $this->articleHandler->getCount();

        $response = new ViewResponse([
            'template_main' => 'article_list.html'
        ]);

        $response->assign('articles', $articles);
        $response->assign('current_page', $page);
        $response->assign('total_pages', ceil($totalCount / $limit));
        $response->assign('total_count', $totalCount);

        return $response;
    }

    /**
     * @Route(
     *     name="article_view",
     *     path="/article/{id}",
     *     methods={"GET"},
     *     requirements={"id": "\d+"}
     * )
     */
    public function viewArticle(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');
        $article = $this->articleHandler->get($id);

        if (!$article || !$article->isNew()) {
            return new RedirectResponse('/articles', 302, 'Article not found');
        }

        // Check permissions
        if (!$article->checkAccess()) {
            return new RedirectResponse('/articles', 302, 'Access denied');
        }

        // Increment view count
        $article->setVar('views', $article->getVar('views') + 1);
        $this->articleHandler->insert($article, true);

        $response = new ViewResponse([
            'template_main' => 'article_view.html'
        ]);

        $response->assign('article', $article);
        $response->setPageTitle($article->getVar('title'));
        $response->addMeta('description', substr(strip_tags($article->getVar('content')), 0, 160));

        return $response;
    }

    /**
     * @Route(
     *     name="article_create",
     *     path="/article/create",
     *     methods={"GET", "POST"}
     * )
     */
    public function createArticle(ServerRequestInterface $request): ResponseInterface
    {
        // Check user permissions
        if (!icms::$user || !icms::$user->isAdmin()) {
            return new RedirectResponse('/login', 302, 'Login required');
        }

        if ($request->getMethod() === 'POST') {
            return $this->processArticleForm($request);
        }

        // Show create form
        $article = $this->articleHandler->create();

        $response = new ViewResponse([
            'template_main' => 'article_form.html'
        ]);

        $response->assign('article', $article);
        $response->assign('form_action', '/article/create');
        $response->assign('form_title', 'Create Article');

        return $response;
    }

    /**
     * @Route(
     *     name="article_edit",
     *     path="/article/{id}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"id": "\d+"}
     * )
     */
    public function editArticle(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');
        $article = $this->articleHandler->get($id);

        if (!$article || $article->isNew()) {
            return new RedirectResponse('/articles', 302, 'Article not found');
        }

        // Check permissions
        if (!$article->checkEditAccess()) {
            return new RedirectResponse('/articles', 302, 'Edit access denied');
        }

        if ($request->getMethod() === 'POST') {
            return $this->processArticleForm($request, $article);
        }

        // Show edit form
        $response = new ViewResponse([
            'template_main' => 'article_form.html'
        ]);

        $response->assign('article', $article);
        $response->assign('form_action', '/article/' . $id . '/edit');
        $response->assign('form_title', 'Edit Article');

        return $response;
    }

    private function processArticleForm(ServerRequestInterface $request, $article = null): ResponseInterface
    {
        $postData = $request->getParsedBody();
        $isNew = ($article === null);

        if ($isNew) {
            $article = $this->articleHandler->create();
        }

        // Validate and set data
        $errors = [];

        if (empty($postData['title'])) {
            $errors[] = 'Title is required';
        } else {
            $article->setVar('title', $postData['title']);
        }

        if (empty($postData['content'])) {
            $errors[] = 'Content is required';
        } else {
            $article->setVar('content', $postData['content']);
        }

        if (!empty($postData['category_id'])) {
            $article->setVar('category_id', (int)$postData['category_id']);
        }

        if (!empty($errors)) {
            $response = new ViewResponse([
                'template_main' => 'article_form.html'
            ]);

            $response->assign('article', $article);
            $response->assign('errors', $errors);
            $response->assign('form_data', $postData);

            return $response;
        }

        // Set additional fields for new articles
        if ($isNew) {
            $article->setVar('author_id', icms::$user->getVar('uid'));
            $article->setVar('created_date', time());
        }

        $article->setVar('modified_date', time());

        // Save article
        if ($this->articleHandler->insert($article)) {
            $message = $isNew ? 'Article created successfully' : 'Article updated successfully';
            return new RedirectResponse('/article/' . $article->getVar('id'), 302, $message);
        } else {
            return new RedirectResponse('/articles', 302, 'Error saving article');
        }
    }
}
```

### API Controller Example

```php
<?php
namespace MyModule\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;
use Sunrise\Http\Router\Annotation\Route;

class ApiController
{
    /**
     * @Route(
     *     name="api_articles",
     *     path="/api/articles",
     *     methods={"GET"}
     * )
     */
    public function getArticles(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $page = (int)($queryParams['page'] ?? 1);
        $limit = min((int)($queryParams['limit'] ?? 10), 50); // Max 50 items

        $articleHandler = icms::handler('mymodule_article');

        $criteria = new \icms_db_criteria_Compo();
        $criteria->setStart(($page - 1) * $limit);
        $criteria->setLimit($limit);

        $articles = $articleHandler->getObjects($criteria);
        $totalCount = $articleHandler->getCount();

        $data = [
            'status' => 'success',
            'data' => array_map(function($article) {
                return [
                    'id' => $article->getVar('id'),
                    'title' => $article->getVar('title'),
                    'content' => $article->getVar('content'),
                    'author_id' => $article->getVar('author_id'),
                    'created_date' => $article->getVar('created_date'),
                    'views' => $article->getVar('views')
                ];
            }, $articles),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $totalCount,
                'total_pages' => ceil($totalCount / $limit)
            ]
        ];

        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                'Cache-Control' => 'public, max-age=300'
            ],
            json_encode($data)
        );
    }

    /**
     * @Route(
     *     name="api_article_create",
     *     path="/api/articles",
     *     methods={"POST"}
     * )
     */
    public function createArticle(ServerRequestInterface $request): ResponseInterface
    {
        // Check authentication
        if (!icms::$user) {
            return $this->jsonError('Authentication required', 401);
        }

        $postData = json_decode($request->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->jsonError('Invalid JSON data', 400);
        }

        // Validate required fields
        $errors = [];
        if (empty($postData['title'])) {
            $errors[] = 'Title is required';
        }
        if (empty($postData['content'])) {
            $errors[] = 'Content is required';
        }

        if (!empty($errors)) {
            return $this->jsonError('Validation failed', 400, ['errors' => $errors]);
        }

        // Create article
        $articleHandler = icms::handler('mymodule_article');
        $article = $articleHandler->create();

        $article->setVar('title', $postData['title']);
        $article->setVar('content', $postData['content']);
        $article->setVar('author_id', icms::$user->getVar('uid'));
        $article->setVar('created_date', time());

        if ($articleHandler->insert($article)) {
            return new Response(
                201,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'status' => 'success',
                    'message' => 'Article created successfully',
                    'data' => [
                        'id' => $article->getVar('id'),
                        'title' => $article->getVar('title')
                    ]
                ])
            );
        } else {
            return $this->jsonError('Failed to create article', 500);
        }
    }

    private function jsonError(string $message, int $code, array $extra = []): ResponseInterface
    {
        $data = array_merge([
            'status' => 'error',
            'message' => $message
        ], $extra);

        return new Response(
            $code,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }
}
```

### File Upload Controller Example

```php
<?php
namespace MyModule\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ImpressCMS\Core\Response\ViewResponse;
use ImpressCMS\Core\Response\RedirectResponse;
use Sunrise\Http\Router\Annotation\Route;

class FileController
{
    /**
     * @Route(
     *     name="file_upload",
     *     path="/upload",
     *     methods={"GET", "POST"}
     * )
     */
    public function handleUpload(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            return $this->processUpload($request);
        }

        // Show upload form
        $response = new ViewResponse([
            'template_main' => 'file_upload.html'
        ]);

        return $response;
    }

    private function processUpload(ServerRequestInterface $request): ResponseInterface
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (!isset($uploadedFiles['file'])) {
            return new RedirectResponse('/upload', 302, 'No file uploaded');
        }

        $uploadedFile = $uploadedFiles['file'];

        // Check for upload errors
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File too large (server limit)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (form limit)',
                UPLOAD_ERR_PARTIAL => 'File partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'No temporary directory',
                UPLOAD_ERR_CANT_WRITE => 'Cannot write file',
                UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
            ];

            $message = $errorMessages[$uploadedFile->getError()] ?? 'Unknown upload error';
            return new RedirectResponse('/upload', 302, $message);
        }

        // Validate file
        $filename = $uploadedFile->getClientFilename();
        $fileSize = $uploadedFile->getSize();
        $mimeType = $uploadedFile->getClientMediaType();

        // Check file size (5MB limit)
        if ($fileSize > 5 * 1024 * 1024) {
            return new RedirectResponse('/upload', 302, 'File too large (max 5MB)');
        }

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!in_array($mimeType, $allowedTypes)) {
            return new RedirectResponse('/upload', 302, 'File type not allowed');
        }

        // Generate unique filename
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = uniqid() . '.' . $extension;
        $uploadPath = ICMS_ROOT_PATH . '/uploads/mymodule/' . $newFilename;

        // Ensure upload directory exists
        $uploadDir = dirname($uploadPath);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move uploaded file
        try {
            $uploadedFile->moveTo($uploadPath);

            // Save file info to database
            $fileHandler = icms::handler('mymodule_file');
            $fileObj = $fileHandler->create();

            $fileObj->setVar('original_name', $filename);
            $fileObj->setVar('filename', $newFilename);
            $fileObj->setVar('mime_type', $mimeType);
            $fileObj->setVar('file_size', $fileSize);
            $fileObj->setVar('upload_date', time());
            $fileObj->setVar('uploader_id', icms::$user ? icms::$user->getVar('uid') : 0);

            if ($fileHandler->insert($fileObj)) {
                return new RedirectResponse('/files', 302, 'File uploaded successfully');
            } else {
                // Clean up file if database insert failed
                unlink($uploadPath);
                return new RedirectResponse('/upload', 302, 'Failed to save file information');
            }

        } catch (Exception $e) {
            return new RedirectResponse('/upload', 302, 'Failed to save file: ' . $e->getMessage());
        }
    }
}
```

## Comparison with 2.0.x Branch

### Architectural Differences

#### 2.0.x Approach (Current)

The 2.0.x branch uses a traditional procedural approach with some object-oriented elements:

**File Structure**:
- Direct PHP file execution (e.g., `/modules/system/admin.php`)
- Global variable dependencies
- Mixed procedural and OOP code
- Direct template assignments

**Example 2.0.x Pattern**:
```php
// In /modules/system/admin.php
include_once '../../mainfile.php';
include_once ICMS_ROOT_PATH . '/include/cp_header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'default';

switch ($op) {
    case 'list':
        // Handle list operation
        $items = $handler->getObjects();
        $icmsTpl->assign('items', $items);
        break;

    case 'edit':
        // Handle edit operation
        $id = (int)$_REQUEST['id'];
        $item = $handler->get($id);
        $icmsTpl->assign('item', $item);
        break;
}

include_once ICMS_ROOT_PATH . '/include/cp_footer.php';
```

#### TNG Approach (Next Generation)

The TNG branch implements a modern MVC architecture:

**File Structure**:
- Controller classes with methods
- PSR-7 request/response handling
- Dependency injection
- Annotation-based routing

**Example TNG Pattern**:
```php
// In /core/Controllers/SystemController.php
namespace ImpressCMS\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ImpressCMS\Core\Response\ViewResponse;
use Sunrise\Http\Router\Annotation\Route;

class SystemController
{
    /**
     * @Route(
     *     name="system_list",
     *     path="/admin/system/list",
     *     methods={"GET"}
     * )
     */
    public function listItems(ServerRequestInterface $request): ResponseInterface
    {
        $handler = icms::handler('system_item');
        $items = $handler->getObjects();

        $response = new ViewResponse([
            'template_main' => 'system_list.html'
        ]);

        $response->assign('items', $items);
        return $response;
    }

    /**
     * @Route(
     *     name="system_edit",
     *     path="/admin/system/edit/{id}",
     *     methods={"GET", "POST"},
     *     requirements={"id": "\d+"}
     * )
     */
    public function editItem(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');
        $handler = icms::handler('system_item');
        $item = $handler->get($id);

        $response = new ViewResponse([
            'template_main' => 'system_edit.html'
        ]);

        $response->assign('item', $item);
        return $response;
    }
}
```

### Key Differences Summary

| Aspect | 2.0.x Branch | TNG Branch |
|--------|--------------|------------|
| **Architecture** | Procedural with some OOP | Full MVC with PSR standards |
| **Routing** | File-based URLs | Annotation-based routing |
| **Request Handling** | Global variables ($_GET, $_POST) | PSR-7 ServerRequest objects |
| **Response Handling** | Direct output/template assignment | PSR-7 Response objects |
| **URL Structure** | `/modules/system/admin.php?op=edit&id=1` | `/admin/system/edit/1` |
| **Code Organization** | Mixed logic in single files | Separated controller methods |
| **Testing** | Difficult due to globals | Easy with dependency injection |
| **Middleware** | Limited support | Full middleware stack |
| **Error Handling** | Basic error pages | Comprehensive exception handling |
| **Caching** | Manual implementation | Built-in response caching |
| **Security** | Manual CSRF/validation | Middleware-based security |

### Migration Considerations

#### Advantages of TNG Approach

1. **Better Code Organization**
   - Clear separation of concerns
   - Reusable controller methods
   - Easier maintenance and debugging

2. **Modern PHP Standards**
   - PSR-7 HTTP message interfaces
   - PSR-11 container interfaces
   - PSR-15 middleware interfaces

3. **Improved Testing**
   - Unit testable controllers
   - Mock-friendly dependencies
   - Isolated component testing

4. **Enhanced Security**
   - Middleware-based security layers
   - Built-in CSRF protection
   - Input validation frameworks

5. **Better Performance**
   - Optimized routing
   - Response caching
   - Middleware optimization

#### Backward Compatibility

The TNG branch maintains backward compatibility through:

1. **Legacy Controller**
   - Executes existing PHP files
   - Maintains global variable compatibility
   - Preserves existing functionality

2. **Gradual Migration Path**
   - Modules can be migrated incrementally
   - Existing URLs continue to work
   - No breaking changes for users

3. **Template Compatibility**
   - Existing templates work unchanged
   - Same template variable structure
   - Preserved theme system

### Performance Comparison

#### 2.0.x Performance Characteristics

- **File Inclusion Overhead**: Multiple include/require statements per request
- **Global State**: Heavy reliance on global variables
- **Template Processing**: Direct template assignments
- **Memory Usage**: Higher due to global state retention

#### TNG Performance Improvements

- **Optimized Routing**: Fast route matching with caching
- **Middleware Efficiency**: Streamlined request processing
- **Response Caching**: Built-in HTTP caching support
- **Memory Management**: Better memory usage through object lifecycle management

**Benchmark Example** (Hypothetical):
```
2.0.x Average Response Time: 150ms
TNG Average Response Time: 95ms
Performance Improvement: ~37%
```

## Migration Guide

### Overview

This guide provides step-by-step instructions for integrating the TNG controller system into the existing 2.0.x branch, allowing for gradual migration while maintaining backward compatibility.

### Prerequisites

Before starting the migration, ensure you have:

1. **PHP 7.4+** - TNG requires modern PHP features
2. **Composer** - For dependency management
3. **Understanding of PSR Standards** - Familiarity with PSR-7, PSR-11, PSR-15
4. **Backup** - Complete backup of your current installation

### Step 1: Install TNG Dependencies

Add the required dependencies to your `composer.json`:

```json
{
    "require": {
        "sunrise/http-router": "^2.0",
        "sunrise/http-message": "^1.0",
        "laminas/laminas-diactoros": "^2.0",
        "psr/http-message": "^1.0",
        "psr/http-server-handler": "^1.0",
        "psr/http-server-middleware": "^1.0",
        "psr/container": "^1.0"
    }
}
```

Install dependencies:
```bash
composer install
```

### Step 2: Copy Core TNG Files

Copy the following files from the TNG branch to your 2.0.x installation:

#### Core Controller Files
```
core/Controllers/
├── DefaultController.php
├── AdminController.php
├── LegacyController.php
├── MiscController.php
└── PasswordController.php
```

#### Response Classes
```
core/Response/
├── ViewResponse.php
└── RedirectResponse.php
```

#### Service Providers
```
core/ServiceProviders/
├── RouterServiceProvider.php
└── MiddlewareServiceProvider.php
```

#### Middleware Classes
```
core/Middleware/
├── UserMiddleware.php
├── SiteClosedMiddleware.php
├── DetectModuleMiddleware.php
└── [other middleware files]
```

### Step 3: Update Bootstrap Process

Modify your main bootstrap file (typically `mainfile.php` or a new bootstrap file):

```php
<?php
// Add after existing includes

// Initialize TNG components if available
if (class_exists('ImpressCMS\\Core\\ServiceProviders\\RouterServiceProvider')) {
    // Initialize container and services
    $container = new \ImpressCMS\Core\Container();

    // Register service providers
    $container->addServiceProvider(new \ImpressCMS\Core\ServiceProviders\RouterServiceProvider());
    $container->addServiceProvider(new \ImpressCMS\Core\ServiceProviders\MiddlewareServiceProvider());

    // Store container globally for access
    icms::getInstance()->setContainer($container);

    // Initialize router if this is a web request
    if (!defined('ICMS_CLI_MODE')) {
        $router = $container->get('router');
        $request = \Laminas\Diactoros\ServerRequestFactory::fromGlobals();

        try {
            // Try to match route
            $route = $router->match($request);

            if ($route) {
                // Handle through TNG system
                $response = handleTngRequest($request, $route);

                if ($response) {
                    // Send response and exit
                    sendTngResponse($response);
                    exit;
                }
            }
        } catch (Exception $e) {
            // Fall back to legacy system
            error_log('TNG routing error: ' . $e->getMessage());
        }
    }
}

function handleTngRequest($request, $route) {
    try {
        $handler = $route->getHandler();
        $action = $route->getAction();

        // Get controller instance
        if (is_string($handler)) {
            $controller = new $handler();
        } else {
            $controller = $handler;
        }

        // Execute controller method
        return $controller->$action($request);

    } catch (Exception $e) {
        error_log('TNG controller error: ' . $e->getMessage());
        return null;
    }
}

function sendTngResponse($response) {
    // Send status code
    http_response_code($response->getStatusCode());

    // Send headers
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header($name . ': ' . $value, false);
        }
    }

    // Send body
    echo $response->getBody();
}
```

### Step 4: Create Module Controllers

For each module you want to migrate, create a Controllers directory and implement controller classes:

#### Example Module Migration

**Before (2.0.x style)**:
```
modules/mymodule/
├── admin/
│   ├── index.php
│   ├── items.php
│   └── categories.php
├── index.php
└── item.php
```

**After (TNG style)**:
```
modules/mymodule/
├── Controllers/
│   ├── AdminController.php
│   ├── ItemController.php
│   └── CategoryController.php
├── admin/          # Keep for backward compatibility
│   ├── index.php
│   ├── items.php
│   └── categories.php
├── index.php       # Keep for backward compatibility
└── item.php        # Keep for backward compatibility
```

#### Sample Module Controller

```php
<?php
// modules/mymodule/Controllers/ItemController.php
namespace MyModule\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ImpressCMS\Core\Response\ViewResponse;
use ImpressCMS\Core\Response\RedirectResponse;
use Sunrise\Http\Router\Annotation\Route;

class ItemController
{
    /**
     * @Route(
     *     name="mymodule_items",
     *     path="/modules/mymodule/",
     *     methods={"GET"}
     * )
     */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        // Migrate logic from old index.php
        $handler = icms::handler('mymodule_item');
        $items = $handler->getObjects();

        $response = new ViewResponse([
            'template_main' => 'mymodule_index.html'
        ]);

        $response->assign('items', $items);
        return $response;
    }

    /**
     * @Route(
     *     name="mymodule_item_view",
     *     path="/modules/mymodule/item/{id}",
     *     methods={"GET"},
     *     requirements={"id": "\d+"}
     * )
     */
    public function view(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');

        // Migrate logic from old item.php
        $handler = icms::handler('mymodule_item');
        $item = $handler->get($id);

        if (!$item || $item->isNew()) {
            return new RedirectResponse('/modules/mymodule/', 302, 'Item not found');
        }

        $response = new ViewResponse([
            'template_main' => 'mymodule_item.html'
        ]);

        $response->assign('item', $item);
        return $response;
    }
}
```

### Step 5: Gradual Migration Strategy

#### Phase 1: Core Controllers
1. Implement core controllers (Default, Admin, etc.)
2. Test basic functionality
3. Ensure legacy fallback works

#### Phase 2: High-Traffic Modules
1. Identify most-used modules
2. Create TNG controllers for these modules
3. Test thoroughly with both URL styles

#### Phase 3: Remaining Modules
1. Migrate remaining modules
2. Update internal links to use new URL structure
3. Add redirects for old URLs if needed

#### Phase 4: Cleanup
1. Remove legacy files (optional)
2. Update documentation
3. Optimize performance

### Step 6: Testing Migration

#### Unit Testing Controllers

```php
<?php
// tests/Controllers/ItemControllerTest.php
use PHPUnit\Framework\TestCase;
use MyModule\Controllers\ItemController;
use Laminas\Diactoros\ServerRequest;

class ItemControllerTest extends TestCase
{
    public function testIndexAction()
    {
        $controller = new ItemController();
        $request = new ServerRequest([], [], '/modules/mymodule/', 'GET');

        $response = $controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('ImpressCMS\\Core\\Response\\ViewResponse', $response);
    }

    public function testViewAction()
    {
        $controller = new ItemController();
        $request = new ServerRequest([], [], '/modules/mymodule/item/1', 'GET');
        $request = $request->withAttribute('id', '1');

        $response = $controller->view($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
```

#### Integration Testing

```bash
# Test legacy URLs still work
curl -I http://yoursite.com/modules/mymodule/index.php

# Test new TNG URLs work
curl -I http://yoursite.com/modules/mymodule/

# Test both return same content
curl http://yoursite.com/modules/mymodule/index.php > legacy.html
curl http://yoursite.com/modules/mymodule/ > tng.html
diff legacy.html tng.html
```

### Step 7: Performance Optimization

#### Enable Route Caching

```php
// In your bootstrap
$router = $container->get('router');
$router->enableCache('/path/to/cache/routes.php');
```

#### Optimize Middleware Stack

```php
// Only load necessary middleware
$middlewareStack = [
    'essential' => [
        \ImpressCMS\Core\Middleware\UserMiddleware::class,
        \ImpressCMS\Core\Middleware\SiteClosedMiddleware::class
    ],
    'optional' => [
        \ImpressCMS\Core\Middleware\CompressionMiddleware::class,
        \ImpressCMS\Core\Middleware\ServerTimingMiddleware::class
    ]
];
```

### Troubleshooting Common Issues

#### Issue: Routes Not Matching

**Solution**: Check route annotations and ensure proper namespace imports:

```php
// Make sure you have proper use statements
use Sunrise\Http\Router\Annotation\Route;

// Check route syntax
/**
 * @Route(
 *     name="unique_route_name",
 *     path="/exact/path/pattern",
 *     methods={"GET", "POST"}
 * )
 */
```

#### Issue: Legacy Files Not Working

**Solution**: Ensure LegacyController is properly configured:

```php
// Check that legacy routes are registered
/**
 * @Route(
 *     name="legacy_proxy",
 *     path="/{path}",
 *     methods={"GET", "POST"},
 *     requirements={"path": ".*\.php"}
 * )
 */
```

#### Issue: Template Variables Not Available

**Solution**: Ensure proper ViewResponse usage:

```php
// Instead of global $icmsTpl->assign()
$response = new ViewResponse(['template_main' => 'template.html']);
$response->assign('variable', $value);
```

### Best Practices

1. **Maintain Backward Compatibility**: Keep legacy files during transition
2. **Test Thoroughly**: Test both old and new URL structures
3. **Monitor Performance**: Compare performance before and after migration
4. **Document Changes**: Update documentation for new URL patterns
5. **Train Team**: Ensure team understands new controller patterns
6. **Gradual Rollout**: Migrate modules incrementally, not all at once
