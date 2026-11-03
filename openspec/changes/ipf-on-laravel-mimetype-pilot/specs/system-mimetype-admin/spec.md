## Purpose

The mimetype administration of the system module, served by the framework application. URLs, output and storage behaviour stay those of the legacy page; saving and deleting are additionally protected by the ImpressCMS security token.

## ADDED Requirements

### Requirement: All mimetype admin requests are served by the framework application
The system SHALL handle every request to `/modules/system/admin.php` in which `fct` is `mimetype`, given either in the query string or in the POST body. All operations (`op`) of that function SHALL be handled. Requests for other admin functions SHALL remain legacy.

#### Scenario: List
- **WHEN** a webmaster requests `GET /modules/system/admin.php?fct=mimetype`
- **THEN** the framework application handles the request

#### Scenario: Delete confirmation posted without a query string
- **WHEN** the delete confirmation form posts to `/modules/system/admin.php` with `fct=mimetype` only in the POST body
- **THEN** the framework application handles the request

#### Scenario: Other admin function
- **WHEN** a webmaster requests `GET /modules/system/admin.php?fct=userrank`
- **THEN** the legacy application handles the request

### Requirement: The list is unchanged
The list view SHALL show the mimetypes with the columns name, extension and types. It SHALL keep the same sorting, paging and quick search, the "create" button and the explanatory text as the legacy page, and the same sort/paging query parameters and cookies.

#### Scenario: Snapshot parity
- **WHEN** a webmaster requests the list with the fixture data
- **THEN** the normalised response equals the characterization snapshot that was recorded from the legacy page

#### Scenario: Sorting
- **WHEN** a webmaster requests the list with `mimetype_sortsel=extension` and `mimetype_ordersel=DESC`
- **THEN** the rows are ordered by extension, descending, as in legacy

### Requirement: The create and edit form is unchanged
`op=mod` SHALL show the create form when no existing `mimetypeid` is given, and the edit form for an existing one. Fields, captions, values, permission selection and page title SHALL be the same as on the legacy page. The form SHALL additionally contain an ImpressCMS security token.

#### Scenario: Edit form
- **WHEN** a webmaster requests `op=mod&mimetypeid=<id>` for an existing mimetype
- **THEN** the form shows that mimetype's values and its `use_extension` groups, and contains a security token

### Requirement: Saving stores the mimetype and its permissions
Posting the form (`op=addmimetype`) with a valid token and valid values SHALL create or update the mimetype and its `use_extension` permission. It SHALL then show the "created" or "modified" message and return to the page before the form, as in legacy. Invalid values SHALL store nothing and SHALL report the error as the legacy page does.

#### Scenario: Create
- **WHEN** a webmaster submits a new mimetype with a valid token
- **THEN** the mimetype and its permissions are stored, and the "created" message leads back to the list

#### Scenario: Save without a token
- **WHEN** the form is posted without a valid security token
- **THEN** nothing is stored and the response reports an invalid token

### Requirement: Deleting requires confirmation and a token
`op=del` SHALL first show the confirmation for the selected mimetype, as in legacy. Only a confirmed POST with a valid security token SHALL delete the mimetype together with its permissions, then show the "deleted" message and return to the previous page. A request for an unknown mimetype SHALL report "not selected", as in legacy.

#### Scenario: Confirmed delete
- **WHEN** a webmaster confirms the deletion of a mimetype with a valid token
- **THEN** the mimetype and its `use_extension` permissions are removed, and the "deleted" message is shown

#### Scenario: Replayed or missing token
- **WHEN** the confirmation is posted with a missing or already used token
- **THEN** nothing is deleted

#### Scenario: Unknown mimetype
- **WHEN** `op=del` is requested for a `mimetypeid` that does not exist
- **THEN** the "not selected" message is shown and nothing is deleted
