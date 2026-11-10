## ADDED Requirements

### Requirement: Correlated upgrade operations
The system SHALL assign an upgrade operation ID when an upgrade or module update starts and SHALL include it in every event of that operation, including events in later requests of the same legacy upgrade run.

#### Scenario: Multi-request upgrade
- **WHEN** the legacy upgrade runner continues in a second request
- **THEN** its events carry the same operation ID as the first request

### Requirement: Lifecycle events
For the legacy upgrade runner, system migrations, module updates and the database updater, the system SHALL record start, each step with its actual result, failure and completion, including the module and the old and new database versions.

#### Scenario: Successful module update
- **WHEN** a module update completes
- **THEN** records exist for start, each step and completion, and completion shows the old and new versions

#### Scenario: Failed step
- **WHEN** a migration step fails
- **THEN** a record with severity `error` names the step, the module and the error code
- **AND** no completion-success record is written for that operation

### Requirement: Results are verified, not assumed
Success SHALL only be recorded when the underlying database or filesystem operation reported success. Reaching a target version or a function returning SHALL NOT by itself count as success.

#### Scenario: Silent failure
- **WHEN** a filesystem copy or table alteration returns failure
- **THEN** the step is recorded as failed

### Requirement: No success after swallowed failures
Exceptions and failures during migrations that are currently echoed or ignored SHALL be recorded and SHALL be reflected in the reported outcome so that the operation is never reported successful after a failed step. Existing retry behaviour SHALL be preserved, and any intentional change to what is reported to the user SHALL be documented.

#### Scenario: Exception during migration
- **WHEN** a migration throws and execution would previously have continued
- **THEN** the exception is recorded as a failure
- **AND** the operation outcome is `failed`

#### Scenario: Retry after failure
- **WHEN** an administrator re-runs an update after a failed one
- **THEN** it starts a new operation ID and can complete successfully

### Requirement: Upgrade logging independent of debug mode
Upgrade events SHALL be written whether debug mode is on or off and regardless of the severity threshold, and SHALL NOT include SQL text, bound values or credentials.

#### Scenario: Debug off during upgrade
- **WHEN** an upgrade runs with debug mode off
- **THEN** the full lifecycle is still recorded
