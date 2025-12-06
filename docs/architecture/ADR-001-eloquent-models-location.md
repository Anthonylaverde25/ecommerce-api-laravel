# Decisión Arquitectónica: Ubicación de Modelos Eloquent

**Fecha**: 2025-12-03  
**Estado**: ✅ Aceptado

---

## Contexto

Al implementar Clean Architecture en Laravel, surge la pregunta: **¿Dónde deben ubicarse los Modelos de Eloquent?**

Teóricamente, los modelos de Eloquent son **detalles de infraestructura** (persistencia), por lo que "deberían" estar en:
```
app/Infrastructure/Persistence/Eloquent/Models/
```

Sin embargo, Laravel y su ecosistema esperan encontrarlos en:
```
app/Models/
```

---

## Problema

Mantener modelos en `app/Infrastructure/.../Models/` causa problemas:

1. **Herramientas de Laravel** (tinker, factories, seeders) esperan `App\Models\`
2. **Paquetes de terceros** asumen la ubicación convencional
3. **IDEs y linters** están configurados para Laravel estándar
4. **Deployment y DevOps** scripts buscan en ubicaciones convencionales
5. **Nuevos desarrolladores** esperan la estructura Laravel estándar
6. **Documentación oficial** de Laravel usa `app/Models/`

---

## Decisión

**Usaremos `app/Models/` para todos los modelos de Eloquent.**

Sin embargo, mantenemos la filosofía de Clean Architecture:

### ✅ Lo que SÍ hacemos:

1. **Separamos conceptos**: 
   - `App\Models\Category` = Eloquent Model (persistencia)
   - `Domain\Category\Entities\Category` = Domain Entity (lógica de negocio)

2. **Documentamos claramente** en cada modelo:
   ```php
   /**
    * Category Model - Infrastructure Layer (Eloquent ORM)
    * 
    * NOTA: Este archivo está en app/Models/ por convención de Laravel.
    * Conceptualmente pertenece a Infrastructure Layer.
    */
   ```

3. **Repositorios en Infrastructure** siguen en:
   ```
   app/Infrastructure/Persistence/Eloquent/Repositories/
   ```

4. **Dependency Injection**: Usamos interfaces y repositorios
   ```php
   // Application Layer define la interfaz
   interface CategoryRepositoryInterface { }
   
   // Infrastructure implementa usando Eloquent
   class EloquentCategoryRepository implements CategoryRepositoryInterface
   {
       public function __construct(private Category $model) {} // App\Models\Category
   }
   ```

---

## Consecuencias

### ✅ Positivas:

- Máxima compatibilidad con Laravel y su ecosistema
- Herramientas funcionan out-of-the-box
- Fácil onboarding de nuevos desarrolladores
- Scripts de deployment funcionan sin configuración especial
- Menor fricción con convenciones de la comunidad

### ⚠️ Negativas:

- Desviación de Clean Architecture "puro"
- Necesidad de documentar claramente la arquitectura
- Riesgo de confundir Eloquent Models con Domain Entities

---

## Estructura Final

```
app/
├── Models/                          # Eloquent Models (Infrastructure Layer conceptualmente)
│   ├── User.php
│   ├── Category.php
│   └── Product.php
│
├── Domain/                          # Domain Layer - Lógica de Negocio
│   ├── Category/
│   │   ├── Entities/
│   │   │   └── Category.php        # ← Domain Entity (diferente de Model!)
│   │   └── ValueObjects/
│   │       └── CategoryName.php
│   └── Shared/
│       └── ValueObjects/
│           └── Uuid.php
│
├── Application/                     # Application Layer - Casos de Uso
│   └── Category/
│       ├── Contracts/
│       │   └── CategoryRepositoryInterface.php
│       └── UseCases/
│           └── CreateCategory/
│               └── CreateCategoryUseCase.php
│
├── Infrastructure/                  # Infrastructure Layer - Implementaciones
│   ├── Persistence/
│   │   └── Eloquent/
│   │       └── Repositories/       # ← Repositorios usan Models de app/Models/
│   │           └── EloquentCategoryRepository.php
│   └── Laravel/
│       └── Providers/
│           └── CategoryServiceProvider.php
│
└── Presentation/                    # Presentation Layer - API/HTTP
    └── API/
        └── Category/
            └── Controllers/
                └── CategoryController.php
```

---

## Reglas a Seguir

### 🚫 NUNCA:
- Usar lógica de negocio en Models de `app/Models/`
- Acceder directamente a Models desde Controllers
- Poner validaciones de dominio en Models

### ✅ SIEMPRE:
- Lógica de negocio en Domain Entities
- Acceder a datos vía Repository Interfaces
- Convertir entre Model (Eloquent) ↔ Entity (Domain) en Repositories

---

## Ejemplo de Conversión en Repository

```php
// Infrastructure/Persistence/Eloquent/Repositories/EloquentCategoryRepository.php

use App\Models\Category as CategoryModel;  // Eloquent Model
use Domain\Category\Entities\Category;     // Domain Entity

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function save(Category $domainEntity): void
    {
        // Convertir de Domain Entity → Eloquent Model
        CategoryModel::updateOrCreate(
            ['id' => $domainEntity->id()->value()],
            [
                'name' => $domainEntity->name()->value(),
                'description' => $domainEntity->description(),
                // ...
            ]
        );
    }

    public function findById(Uuid $id): ?Category
    {
        $model = CategoryModel::find($id->value());
        
        // Convertir de Eloquent Model → Domain Entity
        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(CategoryModel $model): Category
    {
        return Category::fromPrimitives(
            id: $model->id,
            name: $model->name,
            // ...
        );
    }
}
```

---

## Referencias

- [Laravel Beyond CRUD](https://laravel-beyond-crud.com/)
- [Pragmatic Clean Architecture](https://dev.to/bdelespierre/pragmatic-clean-architecture-47d8)

---

## Notas

Esta es una decisión **pragmática** que balancea:
- ✅ Teoría de Clean Architecture
- ✅ Realidad del ecosistema Laravel
- ✅ Productividad del equipo
- ✅ Compatibilidad con deployment

**Clean Architecture no es dogma**. El objetivo es código mantenible, testeable y escalable.
Si seguir la ubicación convencional de Laravel nos ayuda en deployment y productividad sin sacrificar estos principios, es la decisión correcta.
