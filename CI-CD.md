# CI/CD Pipeline Documentation

This project uses GitHub Actions for comprehensive continuous integration and deployment pipelines.

## 🚀 Workflows Overview

### 1. **CI Pipeline** (`.github/workflows/ci.yml`)

**Triggers:** Push to `main`, `develop`, `tests` branches and all pull requests

**Jobs:**
- **Test Suite**: Runs on PHP 8.2 and 8.3
  - PHPUnit tests with 100% coverage verification
  - Behat acceptance tests
  - Composer validation and security audit
  - Coverage reports uploaded to Codecov
- **Code Quality**: Linting and static analysis
- **Matrix Check**: Ensures all matrix jobs pass

### 2. **Release Pipeline** (`.github/workflows/release.yml`)

**Triggers:** Version tags (`v*`) and GitHub releases

**Features:**
- Comprehensive testing across PHP versions
- Package integrity verification
- Production autoloader testing
- Critical 100% coverage enforcement
- Distribution package creation

### 3. **Nightly Tests** (`.github/workflows/nightly.yml`)

**Triggers:** Daily at 2 AM UTC and manual dispatch

**Features:**
- Tests with lowest, locked, and highest dependencies
- Security vulnerability scanning
- Automatic issue creation on failure
- Dependency freshness monitoring

## 🧪 Local Testing

### Quick Test Run
```bash
# Run all tests locally
./bin/run-ci-tests.sh
```

### Individual Test Suites
```bash
# PHPUnit with coverage
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-text

# Behat acceptance tests
./vendor/bin/behat

# Coverage verification
php bin/check-coverage.php

# Get coverage percentage for badges
php bin/coverage-badge.php
```

## 📊 Coverage Requirements

- **Minimum Coverage**: 100% line coverage required
- **Coverage Types**: Lines, Methods, and Classes tracked
- **Verification**: Automated enforcement in all pipelines
- **Reports**: HTML reports generated in `coverage/html/`

## 🔧 Scripts

### `bin/run-ci-tests.sh`
Comprehensive test runner that mimics CI environment:
- Validates composer files
- Runs security audit
- Executes PHPUnit with coverage
- Runs Behat tests
- Verifies 100% coverage
- Colorized output with status indicators

### `bin/check-coverage.php`
Coverage verification script:
- Runs PHPUnit with coverage
- Extracts coverage percentage
- Exits with error if not 100%
- Used by CI for quality gates

### `bin/coverage-badge.php`
Badge-friendly coverage reporter:
- Outputs just the coverage percentage
- Used for generating coverage badges
- Silent operation for automation

## 🏷️ Badges

Add these badges to your README:

```markdown
![CI](https://github.com/praetoriantechnology/mvc-vm-normalizer/workflows/CI%20Pipeline/badge.svg)
![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)
![PHP](https://img.shields.io/badge/php-8.2%20%7C%208.3-blue)
```

## 🚨 Quality Gates

All pipelines enforce these quality gates:

1. **✅ Code Syntax**: PHP syntax validation
2. **✅ Composer Validation**: Valid and secure composer files
3. **✅ Security Audit**: No known vulnerabilities
4. **✅ Unit Tests**: All PHPUnit tests pass
5. **✅ Integration Tests**: All Behat scenarios pass
6. **✅ 100% Coverage**: Line coverage must be exactly 100%
7. **✅ Package Integrity**: Production package loads correctly

## 🔄 Workflow Status

### Matrix Testing
- **PHP Versions**: 8.2, 8.3
- **Dependencies**: lowest, locked, highest (nightly)
- **Operating System**: Ubuntu Latest

### Failure Handling
- **Nightly failures**: Automatic GitHub issue creation
- **PR failures**: Block merge until resolved
- **Release failures**: Prevent tag deployment

## 📈 Monitoring

### Coverage Tracking
- Codecov integration for trend analysis
- HTML reports archived for 30 days
- Coverage badge auto-updated

### Security Monitoring
- Daily dependency vulnerability scans
- Composer security audit on every build
- Automated security issue reporting

## 🎯 Best Practices

### For Contributors
1. Run `./bin/run-ci-tests.sh` before committing
2. Ensure 100% test coverage for new code
3. Add Behat scenarios for new features
4. Update tests when modifying existing code

### For Maintainers
1. Review coverage reports before merging PRs
2. Monitor nightly test failures
3. Keep dependencies updated
4. Verify release pipeline before tagging

This comprehensive CI/CD setup ensures code quality, security, and reliability at every stage of development.
