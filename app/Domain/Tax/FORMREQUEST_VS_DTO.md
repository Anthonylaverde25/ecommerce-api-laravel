# FormRequest vs DTO - Validación en capas

## 🎯 Resumen: Dos validaciones, dos propósitos

```
HTTP Request
    ↓
┌─────────────────────────────────────┐
│  FormRequest (Infrastructure)      │  ← Validación HTTP/Laravel
│  - Formato de datos                │
│  - Reglas de BD (exists, unique)   │
│  - Autorización                    │
└─────────────────────────────────────┘
    ↓
┌─────────────────────────────────────┐
│  DTO (Domain)                       │  ← Validación de negocio
│  - Reglas de negocio puras          │
│  - Independiente de Laravel         │
│  - Reutilizable                     │
└─────────────────────────────────────┘
    ↓
Entity
```

---

## 📝 Comparación detallada

| Aspecto | FormRequest | DTO |
|---------|-------------|-----|
| **Capa** | Infrastructure (HTTP) | Domain |
| **Framework** | Depende de Laravel | Independiente |
| **Valida** | Formato, tipos, BD | Reglas de negocio |
| **Cuándo** | Entrada HTTP | Creación del objeto |
| **Reutilizable** | Solo en HTTP | Desde cualquier lugar |

---

## ✅ CreateTaxRequest (FormRequest)

**Ubicación:** `app/Http/Requests/TaxRequests/CreateTaxRequest.php`

**Responsabilidades:**
```php
public function rules(): array
{
    return [
        'tax_code' => [
            'required',     // ← HTTP: campo debe venir
            'integer',      // ← HTTP: debe ser entero
            'unique:taxes,tax_code'  // ← BD: no debe existir
        ],
        'tax_type_id' => [
            'exists:tax_types,id'  // ← BD: debe existir
        ],
        'percentage' => [
            'numeric',      // ← HTTP: debe ser numérico
            'min:0',        // ← HTTP: validación básica
            'max:100'       // ← HTTP: validación básica
        ],
    ];
}
```

**Mensajes de error:** En español para el usuario final

---

## ✅ CreateTaxData (DTO)

**Ubicación:** `app/Domain/Tax/DTOs/CreateTaxData.php`

**Responsabilidades:**
```php
private function validate(): void
{
    // Regla de negocio: nombre debe tener contenido
    if (empty(trim($this->name))) {
        throw new \InvalidArgumentException('Tax name cannot be empty');
    }
    
    // Regla de negocio: porcentaje entre 0 y 100
    if ($this->percentage < 0 || $this->percentage > 100) {
        throw new \InvalidArgumentException('...');
    }
    
    // Regla de negocio: tax_type_id debe ser positivo
    if ($this->tax_type_id <= 0) {
        throw new \InvalidArgumentException('...');
    }
}
```

---

## 🔄 Flujo completo con ejemplos

### Ejemplo 1: Request HTTP válido

```php
POST /api/taxes
{
    "tax_code": 1,
    "name": "IVA",
    "tax_type_id": 2,
    "percentage": 21
}
```

**Paso 1:** FormRequest valida
```php
✅ tax_code es integer
✅ tax_code no existe en BD (unique)
✅ name es string de 2-255 chars
✅ tax_type_id existe en tax_types
✅ percentage es numeric entre 0-100
```

**Paso 2:** DTO valida
```php
✅ name no está vacío
✅ percentage entre 0-100
✅ tax_type_id es positivo
```

**Resultado:** ✅ Tax creado exitosamente

---

### Ejemplo 2: Falla en FormRequest

```php
POST /api/taxes
{
    "tax_code": "ABC",  // ← NO es integer
    "name": "IVA",
    "tax_type_id": 999,  // ← NO existe en BD
    "percentage": 21
}
```

**Paso 1:** FormRequest valida
```php
❌ tax_code NO es integer
❌ tax_type_id NO existe en tax_types
```

**Respuesta:**
```json
{
    "errores": {
        "tax_code": ["El código del impuesto debe ser un número entero."],
        "tax_type_id": ["El tipo de impuesto seleccionado no existe."]
    }
}
```

**Resultado:** ❌ La request se detiene antes del DTO

---

### Ejemplo 3: Falla en DTO (menos común)

Esto puede pasar si creas el DTO desde otra fuente (CLI, evento, test):

```php
// Desde un comando artisan (no HTTP)
$dto = new CreateTaxData(
    tax_code: 1,
    name: "",  // ← Vacío, pero pasó FormRequest porque no vino de HTTP
    tax_type_id: 1,
    percentage: 21
);
```

**DTO valida:**
```php
❌ name está vacío
```

**Resultado:** `InvalidArgumentException: Tax name cannot be empty`

---

## 🎓 ¿Por qué DOS validaciones?

### Razón 1: **Separación de preocupaciones**
- FormRequest = Infraestructura (¿viene bien el HTTP request?)
- DTO = Dominio (¿cumple las reglas de negocio?)

### Razón 2: **Independencia del framework**
```php
// DTO se puede usar SIN Laravel
$dto = new CreateTaxData(...);  // Funciona en cualquier contexto

// FormRequest REQUIERE Laravel
$request->validated();  // Solo en contexto HTTP
```

### Razón 3: **Reutilización**
```php
// Caso 1: Crear tax desde API
$dto = CreateTaxData::fromArray($request->validated());
$tax = Tax::create($dto);

// Caso 2: Crear tax desde comando CLI
$dto = new CreateTaxData(...);
$tax = Tax::create($dto);

// Caso 3: Crear tax desde evento
$dto = CreateTaxData::fromArray($event->payload);
$tax = Tax::create($dto);
```

---

## 📋 Checklist de validación

### FormRequest debe validar:
- ✅ Tipos de datos HTTP (`integer`, `string`, `boolean`)
- ✅ Formato (`email`, `date`, `regex`)
- ✅ Existencia en BD (`exists`, `unique`)
- ✅ Requerido (`required`, `nullable`)
- ✅ Tamaño (`min`, `max`, `between`)

### DTO debe validar:
- ✅ Reglas de negocio (porcentaje entre 0-100)
- ✅ Invariantes del dominio (nombre no vacío)
- ✅ Lógica específica del negocio

---

## 💡 Pregunta frecuente

**P: ¿No es duplicar validación?**

**R:** No, son validaciones **complementarias**:

```php
// FormRequest: ¿Es un número entre 0-100?
'percentage' => 'numeric|min:0|max:100'

// DTO: ¿Cumple la regla de negocio del dominio?
if ($this->percentage < 0 || $this->percentage > 100) { ... }
```

Puede parecer duplicado, pero:
1. FormRequest valida **formato HTTP**
2. DTO valida **regla de negocio**
3. Si mañana llega data desde un CSV, el DTO sigue validando

---

## 🎯 Conclusión

```php
// ✅ CORRECTO: Ambas validaciones
FormRequest → DTO → Entity

// ❌ INCORRECTO: Solo FormRequest
FormRequest → Entity  // ¿Qué pasa si creo desde CLI?

// ❌ INCORRECTO: Solo DTO
Array → DTO  // ¿Quién valida que tax_type_id existe en BD?
```

**Regla de oro:** 
- **FormRequest** = Puerta de entrada HTTP
- **DTO** = Guardián del dominio
