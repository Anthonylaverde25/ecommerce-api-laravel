# DTO Pattern - Ejemplos de Uso

Este archivo contiene ejemplos prácticos de cómo usar el patrón DTO implementado en la entidad Tax.

## 📋 Flujo completo: Controller → Use Case → Entity → Repository

### 1️⃣ En el Controller (Application Layer)

```php
<?php

namespace App\Http\Controllers\Api;

use App\Domain\Tax\DTOs\CreateTaxData;
use App\Application\UseCases\Tax\CreateTaxUseCase;
use App\Http\Requests\Tax\CreateTaxRequest;

class TaxController extends Controller
{
    public function __construct(
        private CreateTaxUseCase $createTaxUseCase
    ) {}

    public function store(CreateTaxRequest $request)
    {
        try {
            // Opción 1: Crear DTO desde array validado
            $dto = CreateTaxData::fromArray($request->validated());
            
            // Opción 2: Crear DTO directamente (si prefieres control manual)
            $dto = new CreateTaxData(
                tax_code: $request->tax_code,
                name: $request->name,
                tax_type_id: $request->tax_type_id,
                percentage: $request->percentage
            );
            
            // Ejecutar caso de uso
            $tax = $this->createTaxUseCase->execute($dto);
            
            return response()->json([
                'message' => 'Tax created successfully',
                'data' => [
                    'id' => $tax->getId(),
                    'tax_code' => $tax->getTaxCode(),
                    'name' => $tax->getName(),
                    'tax_type_id' => $tax->getTaxTypeId(),
                    'percentage' => $tax->getPercentage(),
                ]
            ], 201);
            
        } catch (\InvalidArgumentException $e) {
            // El DTO lanza excepciones si los datos no son válidos
            return response()->json([
                'message' => 'Validation error',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
```

---

### 2️⃣ En el Use Case (Application Layer)

```php
<?php

namespace App\Application\UseCases\Tax;

use App\Domain\Tax\DTOs\CreateTaxData;
use App\Domain\Tax\Entities\Tax;
use App\Domain\Tax\Repositories\TaxRepositoryInterface;

class CreateTaxUseCase
{
    public function __construct(
        private TaxRepositoryInterface $taxRepository
    ) {}

    public function execute(CreateTaxData $data): Tax
    {
        // Crear la entidad usando el DTO
        // El DTO ya viene validado, por lo que podemos confiar en los datos
        $tax = Tax::create($data);
        
        // Guardar en el repositorio
        return $this->taxRepository->save($tax);
    }
}
```

---

### 3️⃣ En el Repository (Infrastructure Layer)

```php
<?php

namespace App\Infrastructure\Persistence\Eloquent\Tax;

use App\Domain\Tax\Entities\Tax;
use App\Domain\Tax\Repositories\TaxRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\TaxModel;

class EloquentTaxRepository implements TaxRepositoryInterface
{
    public function save(Tax $tax): Tax
    {
        // Convertir la entidad de dominio a modelo Eloquent
        $model = new TaxModel();
        $model->tax_code = $tax->getTaxCode();
        $model->name = $tax->getName();
        $model->tax_type_id = $tax->getTaxTypeId();
        $model->percentage = $tax->getPercentage();
        $model->save();
        
        // Reconstruir la entidad con el ID de la BD
        return Tax::fromPrimitives(
            id: $model->id,
            tax_code: $model->tax_code,
            name: $model->name,
            tax_type_id: $model->tax_type_id,
            percentage: $model->percentage
        );
    }
    
    public function findById(int $id): ?Tax
    {
        $model = TaxModel::find($id);
        
        if (!$model) {
            return null;
        }
        
        // Reconstruir la entidad desde los datos de la BD
        return Tax::fromPrimitives(
            id: $model->id,
            tax_code: $model->tax_code,
            name: $model->name,
            tax_type_id: $model->tax_type_id,
            percentage: $model->percentage
        );
    }
}
```

---

## 🧪 Ejemplos de Validación Automática

El DTO valida automáticamente los datos al momento de crearse:

```php
// ✅ CORRECTO - Datos válidos
$dto = new CreateTaxData(
    tax_code: 1,
    name: 'IVA',
    tax_type_id: 1,
    percentage: 21.0
);

// ❌ ERROR - Nombre vacío
$dto = new CreateTaxData(
    tax_code: 1,
    name: '',  // Lanza: InvalidArgumentException: Tax name cannot be empty
    tax_type_id: 1,
    percentage: 21.0
);

// ❌ ERROR - Porcentaje fuera de rango
$dto = new CreateTaxData(
    tax_code: 1,
    name: 'IVA',
    tax_type_id: 1,
    percentage: 150.0  // Lanza: InvalidArgumentException: Tax percentage must be between 0 and 100
);

// ❌ ERROR - Tax type ID negativo
$dto = new CreateTaxData(
    tax_code: 1,
    name: 'IVA',
    tax_type_id: -1,  // Lanza: InvalidArgumentException: Tax type ID must be a positive integer
    percentage: 21.0
);
```

---

## 🔄 Comparación: Antes vs Después

### ❌ ANTES (Sin DTO - Propenso a errores)

```php
// En el controller - Sin validación centralizada
$tax = Tax::create(
    $request->name,           // ¿Qué pasa si está vacío?
    $request->percentage      // ¿Qué pasa si es negativo?
);
```

### ✅ DESPUÉS (Con DTO - Type-safe y validado)

```php
// En el controller - Validación automática
$dto = CreateTaxData::fromArray($request->validated());
$tax = Tax::create($dto);  // Garantizado que los datos son válidos
```

---

## 📊 Ventajas del Patrón DTO

| Aspecto | Sin DTO | Con DTO |
|---------|---------|---------|
| **Type Safety** | ❌ Arrays sin tipo | ✅ Propiedades tipadas |
| **Validación** | ❌ Dispersa en múltiples lugares | ✅ Centralizada en el DTO |
| **Inmutabilidad** | ❌ Datos pueden cambiar | ✅ `readonly` previene cambios |
| **Autodocumentación** | ❌ No sabes qué campos necesitas | ✅ Constructor muestra todo |
| **Testing** | ❌ Difícil crear datos de prueba | ✅ Fácil crear DTOs de test |
| **Refactoring** | ❌ Cambios rompen todo | ✅ PHP te avisa en compile-time |

---

## 🎯 Cuándo usar cada método

```php
// CREATE - Para crear nuevas entidades (antes de guardar en BD)
$tax = Tax::create($dto);

// FROM_PRIMITIVES - Para reconstruir desde BD (ya tiene ID)
$tax = Tax::fromPrimitives($id, $tax_code, $name, $tax_type_id, $percentage);

// SETTERS - Para actualizar propiedades (con validación)
$tax->setName('IVA Reducido');
$tax->setPercentage(10.5);
```

---

## 🧩 Estructura de archivos final

```
app/
└── Domain/
    └── Tax/
        ├── DTOs/
        │   └── CreateTaxData.php      ← DTO para crear Tax
        ├── Entities/
        │   └── Tax.php                ← Entidad de dominio
        └── Repositories/
            └── TaxRepositoryInterface.php
```

---

## 💡 Consejos

1. **Usa DTOs para entrada de datos** (create, update)
2. **Usa primitivos para salida de datos** (fromPrimitives en repositorios)
3. **Las validaciones van en el DTO**, no en los setters
4. **Los setters también validan** por si actualizas después de crear
5. **El constructor es privado** para forzar el uso de factory methods
