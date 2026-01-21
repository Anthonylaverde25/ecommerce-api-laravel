# 🎯 Plan de Implementación: Arquitectura Multi-Tenant (Database per Tenant)

## 📋 Visión General

Este documento detalla el plan completo para transformar el ERP actual en un **SaaS multi-tenant** similar a Holded, donde:
- Un usuario puede tener acceso a múltiples empresas
- Cada empresa (tenant) tiene su **propia base de datos independiente**
- Aislamiento total de datos entre empresas
- Escalabilidad profesional desde el inicio

---

## 🏗️ Arquitectura Propuesta

### Estructura de Bases de Datos

```
┌──────────────────────────────────────────────────────┐
│  DB_CENTRAL (mysql)                                  │
│  Gestión de usuarios y empresas                      │
├──────────────────────────────────────────────────────┤
│  ✓ users                                             │
│  ✓ companies (tenants)                               │
│  ✓ company_user (pivot - relación muchos a muchos)   │
└──────────────────────────────────────────────────────┘

┌──────────────────────┐  ┌──────────────────────┐  ┌──────────────────────┐
│  tenant_1            │  │  tenant_2            │  │  tenant_N            │
│  (Empresa A)         │  │  (Empresa B)         │  │  (Empresa N)         │
├──────────────────────┤  ├──────────────────────┤  ├──────────────────────┤
│  invoices            │  │  invoices            │  │  invoices            │
│  customers           │  │  customers           │  │  customers           │
│  products            │  │  products            │  │  products            │
│  bank_accounts       │  │  bank_accounts       │  │  bank_accounts       │
│  departments         │  │  departments         │  │  departments         │
│  users (local)       │  │  users (local)       │  │  users (local)       │
│  roles               │  │  roles               │  │  roles               │
│  (TODO negocio)      │  │  (TODO negocio)      │  │  (TODO negocio)      │
└──────────────────────┘  └──────────────────────┘  └──────────────────────┘
```

### Flujo de Autenticación

```
1. Usuario ingresa credenciales
   ↓
2. Login contra DB_CENTRAL
   ↓
3. Sistema devuelve lista de empresas disponibles para ese usuario
   ↓
4. Usuario selecciona empresa (tenant)
   ↓
5. Sistema conecta dinámicamente a la DB del tenant
   ↓
6. TODAS las operaciones se ejecutan en esa DB
```

---

## 📦 Fase 1: Preparación de la Estructura

### 1.1 Reorganizar Migraciones

**Crear nueva estructura de carpetas:**

```
database/migrations/
├── central/          ← Migraciones para DB central
│   ├── 2024_01_01_000000_create_users_table.php
│   ├── 2024_01_01_000001_create_companies_table.php
│   └── 2024_01_01_000002_create_company_user_table.php
│
└── tenant/           ← Migraciones para cada tenant
    ├── 2024_01_01_000000_create_invoices_table.php
    ├── 2024_01_01_000001_create_customers_table.php
    ├── 2024_01_01_000002_create_products_table.php
    ├── 2024_01_01_000003_create_bank_accounts_table.php
    ├── 2024_01_01_000004_create_departments_table.php
    └── ... (todas las tablas de negocio)
```

**IMPORTANTE:** Las tablas tenant **NO deben tener** `company_id` (cada DB es una empresa).

