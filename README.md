MVC View Model Normalizer
=========================

This adds the `DefaultViewModel` attribute into the system and `ViewSubscriber` which hooks into the `kernel.view` event.
Changes a model or entity into the defined view model before passing for further serialization.

Usage:
1. Be sure to add `ViewSubscriber` to your `services.yaml` file:

```yaml
GryfOSS\Mvc\Subscriber\ViewSubscriber:
   autowire: true
   autoconfigure: true
```

2. Add to to classes which should have the ViewModel attached:

```php
#[DefaultViewModel(viewModelClass: AbcViewModel::class)]
```

where `AbcViewModel::class` is the target view model.