## ADDED Requirements

### Requirement: Durable structured event sink
The system SHALL write log events as one JSON object per line to a sink that is independent of the database, of `debug_mode` and of the `activated` flag of the debug logger. The default sink SHALL be PHP's configured error log. An administrator SHALL be able to configure a file sink (path set in `mainfile.php`) located outside the web root.

#### Scenario: Debug mode off
- **WHEN** `debug_mode` is off and a request raises an uncaught exception
- **THEN** a durable record of the exception is written to the configured sink
- **AND** the HTTP response contains no diagnostic details

#### Scenario: Database unavailable
- **WHEN** the database connection cannot be established
- **THEN** the event is still written to the sink

#### Scenario: Existing site without new configuration
- **WHEN** a site is upgraded without adding any new configuration
- **THEN** the default sink is used and the site behaves as before for visitors

### Requirement: Event contract
Every record SHALL contain a UTC timestamp, a severity, a stable event name, a request ID, a component, an outcome and a context object limited to allowlisted keys. Event names SHALL be stable identifiers and SHALL NOT embed variable data. All records of one request SHALL share the same request ID.

#### Scenario: Parsable record
- **WHEN** any event is written
- **THEN** the line is valid JSON containing all mandatory fields

#### Scenario: Correlation within a request
- **WHEN** two events occur in the same request
- **THEN** both carry the same request ID

### Requirement: Early initialization
The system SHALL initialize a minimal event handler before preloads run and before the logger service is initialized, and SHALL later integrate it with the existing debug logger without registering duplicate error or exception handlers.

#### Scenario: Preload failure is captured
- **WHEN** a preload throws during loading
- **THEN** the failure is recorded even though the logger service is not yet initialized

#### Scenario: No duplicate records
- **WHEN** a PHP error is raised after the logger service is initialized
- **THEN** exactly one durable record is written for it

### Requirement: Fatal and uncaught error capture
The system SHALL record uncaught `Throwable`s and fatal errors detectable at shutdown. Public responses SHALL stay generic when debug output is not permitted.

#### Scenario: Shutdown-detectable fatal
- **WHEN** a request ends with a fatal error
- **THEN** a record with severity `critical` and the error location is written

### Requirement: Severity threshold independent of debug settings
The system SHALL apply a persistent severity threshold that is configurable separately from `debug_mode` and `activated`. Verbose activity logging SHALL be disabled by default and opt-in.

#### Scenario: Below threshold
- **WHEN** an event has a severity below the configured threshold
- **THEN** it is not written

#### Scenario: Default threshold
- **WHEN** no threshold is configured
- **THEN** warnings and above are written and verbose activity events are not

### Requirement: Preserve existing logging behaviour
Existing debug-mode rendering, in-memory collection when debug is enabled, and the query-only `ICMS_LOGGING_HOOK` SHALL behave as before.

#### Scenario: Debug mode on
- **WHEN** debug mode is on
- **THEN** the debug console shows the same information as before this change

### Requirement: Sensitive data exclusion
Records SHALL NOT contain passwords, tokens, cookies, request bodies, connection strings, SQL text, bound values or personal data. Exception messages and file paths SHALL be sanitized, and paths SHALL be shown relative to the installation root.

#### Scenario: Database error
- **WHEN** a query with bound values fails
- **THEN** the record contains the error code and an operation identifier but neither the SQL nor the values

#### Scenario: Exception message with credentials
- **WHEN** an exception message contains a DSN with a password
- **THEN** the password does not appear in the record

### Requirement: Logging must not break the application
A failure of the sink SHALL NOT raise an error or exception to the caller and SHALL NOT trigger recursive logging. When the file sink is unwritable or the disk is full, the system SHALL fall back to PHP's error log.

#### Scenario: Unwritable file sink
- **WHEN** the configured file cannot be written
- **THEN** the event goes to PHP's error log and the request continues normally

### Requirement: Rate limiting
The system SHALL limit repeated identical events within a request and SHALL record that events were suppressed.

#### Scenario: Error in a loop
- **WHEN** the same error is raised thousands of times in one request
- **THEN** only a bounded number of records is written, followed by one record stating the suppressed count

### Requirement: Operator documentation
The documentation SHALL describe configuration usable before database initialization, the log location, file permissions, rotation and retention, the unwritable-sink fallback, and the role of PHP-FPM and web-server logs for failures that occur before ImpressCMS code runs.

#### Scenario: Pre-bootstrap failure guidance
- **WHEN** an owner reads the documentation about a white screen with no ImpressCMS record
- **THEN** it directs them to the PHP/server error log