### 1.2 Migración: companies (DB Central)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            
            // Configuración de conexión a DB
            $table->string('db_name')->unique();
            $table->string('db_host')->default('localhost');
            $table->string('db_username');
            $table->string('db_password');
            $table->integer('db_port')->default(3306);
            
            // Plan y facturación
            $table->enum('plan', ['basic', 'professional', 'enterprise'])->default('basic');
            $table->boolean('active')->default(true);
            $table->timestamp('trial_ends_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
```

### 1.3 Migración: company_user (DB Central)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            
            // Rol del usuario EN ESA empresa específica
            $table->enum('role', ['owner', 'admin', 'employee'])->default('employee');
            
            $table->timestamps();
            
            // Un usuario no puede estar duplicado en la misma empresa
            $table->unique(['user_id', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user');
    }
};
```

---

## 📦 Fase 2: Implementación del Sistema Tenant

### 2.1 Modelo Company

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql'; // Siempre en DB central

    protected $fillable = [
        'name',
        'slug',
        'db_name',
        'db_host',
        'db_username',
        'db_password',
        'db_port',
        'plan',
        'active',
        'trial_ends_at',
    ];

    protected $hidden = [
        'db_password',
    ];

    protected $casts = [
        'active' => 'boolean',
        'trial_ends_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }
}
```

### 2.2 Actualizar Modelo User

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    protected $connection = 'mysql'; // Siempre en DB central

    // ... tus campos existentes ...

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function isOwnerOf(Company $company): bool
    {
        return $this->companies()
            ->wherePivot('company_id', $company->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    public function isAdminOf(Company $company): bool
    {
        return $this->companies()
            ->wherePivot('company_id', $company->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }
}
```

### 2.3 TenantManager (Servicio Core)

```php
<?php

namespace App\Services\Tenancy;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class TenantManager
{
    protected ?Company $currentTenant = null;
    protected ?string $connectionName = null;

    /**
     * Establece el tenant actual y configura la conexión dinámica
     */
    public function setTenant(Company $company): void
    {
        $this->currentTenant = $company;
        
        // Nombre único para la conexión
        $this->connectionName = "tenant_{$company->id}";
        
        // Configurar conexión dinámica
        Config::set("database.connections.{$this->connectionName}", [
            'driver' => 'mysql',
            'host' => $company->db_host,
            'port' => $company->db_port,
            'database' => $company->db_name,
            'username' => $company->db_username,
            'password' => $company->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        
        // Purgar conexión anterior si existe
        DB::purge($this->connectionName);
        
        // Registrar en el contenedor
        app()->instance('tenant_connection', $this->connectionName);
        app()->instance('current_tenant', $company);
    }

    /**
     * Obtiene el tenant actual
     */
    public function getTenant(): ?Company
    {
        return $this->currentTenant ?? app('current_tenant');
    }

    /**
     * Obtiene el nombre de la conexión actual
     */
    public function getConnectionName(): ?string
    {
        return $this->connectionName ?? app('tenant_connection');
    }

    /**
     * Verifica si hay un tenant establecido
     */
    public function hasTenant(): bool
    {
        return $this->currentTenant !== null || app()->has('current_tenant');
    }

    /**
     * Limpia el tenant actual
     */
    public function forgetTenant(): void
    {
        if ($this->connectionName) {
            DB::purge($this->connectionName);
        }
        
        $this->currentTenant = null;
        $this->connectionName = null;
        
        app()->forgetInstance('current_tenant');
        app()->forgetInstance('tenant_connection');
    }
}
```

### 2.4 TenantModel (Modelo Base)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo base para todas las entidades que pertenecen a un tenant
 * 
 * Automáticamente usa la conexión del tenant actual
 */
abstract class TenantModel extends Model
{
    /**
     * Obtiene el nombre de la conexión
     */
    public function getConnectionName()
    {
        // Si hay un tenant activo, usa su conexión
        if (app()->has('tenant_connection')) {
            return app('tenant_connection');
        }
        
        // Fallback a la conexión por defecto
        return $this->connection ?? config('database.default');
    }
}
```

### 2.5 Middleware SetTenant

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Services\Tenancy\TenantManager;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    public function __construct(
        protected TenantManager $tenantManager
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Obtener company_id desde el token/sesión
        $companyId = $this->getCompanyIdFromRequest($request);

        if (!$companyId) {
            return response()->json([
                'error' => 'No company selected. Please select a company first.'
            ], 400);
        }

        // Buscar empresa en DB central
        $company = Company::on('mysql')
            ->where('id', $companyId)
            ->where('active', true)
            ->first();

        if (!$company) {
            return response()->json([
                'error' => 'Company not found or inactive.'
            ], 404);
        }

        // Verificar que el usuario tiene acceso
        if (!$request->user()->companies->contains($company->id)) {
            return response()->json([
                'error' => 'You do not have access to this company.'
            ], 403);
        }

        // Establecer tenant
        $this->tenantManager->setTenant($company);

        return $next($request);
    }

    protected function getCompanyIdFromRequest(Request $request): ?int
    {
        // Prioridad 1: Header X-Company-ID
        if ($request->hasHeader('X-Company-ID')) {
            return (int) $request->header('X-Company-ID');
        }

        // Prioridad 2: Query parameter
        if ($request->has('company_id')) {
            return (int) $request->query('company_id');
        }

        // Prioridad 3: Desde el token JWT (si usas JWT)
        // return $request->user()->token()->company_id ?? null;

        // Prioridad 4: Desde la sesión
        return session('current_company_id');
    }
}
```

---

## 📦 Fase 3: Migración de Modelos Existentes

### 3.1 Actualizar Modelos a TenantModel

**Antes:**
```php
class Invoice extends Model
{
    protected $fillable = ['company_id', 'number', 'total'];
}
```

**Después:**
```php
use App\Models\TenantModel;

class Invoice extends TenantModel  // ← Cambiar de Model a TenantModel
{
    // ¡ELIMINAR company_id de fillable y de la tabla!
    protected $fillable = ['number', 'total', 'customer_id'];
}
```

**Lista de modelos a migrar:**
- [ ] Invoice
- [ ] Customer
- [ ] Product
- [ ] BankAccount
- [ ] Department
- [ ] User (entidad local del tenant, no el User global)
- [ ] Role (entidad local del tenant)
- [ ] Todos los demás modelos de negocio

### 3.2 Actualizar Migraciones

**Remover `company_id` de todas las tablas tenant:**

```php
// ANTES
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained(); // ← ELIMINAR
    $table->string('number');
    $table->decimal('total', 10, 2);
    $table->timestamps();
});

// DESPUÉS
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    // company_id ya no existe
    $table->string('number');
    $table->decimal('total', 10, 2);
    $table->timestamps();
});
```

---

## 📦 Fase 4: Sistema de Onboarding

### 4.1 Servicio de Creación de Empresas

```php
<?php

namespace App\Services\Tenancy;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CompanyCreationService
{
    public function create(User $user, array $data): Company
    {
        return DB::transaction(function () use ($user, $data) {
            
            // 1. Generar nombre único para la DB
            $dbName = 'tenant_' . Str::lower(Str::slug($data['name'])) . '_' . uniqid();
            
            // 2. Crear registro en DB central
            $company = Company::on('mysql')->create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'db_name' => $dbName,
                'db_host' => env('TENANT_DB_HOST', 'localhost'),
                'db_username' => env('TENANT_DB_USERNAME', env('DB_USERNAME')),
                'db_password' => env('TENANT_DB_PASSWORD', env('DB_PASSWORD')),
                'db_port' => env('TENANT_DB_PORT', 3306),
                'plan' => $data['plan'] ?? 'basic',
                'active' => true,
                'trial_ends_at' => now()->addDays(30), // 30 días de prueba
            ]);
            
            // 3. Crear la base de datos física
            DB::statement("CREATE DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // 4. Conectar al tenant
            app(TenantManager::class)->setTenant($company);
            
            // 5. Ejecutar migraciones del tenant
            Artisan::call('migrate', [
                '--database' => "tenant_{$company->id}",
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
            
            // 6. Seeders iniciales (opcional)
            // Artisan::call('db:seed', ['--class' => 'TenantSeeder']);
            
            // 7. Asociar usuario como owner
            $company->users()->attach($user->id, [
                'role' => 'owner'
            ]);
            
            return $company;
        });
    }

    public function delete(Company $company): void
    {
        DB::transaction(function () use ($company) {
            // 1. Eliminar la base de datos física
            DB::statement("DROP DATABASE IF EXISTS `{$company->db_name}`");
            
            // 2. Eliminar registro de la empresa
            $company->delete();
        });
    }
}
```

### 4.2 Controlador de Registro

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Tenancy\CompanyCreationService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyCreationService $companyService
    ) {}

    /**
     * Crear nueva empresa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plan' => 'required|in:basic,professional,enterprise',
        ]);

        $company = $this->companyService->create(
            $request->user(),
            $validated
        );

        return response()->json([
            'message' => 'Company created successfully',
            'company' => $company,
        ], 201);
    }

    /**
     * Listar empresas del usuario autenticado
     */
    public function index(Request $request)
    {
        $companies = $request->user()
            ->companies()
            ->with('pivot')
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
                    'plan' => $company->plan,
                    'role' => $company->pivot->role,
                    'active' => $company->active,
                ];
            });

        return response()->json($companies);
    }

    /**
     * Seleccionar empresa activa
     */
    public function select(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $company = $request->user()
            ->companies()
            ->where('companies.id', $validated['company_id'])
            ->first();

        if (!$company) {
            return response()->json([
                'error' => 'You do not have access to this company.'
            ], 403);
        }

        // Guardar en sesión
        session(['current_company_id' => $company->id]);

        return response()->json([
            'message' => 'Company selected successfully',
            'company' => $company,
        ]);
    }
}
```

---

## 📦 Fase 5: Configuración y Rutas

### 5.1 Variables de Entorno

```env
# .env

