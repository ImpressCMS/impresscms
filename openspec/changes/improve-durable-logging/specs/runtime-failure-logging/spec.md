## ADDED Requirements

### Requirement: Bootstrap milestones and failures
The system SHALL record bootstrap milestones at verbose level and, when a service fails to initialize, SHALL record an error naming the failed service and the last service that started successfully.

#### Scenario: Service initialization fails
- **WHEN** a kernel service fails to initialize
- **THEN** a record identifies the failing service and the last started service

### Requirement: Database failure reporting
The system SHALL record failed database connections and failed operations on the connection execution paths (`query()`, `exec()` and `prepare()`; failures of `PDOStatement::execute()` are out of scope), with the error code and an operation identifier, without duplicating events already reported for the same failure.

#### Scenario: Connection failure
- **WHEN** the connection to the database fails
- **THEN** one `error` record is written with the driver error code and no connection string or credentials

#### Scenario: Prepare failure
- **WHEN** preparing a statement fails
- **THEN** exactly one record is written for the failure

### Requirement: Component loading failures
The system SHALL record failures to load preloads, modules and themes, identifying the component and the reason (for example missing file or incompatible code).

#### Scenario: Missing preload file after update
- **WHEN** a preload file referenced by the system is missing
- **THEN** a record names the preload and the reason, and the request continues if it did before

#### Scenario: Missing theme template
- **WHEN** a theme file required for rendering is missing
- **THEN** a record names the theme and the file relative to the installation root

### Requirement: Security-relevant events (later phase)
In a later phase the system SHALL record authentication outcomes and administrative configuration changes, with the user ID and outcome but without credentials, tokens or other personal data. These events SHALL be opt-in verbose activity events.

#### Scenario: Failed login
- **WHEN** a login fails
- **THEN** with activity logging enabled, a record with outcome `failure` is written containing no password and no submitted username or email address

#### Scenario: Activity logging off
- **WHEN** activity logging is not enabled
- **THEN** no authentication or admin-change records are written
