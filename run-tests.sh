#!/bin/bash

# Test runner script for MVC View Model Normalizer

echo "=== GryfOSS MVC View Model Normalizer Test Suite ==="
echo ""

# Check if composer dependencies are installed
if [ ! -d "vendor" ]; then
    echo "Installing dependencies..."
    composer install
    echo ""
fi

# Run PHPUnit with coverage
echo "Running tests with coverage..."
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html coverage/html --coverage-text --coverage-clover coverage/clover.xml

echo ""
echo "=== Test Results ==="
echo "- HTML Coverage Report: coverage/html/index.html"
echo "- Clover Coverage Report: coverage/clover.xml"
echo "- Text Coverage: displayed above"
echo ""
echo "Tests completed!"
