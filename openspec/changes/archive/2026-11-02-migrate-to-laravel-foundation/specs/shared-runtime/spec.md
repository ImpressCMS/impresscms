## Purpose

Makes the ImpressCMS runtime (database connection, configuration, core services, IPF and module handlers, and events) available to requests handled by the new application, while the legacy `icms::` API stays fully backward compatible for modules, themes and plugins.

## ADDED Requirements

### Requirement: The legacy runtime can be booted without executing a page
The system SHALL be able to initialise the ImpressCMS runtime for a request handled by the new application without rendering a legacy page, sending output or terminating the process. The runtime consists of constants, paths, `icms::` core services, configuration and installed modules. A new-application request SHALL only boot it when that request needs it.

#### Scenario: New route uses legacy configuration
- **WHEN** a request is handled by a new-application route that reads the site name from ImpressCMS configuration
- **THEN** the response contains the configured site name and no legacy theme output is emitted

#### Scenario: Route without legacy needs
- **WHEN** `GET /up` is handled
- **THEN** the ImpressCMS runtime is not booted and no database connection is opened

### Requirement: Headless boot does not terminate the request
If the legacy runtime would normally stop the request during a headless boot, the system SHALL raise an error that the new application can handle, and SHALL NOT call `exit()` or send output. Reasons to stop include the site being closed, a missing install, or protection rules.

#### Scenario: Site closed
- **WHEN** the site is configured as closed and an anonymous user requests a new-application route that boots the legacy runtime
- **THEN** the new application receives a site-closed condition and produces a 503 response, and the process is not terminated by the legacy code

### Requirement: Single database connection per request
Within one request, the system SHALL use one physical database connection for both the new application and the legacy database layers (`icms::$db`, `icms::$xoopsDB`, IPF handlers). Data written through one layer within a transaction SHALL be visible to the other layer within that same transaction.

#### Scenario: Transaction across layers
- **WHEN** the new application begins a transaction, inserts a row through an IPF handler and reads it back through the new application's database API before committing
- **THEN** the row is visible, and after rollback it is absent from both layers

### Requirement: Core services are resolvable from the new application with identical instances
The system SHALL make the ImpressCMS core services available through the new application's service container: database, legacy database, configuration, security, logger, session, preload, current user and current module. For a given request, the container SHALL return the same instance as the corresponding `icms::$…` static property.

#### Scenario: Same security service
- **WHEN** a new-application controller obtains the ImpressCMS security service from the container and legacy code in the same request reads `icms::$security`
- **THEN** both refer to the same object instance

### Requirement: IPF and module handlers are resolvable from the new application with identical instances
The system SHALL make any handler that `icms::handler($name)` can resolve available through the new application's service container. This includes core handlers and IPF handlers of installed modules. The container SHALL return the same instance that `icms::handler($name)` returns in that request.

#### Scenario: Module IPF handler
- **WHEN** a new-application controller requests the handler for an installed IPF module object and legacy code in the same request calls `icms::handler()` with the same name
- **THEN** both receive the same handler instance, and objects created through it behave identically

#### Scenario: Unknown handler
- **WHEN** a handler name is requested that `icms::handler()` cannot resolve
- **THEN** the container raises a service-not-found error instead of returning `false`

### Requirement: Legacy `icms::` API remains backward compatible
The system SHALL keep the public static API of `icms` working with unchanged behaviour for all legacy callers: `icms::$…` service properties, `icms::handler()`, `icms::create()`, `icms::path()`, `icms::url()`, `icms::buildUrl()` and `icms::loadService()`. This includes the `loadService` and `loadService-<name>` events.

#### Scenario: Module boots as before
- **WHEN** an unmodified IPF module page is requested through the bridge
- **THEN** it renders the same output as before this change, and `loadService-<name>` listeners registered by preloads are still invoked

### Requirement: Legacy events are observable by the new application
The system SHALL dispatch every event triggered through the ImpressCMS event and preload mechanisms to listeners registered in the new application as well, with the same event name and parameters. Existing preload listeners SHALL keep receiving them.

#### Scenario: Listening to a legacy event
- **WHEN** a new-application listener subscribes to the legacy `loadService-db` event of the `icms` event namespace and a request boots the legacy runtime
- **THEN** the listener is invoked once with the database service as parameter

### Requirement: One dependency set per process
The system SHALL load at most one version of every third-party library per PHP process. The new application and the legacy application SHALL resolve classes through a single autoloader.

#### Scenario: Shared library version
- **WHEN** both the new application and legacy code use a Symfony component in the same request
- **THEN** both use the same installed version, and no "cannot redeclare class" error occurs

### Requirement: Modern and legacy class names resolve to the same class
For every entry in the ImpressCMS legacy alias map, under both the host and a direct `htdocs/` deployment, the system SHALL resolve the PSR-4 name (for example `Icms\Ipf\Handler`) and the legacy name (for example `icms_ipf_Handler`) to the same class. Each class file SHALL be loaded at most once, regardless of which name is referenced first.

#### Scenario: Legacy name first
- **WHEN** a module referencing `icms_ipf_Handler` is loaded before any code references `Icms\Ipf\Handler`
- **THEN** `icms_ipf_Handler` and `Icms\Ipf\Handler` are the same class, and `instanceof` checks succeed with either name

#### Scenario: Modern name first under the host
- **WHEN** a new-application service references `Icms\Core\Session` before any legacy code runs, and legacy code later references `icms_core_Session`
- **THEN** no redeclare error occurs and both names refer to the same class
