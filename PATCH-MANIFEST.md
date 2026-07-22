# Daftar File Patch

## File aplikasi yang diganti

```text
app/Models/User.php
app/Http/Controllers/AuthController.php
app/Http/Controllers/AccountController.php
app/Http/Controllers/Admin/UserController.php
resources/views/auth/login.blade.php
resources/views/auth/register.blade.php
resources/views/account/profile.blade.php
resources/views/admin/users/_form.blade.php
resources/views/admin/users/index.blade.php
tests/Feature/AuthenticationTest.php
database/factories/UserFactory.php
```

## File baru

```text
database/migrations/2026_07_22_000001_add_username_to_users_table.php
.github/PULL_REQUEST_TEMPLATE.md
.github/workflows/tests.yml
GITHUB-SETUP.md
APPLY-USERNAME-AUTH.md
APPLY_PATCH_WINDOWS.bat
PATCH-MANIFEST.md
```

## File repository yang diganti

```text
.gitignore
.gitattributes
```
