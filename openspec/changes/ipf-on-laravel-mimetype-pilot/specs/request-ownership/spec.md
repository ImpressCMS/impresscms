## ADDED Requirements

### Requirement: URL takeovers can be switched off by configuration
The system SHALL let site configuration disable each takeover of a legacy URL by the new application individually, without a code change or deploy. While a takeover is disabled, the URLs it claims SHALL be handled by the legacy application.

#### Scenario: Takeover disabled
- **WHEN** the takeover of the mimetype administration is disabled in the site configuration and a webmaster requests `/modules/system/admin.php?fct=mimetype`
- **THEN** the legacy application handles the request

#### Scenario: Takeover enabled
- **WHEN** the takeover is enabled and the same request arrives
- **THEN** the new application handles the request
