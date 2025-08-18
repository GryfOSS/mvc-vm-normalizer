#!/bin/bash

# Comprehensive test runner for CI/CD
# This script runs all tests and checks that are required for a successful build

set -e  # Exit on any error

echo "🚀 Starting Comprehensive Test Suite"
echo "===================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    local color=$1
    local message=$2
    echo -e "${color}${message}${NC}"
}

# Function to run a command and check its exit status
run_check() {
    local description=$1
    local command=$2

    print_status $BLUE "🔍 ${description}..."

    if eval "$command"; then
        print_status $GREEN "✅ ${description} - PASSED"
        return 0
    else
        print_status $RED "❌ ${description} - FAILED"
        return 1
    fi
}

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    print_status $RED "❌ Error: composer.json not found. Are you in the project root?"
    exit 1
fi

# Install dependencies if vendor directory doesn't exist
if [ ! -d "vendor" ]; then
    print_status $YELLOW "📦 Installing dependencies..."
    composer install --no-progress --optimize-autoloader
fi

echo ""
print_status $BLUE "Running validation checks..."
echo ""

# 1. Validate composer files
run_check "Composer validation" "composer validate --strict"

# 2. Security audit
run_check "Security audit" "composer audit"

# 3. Check PHP syntax
run_check "PHP syntax check" "find src tests features -name '*.php' -exec php -l {} \;"

echo ""
print_status $BLUE "Running test suites..."
echo ""

# 4. Run PHPUnit tests with coverage
if command -v xdebug &> /dev/null || php -m | grep -q xdebug; then
    run_check "PHPUnit tests with coverage" "XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-clover coverage/clover.xml --coverage-text"

    # 5. Verify 100% coverage
    run_check "100% coverage verification" "php bin/check-coverage.php"
else
    print_status $YELLOW "⚠️  XDebug not available, running PHPUnit without coverage"
    run_check "PHPUnit tests (no coverage)" "./vendor/bin/phpunit"
fi

# 6. Run Behat tests
run_check "Behat acceptance tests" "./vendor/bin/behat --format=progress"

echo ""
print_status $GREEN "🎉 All tests passed successfully!"
print_status $GREEN "🎯 Build is ready for deployment"

# Optional: Display coverage summary if available
if [ -f "coverage/clover.xml" ]; then
    echo ""
    print_status $BLUE "📊 Coverage Summary:"
    if command -v coverage-summary &> /dev/null; then
        coverage-summary coverage/clover.xml
    else
        echo "Coverage report generated in coverage/clover.xml"
    fi
fi

echo ""
print_status $GREEN "✨ Test suite completed successfully!"