# DB Central (usuarios y empresas)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_central
DB_USERNAME=root
DB_PASSWORD=

# Configuración para Tenants
TENANT_DB_HOST=127.0.0.1
TENANT_DB_PORT=3306
TENANT_DB_USERNAME=root
TENANT_DB_PASSWORD=
```

### 5.2 Rutas API

```php
// routes/api.php

use App\Http\Controllers\Api\CompanyController;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas autenticadas (sin tenant)
Route::middleware('auth:sanctum')->group(function () {
    
    // Gestión de empresas
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::post('/companies/select', [CompanyController::class, 'select']);
});

// Rutas con tenant activo
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    
    // Todas las rutas de negocio
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('bank-accounts', BankAccountController::class);
    Route::apiResource('departments', DepartmentController::class);
    // ... todas las demás rutas
});
```

### 5.3 Registrar Middleware

```php
// app/Http/Kernel.php

protected $middlewareAliases = [
    // ... otros middlewares
    'tenant' => \App\Http\Middleware\SetTenant::class,
];
```

### 5.4 Registrar TenantManager

```php
// app/Providers/AppServiceProvider.php

use App\Services\Tenancy\TenantManager;

public function register(): void
{
    $this->app->singleton(TenantManager::class);
}
```

---

## 📦 Fase 6: Comandos Artisan útiles

### 6.1 Comando para migrar todos los tenants

```php
<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Services\Tenancy\TenantManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantMigrate extends Command
{
    protected $signature = 'tenants:migrate {--fresh} {--seed}';
    protected $description = 'Run migrations for all tenants';

    public function handle(TenantManager $tenantManager)
    {
        $companies = Company::on('mysql')->where('active', true)->get();

        $this->info("Found {$companies->count()} active tenants");

        foreach ($companies as $company) {
            $this->info("Migrating: {$company->name} ({$company->db_name})");

            $tenantManager->setTenant($company);

            $params = [
                '--database' => "tenant_{$company->id}",
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ];

            if ($this->option('fresh')) {
                Artisan::call('migrate:fresh', $params);
            } else {
                Artisan::call('migrate', $params);
            }

            if ($this->option('seed')) {
                Artisan::call('db:seed', [
                    '--database' => "tenant_{$company->id}",
                    '--class' => 'TenantSeeder',
                ]);
            }

            $this->info("✓ Completed: {$company->name}");
        }

        $this->info('All tenants migrated successfully!');
    }
}
```

---

## ✅ Checklist de Implementación

### Fase 1: Preparación
- [ ] Crear carpetas `database/migrations/central` y `database/migrations/tenant`
- [ ] Mover migraciones existentes a las carpetas correspondientes
- [ ] Crear migración `companies` en central
- [ ] Crear migración `company_user` en central
- [ ] Eliminar `company_id` de todas las migraciones tenant

### Fase 2: Modelos y Servicios Core
- [ ] Crear modelo `Company`
- [ ] Actualizar modelo `User` con relación companies
- [ ] Crear `TenantManager` service
- [ ] Crear `TenantModel` base class
- [ ] Crear middleware `SetTenant`

### Fase 3: Migración de Modelos
- [ ] Actualizar `Invoice` a extends `TenantModel`
- [ ] Actualizar `Customer` a extends `TenantModel`
- [ ] Actualizar `Product` a extends `TenantModel`
- [ ] Actualizar `BankAccount` a extends `TenantModel`
- [ ] Actualizar `Department` a extends `TenantModel`
- [ ] Actualizar todos los demás modelos de negocio

### Fase 4: Onboarding
- [ ] Crear `CompanyCreationService`
- [ ] Crear `CompanyController`
- [ ] Crear rutas de gestión de empresas
- [ ] Crear comando `tenants:migrate`

### Fase 5: Frontend (React)
- [ ] Pantalla de selección de empresa
- [ ] Header X-Company-ID en requests
- [ ] Context/Store para empresa actual
- [ ] Formulario de creación de empresa

### Fase 6: Pruebas
- [ ] Crear empresa de prueba
- [ ] Verificar aislamiento de datos
- [ ] Probar cambio entre empresas
- [ ] Verificar permisos y roles

---

## 🎯 Consideraciones Importantes

### Seguridad
1. **Nunca exponer credenciales de DB** - El modelo Company debe ocultar `db_password`
2. **Validar acceso** - Siempre verificar que el usuario tiene acceso a la empresa
3. **SQL Injection** - Sanitizar nombres de DB si permites que usuarios los elijan
4. **Rate Limiting** - Limitar creación de empresas por usuario

### Performance
1. **Pooling de conexiones** - Laravel maneja esto, pero monitorear con muchos tenants
2. **Índices** - Asegurar índices apropiados en cada tenant DB
3. **Caché** - Implementar caché por tenant

### Escalabilidad
1. **Múltiples servidores DB** - El campo `db_host` permite distribuir tenants
2. **Sharding** - Agrupar tenants pequeños, separar los grandes
3. **Backups** - Script para backup individual de cada tenant

### Monitoreo
1. **Logs por tenant** - Incluir `company_id` en todos los logs
2. **Métricas** - Uso de recursos por tenant
3. **Alertas** - Detectar tenants con problemas

---

## 📚 Recursos y Referencias

### Paquetes Recomendados
- [Spatie Laravel Multitenancy](https://github.com/spatie/laravel-multitenancy) - Alternativa robusta
- [Tenancy for Laravel](https://tenancyforlaravel.com/) - Framework completo
- [Laravel Cashier](https://laravel.com/docs/billing) - Suscripciones

### Lecturas
- [Multi-Tenancy Database Approaches](https://docs.microsoft.com/en-us/azure/architecture/guide/multitenant/approaches/overview)
- [AWS Multi-Tenant SaaS](https://aws.amazon.com/partners/saas-factory/)

---

## 🚀 Próximos Pasos

1. **Revisar esta planificación** y validar que cumple con los requisitos
2. **Configurar entorno de desarrollo** con múltiples DBs de prueba
3. **Implementar Fase 1** (estructura de migraciones)
4. **Implementar Fase 2** (servicios core)
5. **Migrar gradualmente** los modelos existentes
6. **Probar exhaustivamente** con datos de prueba
7. **Implementar frontend** de selección de empresa
8. **Deploy a staging** y pruebas end-to-end

---

## 📞 Soporte

Para continuar con la implementación, retomar desde la **Fase 1** del checklist.

**¡Éxito con tu SaaS! 🎯**
