# RequestBag

**A simple, request-scoped data container for Laravel**

RequestBag provides an easy way to share data between classes during a single request lifecycle without passing parameters around. Perfect for middleware chains, Inertia.js setups, and any scenario where you need to share contextual data across your application.

## Why RequestBag?

- 🎯 **Request-scoped** - Data lives only for the duration of a single request
- 🚀 **No parameter passing** - Share data between classes without cluttering method signatures
- 🔒 **Type-safe** - Built with PHP 8.4+ and strict types
- 🎨 **Clean API** - Simple, intuitive methods that just work
- 🧪 **Fully tested** - Comprehensive test coverage with Pest
- ✨ **Laravel-friendly** - Facade support and automatic service provider registration

## Installation

Install via Composer:

```bash
composer require bensedev/request-bag
```

The service provider and facade are automatically registered via Laravel's package discovery.

## Usage

### Basic Usage

```php
use Bensedev\RequestBag\Facades\RequestBag;

// Add data
RequestBag::add('user_permissions', ['edit', 'delete', 'create']);
RequestBag::add('tenant_id', 123);

// Retrieve data
$permissions = RequestBag::get('user_permissions');
$tenantId = RequestBag::get('tenant_id');

// With default value
$theme = RequestBag::get('theme', 'light');

// Check if key exists and has value
if (RequestBag::has('user_permissions')) {
    // Key exists and is not empty
}

// Check if key exists (even if empty)
if (RequestBag::exists('theme')) {
    // Key exists
}
```

### Middleware Example

Share computed data from middleware with your controllers:

```php
namespace App\Http\Middleware;

use Bensedev\RequestBag\Facades\RequestBag;
use Closure;

class LoadUserPermissions
{
    public function handle($request, Closure $next)
    {
        $permissions = auth()->user()->permissions->pluck('name')->toArray();

        // Store in RequestBag instead of adding to request
        RequestBag::add('user_permissions', $permissions);
        RequestBag::add('is_admin', auth()->user()->isAdmin());

        return $next($request);
    }
}
```

Then access it anywhere in your application:

```php
namespace App\Http\Controllers;

use Bensedev\RequestBag\Facades\RequestBag;

class PostController extends Controller
{
    public function store()
    {
        // No need to pass permissions around!
        if (RequestBag::has('user_permissions')) {
            $permissions = RequestBag::get('user_permissions');

            if (in_array('create_post', $permissions)) {
                // Create post
            }
        }
    }
}
```

### Inertia.js Example

Share data with your Inertia frontend:

```php
namespace App\Http\Middleware;

use Bensedev\RequestBag\Facades\RequestBag;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                // Pull from RequestBag populated earlier in middleware chain
                'permissions' => RequestBag::get('user_permissions', []),
                'is_admin' => RequestBag::get('is_admin', false),
            ],
            'tenant' => [
                'id' => RequestBag::get('tenant_id'),
                'name' => RequestBag::get('tenant_name'),
            ],
        ]);
    }
}
```

### Service/Repository Pattern

Share computed data between services:

```php
namespace App\Services;

use Bensedev\RequestBag\Facades\RequestBag;

class TenantService
{
    public function loadTenantContext(int $tenantId): void
    {
        $tenant = Tenant::with('settings')->find($tenantId);

        RequestBag::merge([
            'tenant_id' => $tenant->id,
            'tenant_name' => $tenant->name,
            'tenant_settings' => $tenant->settings->toArray(),
        ]);
    }
}

class InvoiceService
{
    public function createInvoice(array $data): Invoice
    {
        // Access tenant data without dependency injection
        $tenantId = RequestBag::get('tenant_id');
        $settings = RequestBag::get('tenant_settings');

        return Invoice::create([
            'tenant_id' => $tenantId,
            'currency' => $settings['default_currency'],
            // ...
        ]);
    }
}
```

## API Reference

### Adding Data

```php
// Add a single value
RequestBag::add(string $key, mixed $value): self

// Merge multiple values
RequestBag::merge(array $data): self

// Example
RequestBag::add('user_id', 123);
RequestBag::merge(['theme' => 'dark', 'locale' => 'en']);
```

### Retrieving Data

```php
// Get a value (with optional default)
RequestBag::get(string $key, mixed $default = null): mixed

// Get all data
RequestBag::all(): array

// Example
$userId = RequestBag::get('user_id');
$theme = RequestBag::get('theme', 'light');
$all = RequestBag::all();
```

### Checking Data

```php
// Check if key exists and is not empty
RequestBag::has(string $key): bool

// Check if key exists (even if empty)
RequestBag::exists(string $key): bool

// Example
if (RequestBag::has('user_id')) {
    // Key exists and has a value
}

if (RequestBag::exists('theme')) {
    // Key exists (might be empty)
}
```

### Removing Data

```php
// Remove a specific key
RequestBag::remove(string $key): self

// Clear all data
RequestBag::clear(): self

// Example
RequestBag::remove('temp_data');
RequestBag::clear();
```

### Method Chaining

All methods that modify the bag return `self` for fluent chaining:

```php
RequestBag::add('key1', 'value1')
    ->add('key2', 'value2')
    ->merge(['key3' => 'value3'])
    ->remove('key1');
```

## Direct Class Usage

You can also inject the class directly instead of using the facade:

```php
use Bensedev\RequestBag\RequestBag;

class MyService
{
    public function __construct(
        private RequestBag $bag
    ) {}

    public function doSomething(): void
    {
        $this->bag->add('key', 'value');
    }
}
```

## Testing

Run the test suite:

```bash
composer test
```

Run PHPStan:

```bash
composer analyse
```

Run Laravel Pint:

```bash
composer format
```

## Requirements

- PHP 8.4 or higher
- Laravel 12.0 or higher

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [bensedev](https://github.com/bensedev)

## Support

If you discover any issues, please open an issue on GitHub.