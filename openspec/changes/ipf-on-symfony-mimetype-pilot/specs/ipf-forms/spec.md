## Purpose

Builds, binds and validates forms for framework-handled pages from the metadata of an IPF entity: its variable definitions, controls and handler permissions. An IPF object therefore needs no hand-written form, and submitted data keeps the legacy field names and storage behaviour.

## ADDED Requirements

### Requirement: Form fields are derived from entity metadata
The system SHALL derive one form field per entity variable that is shown in the IPF form. The field's name, label, description, required flag, maximum length and options SHALL come from the variable definition and its control, exactly as the legacy IPF form would use them. Variables that the legacy form hides (such as the key field) SHALL be carried as hidden values, not as editable fields.

#### Scenario: Mimetype entity
- **WHEN** a form is built for a mimetype entity
- **THEN** it has the editable fields `extension`, `types`, `name` and `dirname` and the permission `use_extension`, with the captions and descriptions the legacy form shows, plus `mimetypeid` as a hidden value

#### Scenario: Select-multi control with an item handler
- **WHEN** a variable has the control `selectmulti` with `itemHandler` `icms_module` and method `getActive`
- **THEN** the field offers the same options, in the same order, as the legacy form's select list

### Requirement: Legacy field names are preserved
The form SHALL read submitted values under the same top-level request keys that the legacy IPF form uses (for example `extension`, `dirname[]` and the permission name). It SHALL NOT nest them under a form name, so that HTML rendered by the legacy IPF form can be submitted to it unchanged.

#### Scenario: Legacy form posted to the framework form
- **WHEN** the HTML produced by the legacy IPF form for a mimetype is submitted
- **THEN** every submitted value is bound to the corresponding entity variable

### Requirement: Validation follows the variable definitions
The system SHALL reject a submission that violates the variable definitions: a missing value for a required variable, a value longer than the defined maximum length, or a value outside the allowed options of a select control. The error SHALL name the field by its caption.

#### Scenario: Required field left empty
- **WHEN** a mimetype is submitted with an empty `extension`
- **THEN** the submission is rejected, nothing is stored, and the error names the extension field

### Requirement: Valid submissions are applied to the IPF entity
When a submission is valid, the system SHALL apply the submitted values to the IPF entity through its normal setters. It SHALL store the entity through its IPF handler, with that handler's events (`beforeSave`, `afterSave`, …) executed as in the legacy controller.

#### Scenario: Storing a new mimetype
- **WHEN** a valid new mimetype is submitted
- **THEN** a row is inserted through the mimetype handler, with the submitted values exactly as the legacy controller would store them

### Requirement: Handler permissions are part of the form
For each permission that the entity's handler declares, the form SHALL contain a group selection field named after the permission. On a successful save, the selected groups SHALL be stored as that item's permission, replacing the previous selection, as the legacy controller does.

#### Scenario: Mimetype upload permission
- **WHEN** a mimetype is saved with groups 1 and 2 selected for `use_extension`
- **THEN** exactly groups 1 and 2 hold the `use_extension` permission for that mimetype afterwards

### Requirement: Unsupported metadata is rejected explicitly
If an entity uses a variable type or control that the form building does not support, the system SHALL refuse to build the form and raise an error that names the variable and the control. Such entities are left to their legacy pages.

#### Scenario: Upload control
- **WHEN** a form is built for an entity with a variable using the `upload` control
- **THEN** building fails with an error that names that variable and the `upload` control
