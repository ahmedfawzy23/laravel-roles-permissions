# Contributing to Laravel Roles & Permissions

First off, thank you for considering contributing to Laravel Roles & Permissions! It's people like you that make this package better for everyone.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the issue list as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

* **Use a clear and descriptive title**
* **Describe the exact steps to reproduce the problem**
* **Provide specific examples to demonstrate the steps**
* **Describe the behavior you observed and what behavior you expected**
* **Include Laravel version, PHP version, and package version**
* **Include any error messages or stack traces**

#### Example Bug Report

```markdown
**Bug Description:**
When assigning multiple roles, the user loses existing roles.

**Steps to Reproduce:**
1. Create a user with 'admin' role
2. Call `$user->assignRole(['editor', 'author'])`
3. Check user roles

**Expected Behavior:**
User should have all three roles: admin, editor, and author

**Actual Behavior:**
User only has editor and author roles

**Environment:**
- Laravel Version: 10.32.1
- PHP Version: 8.2.0
- Package Version: 1.0.0
```

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please include:

* **Use a clear and descriptive title**
* **Provide a detailed description of the suggested enhancement**
* **Provide specific examples to demonstrate how the enhancement would work**
* **Explain why this enhancement would be useful**

### Pull Requests

* Fill in the required template
* Do not include issue numbers in the PR title
* Follow the PHP coding standards (PSR-12)
* Include tests for new features
* Update documentation as needed
* End all files with a newline

#### Pull Request Process

1. **Fork the repository** and create your branch from `main`
   ```bash
   git checkout -b feature/my-new-feature
   ```

2. **Make your changes** and ensure:
   - Code follows PSR-12 standards
   - All tests pass
   - New features have tests
   - Documentation is updated

3. **Commit your changes** with clear commit messages:
   ```bash
   git commit -m "Add feature: role inheritance"
   ```

4. **Push to your fork**:
   ```bash
   git push origin feature/my-new-feature
   ```

5. **Open a Pull Request** with:
   - Clear title and description
   - Link to any related issues
   - Screenshots if applicable

## Development Setup

### Requirements

- PHP 8.1 or higher
- Composer
- Laravel 10.x or 11.x

### Setup Instructions

1. Clone your fork:
   ```bash
   git clone https://github.com/your-username/roles-permissions.git
   cd roles-permissions
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Run tests:
   ```bash
   composer test
   ```

### Running Tests

We use PHPUnit for testing. Run the test suite with:

```bash
# Run all tests
composer test

# Run specific test file
./vendor/bin/phpunit tests/RoleTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage
```

### Code Style

We follow PSR-12 coding standards. Before submitting, run:

```bash
# Check code style
composer check-style

# Fix code style automatically
composer fix-style
```

## Coding Guidelines

### General Rules

1. **Keep it simple** - Favor simplicity over cleverness
2. **Write tests** - All new features must have tests
3. **Document your code** - Use PHPDoc blocks for all public methods
4. **Follow conventions** - Match the existing code style

### Example Code Style

```php
<?php

namespace YourVendor\RolesPermissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Role Model
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get the permissions for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
```

### Naming Conventions

- **Classes**: PascalCase (e.g., `RoleController`)
- **Methods**: camelCase (e.g., `assignRole`)
- **Variables**: camelCase (e.g., `$userRole`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MAX_ROLES`)
- **Database columns**: snake_case (e.g., `user_id`)

### Testing Guidelines

Write tests for:
- All new features
- Bug fixes
- Edge cases

Example test:

```php
public function test_user_can_have_multiple_roles()
{
    $user = User::factory()->create();
    $admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
    $editor = Role::create(['name' => 'Editor', 'slug' => 'editor']);

    $user->assignRole(['admin', 'editor']);

    $this->assertTrue($user->hasRole('admin'));
    $this->assertTrue($user->hasRole('editor'));
    $this->assertCount(2, $user->roles);
}
```

## Documentation

### Updating Documentation

When adding features:
1. Update README.md with examples
2. Add inline documentation (PHPDoc)
3. Update CHANGELOG.md
4. Consider adding to Wiki

### Documentation Style

- Use clear, concise language
- Provide code examples
- Explain the "why" not just the "how"
- Keep examples realistic and practical

## Commit Messages

### Format

```
<type>: <subject>

<body>

<footer>
```

### Types

- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation changes
- **style**: Code style changes (formatting, etc.)
- **refactor**: Code refactoring
- **test**: Adding or updating tests
- **chore**: Maintenance tasks

### Examples

```
feat: add role hierarchy support

Implements parent-child relationships between roles, allowing
roles to inherit permissions from parent roles.

Closes #123
```

```
fix: prevent duplicate role assignment

Fixed issue where assigning the same role twice would create
duplicate entries in the database.

Fixes #456
```

## Release Process

(For maintainers only)

1. Update version in `composer.json`
2. Update CHANGELOG.md
3. Create git tag: `git tag v1.x.x`
4. Push tag: `git push origin v1.x.x`
5. Create GitHub release with notes

## Questions?

Feel free to:
- Open an issue for discussion
- Join our Discord/Slack community
- Email the maintainers

## Recognition

Contributors will be:
- Listed in the project's contributors list
- Mentioned in release notes for significant contributions
- Given credit in the documentation

Thank you for contributing! 🎉