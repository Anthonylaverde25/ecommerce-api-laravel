# 📚 Errores Comunes en Clean Architecture con Laravel - Guía de Estudio

Este documento detalla los errores encontrados durante el desarrollo del módulo de Products y cómo solucionarlos para no repetirlos en el futuro.

---

## ❌ Error #1: Interface no registrada en el Service Container

### 🔴 Mensaje de Error
```
Target [Application\Product\Contracts\ProductRepositoryInterface] is not instantiable 
while building [App\Presentation\API\Category\Controllers\ProductController, 
App\Application\Product\UseCases\Product\ProductCrudUseCase].
```

### 🤔 ¿Qué pasó?
Laravel intentó inyectar `ProductRepositoryInterface` en el controlador, pero el contenedor de servicios **no sabía qué implementación concreta usar**.

### 🎯 Causa raíz
No se creó un **Service Provider** para registrar el binding entre la interfaz y su implementación.

### ✅ Solución
Crear un `ProductServiceProvider`:

```php
<?php
namespace Infrastructure\Laravel\Providers;

use Application\Product\Contracts\ProductRepositoryInterface;
use Infrastructure\Persistence\Eloquent\Repositories\EloquentProductRepository;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding: cuando alguien pida ProductRepositoryInterface,
        // Laravel le dará EloquentProductRepository
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );
    }
}
```

Y registrarlo en `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    Infrastructure\Laravel\Providers\CategoryServiceProvider::class,
    Infrastructure\Laravel\Providers\ProductServiceProvider::class, // ← AGREGAR
];
```

### 📝 Lecciones aprendidas

> [!IMPORTANT]
> **Regla de oro**: Por cada **repositorio** o **servicio** que uses en Clean Architecture, DEBES crear un binding en un Service Provider.

**Checklist al crear un nuevo módulo:**
- [ ] ¿Creé la interfaz (contrato) en la capa de Application?
- [ ] ¿Creé la implementación en la capa de Infrastructure?
- [ ] ✨ **¿Creé el Service Provider para el binding?**
- [ ] ✨ **¿Registré el Service Provider en `bootstrap/providers.php`?**

---

## ❌ Error #2: Namespace incorrecto en la entidad del dominio

### 🔴 Mensaje de Error
```
Class "Domain\Item\Entities\Item" not found
```

### 🤔 ¿Qué pasó?
El archivo de la entidad `Item.php` estaba ubicado en:
```
app/Domain/Product/Entities/Item.php
```

Pero el namespace declarado dentro del archivo era:
```php
namespace Domain\Item\Entities;  // ❌ INCORRECTO
```

### 🎯 Causa raíz
**Desincronización entre la estructura de carpetas y el namespace declarado**.

### ✅ Solución
El namespace debe coincidir exactamente con la ruta del archivo:

```php
// ✅ CORRECTO
namespace Domain\Product\Entities;
```

### 📝 Lecciones aprendidas

> [!WARNING]
> En PHP moderno con PSR-4, el **namespace DEBE coincidir exactamente con la estructura de carpetas**.

**Fórmula para calcular el namespace:**
```
Ruta del archivo:  app/Domain/Product/Entities/Item.php
Namespace base:    (configurado en composer.json)
Namespace final:   Domain\Product\Entities
```

**Checklist al crear una nueva clase:**
- [ ] ¿La estructura de carpetas refleja el namespace?
- [ ] ¿El namespace en el archivo coincide con la ruta?
- [ ] ¿Ejecuté `composer dump-autoload` si creé nuevas carpetas?

**Tip profesional:**
La mayoría de los IDEs (PHPStorm, VSCode con extensiones) pueden **auto-generar el namespace correcto** cuando creas un archivo.

---

## ❌ Error #3: Constructor privado impide la instanciación

### 🔴 Mensaje de Error
```
Class "Domain\Product\Entities\Item" not found
```
(Mismo error pero diferente causa raíz)

### 🤔 ¿Qué pasó?
La entidad `Item` tenía un constructor **privado**:

```php
final class Item
{
    private function __construct(  // ❌ PRIVADO
        private readonly int $id,
        private string $name,
        // ...
    ) {}
}
```

Esto impedía hacer `new Item(...)` desde el repositorio.

### 🎯 Causa raíz
Los constructores privados se usan típicamente en:
- **Patrón Singleton**: Para controlar que solo haya una instancia
- **Factory Methods**: Cuando quieres forzar la creación mediante métodos estáticos

En este caso, no necesitábamos ninguno de esos patrones.

### ✅ Solución
Cambiar a constructor público:

```php
final class Item
{
    public function __construct(  // ✅ PÚBLICO
        private readonly int $id,
        private string $name,
        // ...
    ) {}
}
```

### 📝 Lecciones aprendidas

