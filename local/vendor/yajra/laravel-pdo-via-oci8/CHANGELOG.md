# PDO-VIA-OCI8 Change Log

## v1.3.5 - 2017-11-28

- Do not quote numeric values. #51
- Fix changelog markdown lint.

## v1.3.4 - 2017-11-16

- Changed to LONG type because number type can not be registered normally [#42](https://github.com/yajra/pdo-via-oci8/pull/49) credits to @reo0306.

## v1.3.3 - 2017-11-16

- Handle ROWID output exception. [#49](https://github.com/yajra/pdo-via-oci8/pull/49)

## v1.3.2 - 2017-06-24

- Fixes for Array bindings. 
- PR [#43](https://github.com/yajra/pdo-via-oci8/pull/43) credits to @shannonfairchild.

## v1.3.1 - 2017-02-02

- Fix fetchAll fetchMode parameter and set to null by default.
- Fix https://github.com/yajra/laravel-oci8/issues/246.

## v1.3.0 - 2017-01-05

- Add .gitattributes and config files.
- Add code of conduct.
- Add change log.
- Update license to 2017.
- Add github templates.

## v1.2.1

- Change regex replacement pattern in prepare. PR #38, credits to @jjware.
- Fix #37.
- Refactor regex replacement using preg_replace_callback.

## v1.2.0

- Implement closeCursor. 
- Add oci8 connection options getter.

## v1.1.1

- 3rd argument in Statement::fetchAll(), $ctorArgs, should allow null. 
- PR #34, credits to @nhowell.

## v1.1.0

- Add support for CLOB data types. 
- PR #31, credits to @Tylerian.

## v1.0.5

- fetchAll also checks if fetch() returning object.
- PR #30, credits to @apit

## v1.0.4

- Fix non-FETCH_COLUMN queries.
- PR #29, credits to @snelg

## v1.0.3

- Function fetchAll() bug fix. Fix #27
- PR #28, credits to @dkochnov.

## v1.0.2

- Parse DSN - check charset option in connection string and clean "PDO style".
- PR #26, credits to @eisberg.

## v1.0.1

- PHP 7 fix for bindValue. PR #23 by @snelg.

## v1.0.0

- Change of namespace from yajra to Yajra (capital Y).
- Enhance error dump with proper bindings.
- Convert to PSR-2 & PSR-4 standard.
- Safe refactoring of variable names.
- Auto-saving of BLOB objects.

## v0.15.0

- Account for PDO::PARAM_INPUT_OUTPUT in bindParam.

## v0.14.0

- Add bindArray special non-pdo function for extensive support for oci_bind_array_by_name.

## v0.13.0

- Add support for oci_bind_array_by_name.

## v0.12.0

- Add support for procedure returning a cursor.

## v0.11.0

- Rename github package name to pdo-via-oci8 from laravel-pdo-via-oci8 making the package not specific to Laravel.
- Sequence name can now be passed in the `lastInsertId` function. Table name that will be used by default will be based on the last table used on insert query.

```php
$pdo->lastInsertId(); // will use TABLE_ID_SEQ
$pdo->lastInsertId('TABLE_SEQ'); // will use TABLE_SEQ
```
