# Release Guide for VIT ID Authentication Extension

This guide is for maintainers who need to create new releases of the extension.

## Overview

The extension uses a build script to create production-ready release packages that include all dependencies. This ensures users can deploy without requiring composer on their servers.

## Version Numbering

Follow [Semantic Versioning](https://semver.org/):

- **MAJOR** (1.x.x): Breaking changes, incompatible API changes
- **MINOR** (x.1.x): New features, backward compatible
- **PATCH** (x.x.1): Bug fixes, backward compatible

## Release Process

### 1. Prepare the Release

Update version numbers in these files:

```bash
# Update info.xml
<version>1.0.0</version>

# Optionally update CHANGELOG.md (if you maintain one)
```

### 2. Test Thoroughly

Before creating a release:

- [ ] Test on development environment
- [ ] Verify all Auth0 integration points work
- [ ] Test role mapping functionality
- [ ] Test logout for both VIT ID and local users
- [ ] Verify error messages display correctly
- [ ] Test on production-like environment (Docker)
- [ ] Check PHP 8.1, 8.2, 8.3 compatibility
- [ ] Verify MariaDB/MySQL compatibility

### 3. Build the Release Package

Run the build script:

```bash
cd vitid_auth0
./build-release.sh
```

The script will:

1. Clean previous builds
2. Install dependencies with composer
3. Create a clean zip package
4. Name it `vitid_auth0-v{VERSION}.zip`
5. Exclude development files

Expected output:

```
Building VIT ID Authentication Extension
Version: 1.0.0

Cleaning previous builds...
Installing dependencies...
✓ Dependencies installed
Creating release package...
✓ Release package created: vitid_auth0-v1.0.0.zip (2.5M)

═══════════════════════════════════════
Build completed successfully!
═══════════════════════════════════════

Package: vitid_auth0-v1.0.0.zip
Version: 1.0.0
Size:    2.5M
```

### 4. Test the Release Package

Before distributing, test the release package:

```bash
# Extract to temporary location
mkdir -p /tmp/test-release
cd /tmp/test-release
unzip /path/to/vitid_auth0-v1.0.0.zip

# Verify vendor directory exists
ls -la vitid_auth0-v1.0.0/vendor/

# Check critical dependencies
ls vitid_auth0-v1.0.0/vendor/auth0/
ls vitid_auth0-v1.0.0/vendor/psr/

# Test installation in CiviCRM test environment
# (Follow INSTALL.md steps)
```

### 5. Create Git Tag

Tag the release in git:

```bash
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### 6. Distribute the Release

Upload the release package:

1. **GitHub Releases** (recommended):

   - Go to repository → Releases → Draft a new release
   - Choose the tag you created
   - Upload `vitid_auth0-v1.0.0.zip`
   - Add release notes

2. **Internal Distribution**:

   - Upload to organization's file server
   - Document download location in internal docs

3. **CiviCRM Extensions Directory** (optional):
   - Submit to [CiviCRM Extensions](https://civicrm.org/extensions)
   - Follow their submission guidelines

### 7. Update Documentation

After release:

- Update README.md with latest version number
- Update INSTALL.md if installation steps changed
- Document breaking changes (if any)
- Update internal deployment guides

## Release Checklist

Use this checklist for each release:

### Pre-Release

- [ ] All tests passing
- [ ] Version updated in info.xml
- [ ] CHANGELOG.md updated (if maintained)
- [ ] Code reviewed
- [ ] Documentation updated
- [ ] Breaking changes documented

### Release

- [ ] Build script executed successfully
- [ ] Release package tested
- [ ] Git tag created and pushed
- [ ] Release package uploaded to distribution point
- [ ] Release notes written

### Post-Release

- [ ] Deployment guide updated
- [ ] Users/admins notified
- [ ] Production environment updated
- [ ] Monitoring for issues enabled

## Build Script Details

### What the Script Does

The `build-release.sh` script:

1. **Extracts version** from `info.xml`
2. **Cleans** previous builds (removes vendor/, \*.zip)
3. **Installs dependencies** with:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
4. **Creates package** excluding:

   - `.git*` files
   - `.DS_Store` files
   - `*.zip` files
   - `build-release.sh` itself
   - `composer.lock`

5. **Outputs** `vitid_auth0-v{VERSION}.zip`

### Customizing the Build

To modify what's excluded from the release:

Edit the `rsync` command in `build-release.sh`:

```bash
rsync -av \
    --exclude='.git*' \
    --exclude='.DS_Store' \
    --exclude='*.zip' \
    --exclude='build-release.sh' \
    --exclude='composer.lock' \
    --exclude='YOUR_CUSTOM_EXCLUSION' \
    ./ "${PACKAGE_DIR}/"
```

## Hotfix Releases

For urgent bug fixes:

1. Create hotfix branch from tagged release:

   ```bash
   git checkout -b hotfix/1.0.1 v1.0.0
   ```

2. Make minimal changes to fix the issue

3. Update version to patch number (e.g., 1.0.1)

4. Follow standard release process

5. Merge back to main branch:
   ```bash
   git checkout main
   git merge hotfix/1.0.1
   git push
   ```

## Rollback Procedure

If a release has critical issues:

1. **Immediate action**: Notify all users, remove download links

2. **For users who installed**:

   - Provide rollback instructions
   - Share previous stable version
   - Document manual fixes if possible

3. **Fix the issue**:

   - Create hotfix branch
   - Fix the problem
   - Release new version

4. **Post-mortem**:
   - Document what went wrong
   - Update testing procedures
   - Improve build process if needed

## Dependency Updates

To update dependencies:

```bash
# Update composer.json with new versions
# Then test thoroughly

composer update --no-dev

# If successful, commit composer.json
git add composer.json
git commit -m "Update dependencies"
```

Common dependencies to monitor:

- `auth0/auth0-php`: Auth0 SDK updates
- PSR implementations (PSR-7, PSR-17, PSR-18)

## Support and Maintenance

### Long-term Support

Define your support policy:

- Bug fixes for current major version
- Security updates for previous major version
- Migration guides for major version upgrades

### End of Life

When deprecating a major version:

1. Announce 6 months in advance
2. Provide migration guide
3. Continue critical security fixes
4. Document EOL date clearly

## Contacts

**Maintainer**: Andrea Caselli (acaselli@itatti.harvard.edu)

**Repository**: [Add your repository URL]

**Issues**: [Add your issue tracker URL]