> [!TIP]
> **¿Cuándo usar constructor privado?**
> - ✅ Cuando implementas **Singleton**
> - ✅ Cuando forzas creación por **Factory Method**
> - ❌ En entidades de dominio simples que se instancian directamente

**Ejemplo válido de constructor privado:**

```php
final class Item
{
    private function __construct(
        private readonly int $id,
        private string $name,
    ) {}
    
    // Factory method - forma controlada de crear instancias
    public static function create(int $id, string $name): self
    {
        // Aquí puedes agregar validaciones
        if (empty($name)) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        
        return new self($id, $name);
    }
}

// Uso:
$item = Item::create(1, 'Product Name'); // ✅
$item = new Item(1, ''); // ❌ No se puede, constructor es privado
```

---

## ❌ Error #4: Conversión incorrecta de tipos (Decimal → Float)

### 🔴 Mensaje de Error (Warning de IDE)
```
Argument '4' passed to __construct() is expected to be of type float, 
decimal|null given
```

### 🤔 ¿Qué pasó?
En el modelo Eloquent, los campos están casteados como `decimal`:

```php
// app/Models/Item.php
protected $casts = [
    'price' => 'decimal:2',      // Retorna string "99.99"
    'cost_price' => 'decimal:2', // Retorna string "50.00"
];
```

Pero la entidad del dominio espera `float`:

```php
// Domain/Product/Entities/Item.php
public function __construct(
    private float $price,        // Espera float 99.99
    private ?float $cost_price,  // Espera float o null
) {}
```

### 🎯 Causa raíz
**Type mismatch** entre la capa de Persistencia (Infrastructure) y la capa de Dominio.

### ✅ Solución
Convertir explícitamente en el repositorio:

```php
private function toDomain(EloquentProductModel $model): Item
{
    return new Item(
        id: $model->id,
        name: $model->name,
        description: $model->description,
        price: (float) $model->price,  // ✅ Conversión explícita
        cost_price: $model->cost_price ? (float) $model->cost_price : null,  // ✅ Con null check
        is_active: $model->is_active,
    );
}
```

### 📝 Lecciones aprendidas

> [!IMPORTANT]
> El **repositorio** es el responsable de la conversión entre el modelo de BD (Eloquent) y la entidad del dominio.

**Tipos comunes de Eloquent vs PHP:**

| Eloquent Cast | Tipo PHP retornado | Conversión necesaria |
|---------------|-------------------|----------------------|
| `'decimal:2'` | `string` | `(float) $value` |
| `'boolean'` | `bool` | ✅ No necesita |
| `'integer'` | `int` | ✅ No necesita |
| `'datetime'` | `Carbon` (object) | Depende del dominio |
| `'array'` | `array` | ✅ No necesita |
| `'json'` | `array` | ✅ No necesita |

**Checklist al crear un repositorio:**
- [ ] ¿Verifiqué los `$casts` en el modelo Eloquent?
- [ ] ¿Las conversiones de tipo están explícitas en `toDomain()`?
- [ ] ¿Manejé correctamente los valores `null`?

---

## ❌ Error #5: Propiedades privadas no serializables a JSON

### 🔴 Resultado visible
```json
[{},{},{},{},{},{}]  // Objetos vacíos
```

### 🤔 ¿Qué pasó?
El controlador devolvía directamente las entidades del dominio:

```php
public function index(): JsonResponse
{
    $products = $this->productCrudUseCase->index(); // Array de Item (entidades)
    return Response::json($products);  // ❌ Items con propiedades privadas
}
```

Pero la entidad `Item` tenía propiedades **privadas**:

```php
final class Item
{
    public function __construct(
        private readonly int $id,      // ← privadas
        private string $name,           // ← privadas
        private ?string $description,   // ← privadas
        // ...
    ) {}
}
```

JSON no puede acceder a propiedades privadas, por eso devolvía `{}`.

### 🎯 Causa raíz
**Las propiedades privadas de PHP no son accesibles para la serialización JSON por defecto.**

### ✅ Solución
Implementar `JsonSerializable` en la entidad:

```php
use JsonSerializable;

final class Item implements JsonSerializable
{
    // ... constructor y getters ...
    
    /**
     * Define cómo se serializa la entidad a JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'is_active' => $this->is_active,
        ];
    }
}
```

### 📝 Lecciones aprendidas

> [!CAUTION]
> En **Clean Architecture**, las entidades del dominio NO deberían conocer detalles de presentación (como JSON).

**Dos enfoques válidos:**

### Opción 1: `JsonSerializable` (Pragmático) ✅
**Pros:**
- Simple y rápido
- Funciona directamente

**Contras:**
- Acopla el dominio con la presentación
- Mezcla responsabilidades

```php
// Dominio
final class Item implements JsonSerializable
{
    public function jsonSerialize(): array { /*...*/ }
}

// Controlador
return Response::json($products); // Directo
```

