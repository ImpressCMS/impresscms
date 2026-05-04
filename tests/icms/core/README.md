# ImpressCMS Core Class Tests

This folder contains fast, isolated PHPUnit tests for foundational classes in `htdocs/libraries/icms/core`.

## Scope

- Characterization tests for legacy behavior
- No full CMS bootstrap
- No database dependency

## Run

```powershell
vendor\bin\phpunit --configuration phpunit.xml.dist --testsuite icms-core
```

