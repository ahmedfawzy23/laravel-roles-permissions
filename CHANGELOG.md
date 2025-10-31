# Changelog

All notable changes to `laravel-roles-permissions` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned
- Permission groups and categories
- Role hierarchy support
- Team/workspace-based permissions
- GUI admin panel
- Permission wildcards
- Temporary permissions with expiration dates
- Audit logging for permission changes

## [1.0.0] - 2024-10-31

### Added
- Initial release of Laravel Roles & Permissions package
- Core roles and permissions functionality
- `HasRoles` trait for User model
- Support for multiple roles per user
- Support for multiple permissions per user and role
- Direct user permissions (bypass roles)
- `RoleMiddleware` for protecting routes by role
- `PermissionMiddleware` for protecting routes by permission
- Blade directives: `@role`, `@hasrole`, `@permission`, `@haspermission`
- RESTful API endpoints for role management
- RESTful API endpoints for permission management
- RESTful API endpoints for user role/permission assignment
- Database migrations for roles, permissions, and pivot tables
- Configuration file for customization
- Comprehensive README documentation
- MIT License
- PSR-12 coding standards compliance
- Laravel 10.x support
- Laravel 11.x support
- PHP 8.1+ support

### Features
- `assignRole()` - Assign single or multiple roles to users
- `removeRole()` - Remove roles from users
- `syncRoles()` - Sync user roles (replace all)
- `hasRole()` - Check if user has a specific role
- `hasAnyRole()` - Check if user has any of the specified roles
- `hasAllRoles()` - Check if user has all specified roles
- `givePermissionTo()` - Grant permissions to users or roles
- `revokePermissionTo()` - Revoke permissions from users or roles
- `syncPermissions()` - Sync user permissions
- `hasPermission()` - Check if user has permission (direct or via role)
- `hasDirectPermission()` - Check direct user permissions only
- `hasPermissionViaRole()` - Check permission through roles only
- `hasAnyPermission()` - Check if user has any of the specified permissions
- `hasAllPermissions()` - Check if user has all specified permissions
- `getAllPermissions()` - Get all user permissions (direct + role-based)

### API Endpoints
- `GET /api/roles-permissions/roles` - List all roles
- `POST /api/roles-permissions/roles` - Create new role
- `GET /api/roles-permissions/roles/{id}` - Get role details
- `PUT /api/roles-permissions/roles/{id}` - Update role
- `DELETE /api/roles-permissions/roles/{id}` - Delete role
- `POST /api/roles-permissions/roles/{id}/permissions/assign` - Assign permissions to role
- `POST /api/roles-permissions/roles/{id}/permissions/revoke` - Revoke permissions from role
- `GET /api/roles-permissions/permissions` - List all permissions
- `POST /api/roles-permissions/permissions` - Create new permission
- `GET /api/roles-permissions/permissions/{id}` - Get permission details
- `PUT /api/roles-permissions/permissions/{id}` - Update permission
- `DELETE /api/roles-permissions/permissions/{id}` - Delete permission
- `GET /api/roles-permissions/users/{id}/roles` - Get user roles
- `POST /api/roles-permissions/users/{id}/roles/assign` - Assign roles to user
- `POST /api/roles-permissions/users/{id}/roles/remove` - Remove roles from user
- `POST /api/roles-permissions/users/{id}/roles/sync` - Sync user roles
- `GET /api/roles-permissions/users/{id}/roles/check/{slug}` - Check if user has role
- `GET /api/roles-permissions/users/{id}/permissions` - Get user permissions
- `POST /api/roles-permissions/users/{id}/permissions/assign` - Assign permissions to user
- `POST /api/roles-permissions/users/{id}/permissions/revoke` - Revoke permissions from user
- `GET /api/roles-permissions/users/{id}/permissions/check/{slug}` - Check if user has permission

### Documentation
- Complete README with installation instructions
- Usage examples for all features
- API documentation with request/response examples
- Common use cases and patterns
- Troubleshooting guide
- Performance tips
- Security best practices
- Contributing guidelines

## [0.9.0-beta] - 2024-10-15

### Added
- Beta release for testing
- Core functionality implementation
- Basic test suite

### Changed
- Refined API structure
- Improved performance of permission checks

### Fixed
- Memory issues with large permission sets
- N+1 query problems in role loading

## [0.1.0-alpha] - 2024-09-01

### Added
- Initial alpha release
- Basic role and permission models
- Simple trait implementation

---

## Version History Notes

### Breaking Changes
None yet. First stable release.

### Deprecations
None.

### Security
No known security issues.

### Migration Notes

#### From 0.9.0-beta to 1.0.0
- No breaking changes
- Simply update composer package version
- Run `php artisan migrate` if using new migrations

---

## Upgrade Guide

### Upgrading to 1.0.0

If you're using the beta version, upgrade with:

```bash
composer update fawzy/roles-permissions
```

No additional steps required. All beta features are maintained in 1.0.0.

---

## Support

For issues, questions, or contributions, please visit:
- [GitHub Issues](https://github.com/ahmedfawzy23/roles-permissions/issues)
- [Documentation](https://github.com/ahmedfawzy23/roles-permissions/README.md)