### Opción 2: Resource/DTO (Más "puro" en Clean Architecture) ✅
**Pros:**
- Separa responsabilidades
- Dominio puro, sin conocer JSON
- Más control sobre la respuesta

**Contras:**
- Más código (boilerplate)
- Más archivos

```php
// Presentation/API/Resources/ProductResource.php
final class ProductResource
{
    public static function collection(array $items): array
    {
        return array_map(
            fn(Item $item) => self::toArray($item),
            $items
        );
    }
    
    public static function toArray(Item $item): array
    {
        return [
            'id' => $item->id(),
            'name' => $item->name(),
            'price' => $item->price(),
            // Control total sobre la respuesta
        ];
    }
}

// Controlador
public function index(): JsonResponse
{
    $products = $this->productCrudUseCase->index();
    return Response::json(ProductResource::collection($products));
}
```

**¿Cuál usar?**
- Proyectos pequeños/medianos: **Opción 1** (JsonSerializable)
- Proyectos grandes/equipos: **Opción 2** (Resources)

---

## 🎓 Resumen de Errores y Prevención

### 📋 Matriz de Errores

| # | Error | Código | Ubicación | Prevención |
|---|-------|--------|-----------|------------|
| 1 | Interface no registrada | Service Container | `bootstrap/providers.php` | Crear Service Provider por cada módulo |
| 2 | Namespace incorrecto | PSR-4 | Entidad del dominio | Verificar ruta = namespace |
| 3 | Constructor privado | Accesibilidad | Entidad del dominio | Usar `public` si no necesitas factory |
| 4 | Type mismatch | Conversión de tipos | Repositorio | Cast explícito en `toDomain()` |
| 5 | Serialización JSON | Propiedades privadas | Entidad del dominio | Implementar `JsonSerializable` |

---

## 🚀 Checklist para evitar estos errores en futuros módulos

Al crear un nuevo módulo (ej: Orders, Users, Payments), seguí esta lista:

### 1️⃣ Capa de Dominio (Domain)
- [ ] Crear entidad en `Domain/[Modulo]/Entities/[Entidad].php`
- [ ] Verificar namespace: `namespace Domain\[Modulo]\Entities;`
- [ ] Constructor **público** (a menos que necesites factory)
- [ ] Implementar `JsonSerializable` si se va a devolver en API
- [ ] Agregar getters para todas las propiedades

### 2️⃣ Capa de Aplicación (Application)
- [ ] Crear interfaz del repositorio: `Application/[Modulo]/Contracts/[Modulo]RepositoryInterface.php`
- [ ] Crear use case: `Application/[Modulo]/UseCases/[Accion]/[Accion]UseCase.php`

### 3️⃣ Capa de Infraestructura (Infrastructure)
- [ ] Crear repositorio: `Infrastructure/Persistence/Eloquent/Repositories/Eloquent[Modulo]Repository.php`
- [ ] Implementar interfaz del repositorio
- [ ] Agregar método `toDomain()` con conversiones de tipo explícitas
- [ ] Verificar `$casts` del modelo Eloquent
- [ ] ✨ **Crear Service Provider**: `Infrastructure/Laravel/Providers/[Modulo]ServiceProvider.php`
- [ ] ✨ **Registrar binding** en el Service Provider
- [ ] ✨ **Agregar Service Provider a `bootstrap/providers.php`**

### 4️⃣ Capa de Presentación (Presentation)
- [ ] Crear controlador
- [ ] Opcionalmente crear Resource/DTO

### 5️⃣ Testing y Validación
- [ ] Ejecutar `composer dump-autoload`
- [ ] Ejecutar `php artisan optimize:clear`
- [ ] Probar el endpoint
- [ ] Verificar que el JSON tenga datos (no objetos vacíos `{}`)

---

## 🔧 Comandos útiles después de cambios

```bash
# Limpiar todos los caches
php artisan optimize:clear

# O individualmente:
php artisan config:clear    # Limpia cache de configuración
php artisan cache:clear     # Limpia cache de aplicación
php artisan route:clear     # Limpia cache de rutas
php artisan view:clear      # Limpia cache de vistas

# Actualizar autoload de Composer (después de crear nuevas clases)
composer dump-autoload
```

---

## 📚 Recursos adicionales

- [PSR-4: Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [Laravel Service Container](https://laravel.com/docs/container)
- [Clean Architecture by Robert C. Martin](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- Referencia en este proyecto: `CLEAN_ARCHITECTURE_GUIDE.md`

---

> [!NOTE]
> Este documento fue creado basándose en errores reales encontrados durante el desarrollo. Mantenerlo actualizado con nuevos errores que encuentres.

**Fecha de creación:** 2025-12-10  
**Última actualización:** 2025-12-10
