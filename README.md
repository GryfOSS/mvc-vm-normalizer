MVC View Model Normalizer
=========================

![Tests](https://github.com/praetoriantechnology/mvc-vm-normalizer/workflows/tests/badge.svg)
![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)
![PHP](https://img.shields.io/badge/php-8.2%20%7C%208.3-blue)
![Tests](https://img.shields.io/badge/tests-PHPUnit%20%2B%20Behat-blue)

This adds the `DefaultViewModel` attribute into the system and a Symfony Serializer normalizer that automatically transforms entities into their designated ViewModels during serialization.

## ✨ Features

- **Attribute-based Configuration**: Use `#[DefaultViewModel]` to specify ViewModels
- **SerializedName Support**: Full support for `#[SerializedName('alias')]` attributes
- **Nested Object Handling**: Automatic handling of nested ViewModels
- **Collection Support**: Transform arrays/collections of objects
- **100% Test Coverage**: Comprehensive PHPUnit + Behat test coverage
- **CI/CD Ready**: Complete GitHub Actions workflows

## 🚀 Quick Start

### Installation

```bash
composer require gryfoss/mvc-vm-normalizer
```

### Configuration

Configure the normalizer in your `services.yaml`:

```yaml
  GryfOSS\Mvc\Normalizer\DefaultViewModelNormalizer:
    tags:
      - { name: serializer.normalizer, priority: 100 }
```

### Basic Usage

1. Add the attribute to your entities:

```php
#[DefaultViewModel(viewModelClass: UserViewModel::class)]
class User implements NormalizableInterface
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private int $age
    ) {}

    // getters...
}
```

2. Create your ViewModel:

```php
class UserViewModel implements ViewModelInterface
{
    public function __construct(private User $user) {}

    #[SerializedName('n')]
    public function getName(): string
    {
        return $this->user->getFirstName() . ' ' . $this->user->getLastName();
    }

    #[SerializedName('a')]
    public function getAge(): int
    {
        return $this->user->getAge();
    }
}
```

**Result:**
```json
{
  "n": "John Doe",
  "a": 30
}
```

## 🧪 Testing

### Run All Tests
```bash
./bin/run-ci-tests.sh
```

### Individual Test Suites
```bash
# PHPUnit tests with coverage
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-text

# Behat acceptance tests
./vendor/bin/behat

# Coverage verification
php bin/check-coverage.php
```

## 📚 Documentation

- **[CI/CD Pipeline](CI-CD.md)**: Complete GitHub Actions setup
- **[Behat Tests](features/README.md)**: Acceptance test documentation
- **[Coverage Reports](coverage/html/)**: Detailed coverage analysis

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Run tests: `./bin/run-ci-tests.sh`
4. Ensure 100% coverage
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License.