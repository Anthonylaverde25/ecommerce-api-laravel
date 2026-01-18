# Autenticación Laravel Sanctum - Documentación

## 📚 Tabla de Contenidos
1. [Introducción](#introducción)
2. [Endpoints Disponibles](#endpoints-disponibles)
3. [Integración con Cliente Vite](#integración-con-cliente-vite)
4. [Gestión de Sesiones](#gestión-de-sesiones)
5. [Seguridad](#seguridad)

---

## Introducción

Este sistema de autenticación usa **Laravel Sanctum** para proveer tokens de API que permiten a tu cliente Vite comunicarse de forma segura con la API.

### ¿Cómo funciona?

1. Usuario hace **login** → API genera un token aleatorio único
2. Token se guarda en tabla `personal_access_tokens` de la base de datos
3. Cliente guarda token en `localStorage`
4. Cada request incluye header: `Authorization: Bearer {token}`
5. Laravel valida el token consultando la BD
6. Al hacer **logout** → Token se elimina de BD (¡revocado instantáneamente!)

### Ventajas de Sanctum

✅ **Revocación Inmediata**: Puedes invalidar tokens al instante  
✅ **Control de Sesiones**: Ver y gestionar todos los dispositivos activos  
✅ **Auditoría**: Saber cuándo y dónde se creó cada token  
✅ **Multi-dispositivo**: Permite múltiples sesiones simultáneas

---

## Endpoints Disponibles

### Rutas Públicas (sin autenticación)

#### 1. Registro de Usuario

**POST** `/api/auth/register`

**Headers:**
```http
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@erp.com",
  "password": "password123",
  "password_confirmation": "password123",
  "device_name": "Chrome - Windows" // Opcional
}
```

**Respuesta exitosa (201 Created):**
```json
{
  "token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@erp.com",
    "email_verified_at": null,
    "created_at": "2024-12-24T19:00:00.000000Z",
    "updated_at": "2024-12-24T19:00:00.000000Z"
  }
}
```

**Errores posibles:**
- **422 Unprocessable Entity**: Validación fallida (ej: email duplicado)
- **429 Too Many Requests**: Demasiados intentos (máx. 6 por minuto)

---

#### 2. Login

**POST** `/api/auth/login`

**Headers:**
```http
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "email": "juan@erp.com",
  "password": "password123",
  "device_name": "Safari - MacOS" // Opcional
}
```

**Respuesta exitosa (200 OK):**
```json
{
  "token": "2|zYxWvUtSrQpOnMlKjIhGfEdCbA0987654321",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@erp.com",
    ...
  }
}
```

**Errores posibles:**
- **401 Unauthorized**: Credenciales incorrectas
- **422 Unprocessable Entity**: Validación fallida
- **429 Too Many Requests**: Demasiados intentos

---

### Rutas Protegidas (requieren autenticación)

**Todas estas rutas requieren el header:**
```http
Authorization: Bearer {tu_token_aqui}
```

---

#### 3. Obtener Usuario Autenticado

**GET** `/api/auth/me`

**Respuesta exitosa (200 OK):**
```json
{
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@erp.com",
    "email_verified_at": null,
    "created_at": "2024-12-24T19:00:00.000000Z",
    "updated_at": "2024-12-24T19:00:00.000000Z"
  }
}
```

---

#### 4. Logout (Cerrar Sesión Actual)

**POST** `/api/auth/logout`

Revoca **solo** el token que estás usando actualmente.

**Respuesta exitosa (200 OK):**
```json
{
  "message": "Logout exitoso"
}
```

---

#### 5. Logout de Todos los Dispositivos

**POST** `/api/auth/logout-all`

Revoca **todos** los tokens del usuario. Útil si detectas actividad sospechosa.

**Respuesta exitosa (200 OK):**
```json
{
  "message": "Se cerraron todas las sesiones"
}
```

---

#### 6. Listar Sesiones Activas

**GET** `/api/auth/tokens`

Obtiene todos los tokens/dispositivos activos del usuario.

**Respuesta exitosa (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Chrome - Windows",
      "abilities": ["*"],
      "last_used_at": "2024-12-24T19:00:00.000000Z",
      "created_at": "2024-12-24T18:00:00.000000Z",
      "is_current": false
    },
    {
      "id": 2,
      "name": "Safari - MacOS",
      "abilities": ["*"],
      "last_used_at": "2024-12-24T19:30:00.000000Z",
      "created_at": "2024-12-24T19:00:00.000000Z",
      "is_current": true
    }
  ]
}
```

**Uso en frontend:**
Puedes mostrar una lista de "Dispositivos conectados" con botón para cerrar cada sesión individualmente.

---

#### 7. Revocar Token Específico

**DELETE** `/api/auth/tokens/{tokenId}`

Revoca un token específico por su ID. Útil para "cerrar sesión en otro dispositivo".

**Parámetros:**
- `tokenId`: ID del token a revocar (de la lista de `/api/auth/tokens`)

**Respuesta exitosa (200 OK):**
```json
{
  "message": "Token revocado exitosamente"
}
```

**Errores posibles:**
- **404 Not Found**: Token no existe o no pertenece al usuario

---

## Integración con Cliente Vite

### 1. Configuración de Axios

Crea un archivo `src/api/axios.js`:

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor para agregar token automáticamente
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Interceptor para manejar errores de autenticación
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token inválido o expirado
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

---

### 2. Ejemplo de Login

```javascript
import api from './api/axios';

async function login(email, password) {
  try {
    const response = await api.post('/auth/login', {
      email,
      password,
      device_name: navigator.userAgent, // Opcional
    });

    // Guardar token
    localStorage.setItem('auth_token', response.data.token);

    // Guardar usuario (opcional)
    localStorage.setItem('user', JSON.stringify(response.data.user));

    return response.data;
  } catch (error) {
    if (error.response?.status === 401) {
      throw new Error('Credenciales incorrectas');
    }
    throw error;
  }
}
```

---

### 3. Ejemplo de Registro

```javascript
async function register(name, email, password, passwordConfirmation) {
  try {
    const response = await api.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
      device_name: navigator.userAgent,
    });

    // Guardar token automáticamente después del registro
    localStorage.setItem('auth_token', response.data.token);
    localStorage.setItem('user', JSON.stringify(response.data.user));

    return response.data;
  } catch (error) {
    if (error.response?.status === 422) {
      // Errores de validación
      throw error.response.data.errors;
    }
    throw error;
  }
}
```

---

### 4. Ejemplo de Logout

```javascript
async function logout() {
  try {
    await api.post('/auth/logout');
  } catch (error) {
    console.error('Error al hacer logout:', error);
  } finally {
    // Limpiar localStorage siempre
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    window.location.href = '/login';
  }
}
```

---

### 5. Obtener Usuario Actual

```javascript
async function getCurrentUser() {
  try {
    const response = await api.get('/auth/me');
    return response.data.data;
  } catch (error) {
    console.error('Error al obtener usuario:', error);
    throw error;
  }
}
```

---

### 6. Proteger Rutas en React Router

```javascript
import { Navigate } from 'react-router-dom';

function PrivateRoute({ children }) {
  const token = localStorage.getItem('auth_token');

  if (!token) {
    return <Navigate to="/login" replace />;
  }

  return children;
}

// Uso:
<Route
  path="/dashboard"
  element={
    <PrivateRoute>
      <Dashboard />
    </PrivateRoute>
  }
/>
```

---

## Gestión de Sesiones

### Listar Dispositivos Activos

```javascript
async function getActiveSessions() {
  const response = await api.get('/auth/tokens');
  return response.data.data;
}
```

**Ejemplo de UI:**
```jsx
function ActiveSessions() {
  const [sessions, setSessions] = useState([]);

  useEffect(() => {
    getActiveSessions().then(setSessions);
  }, []);

  const handleRevoke = async (tokenId) => {
    await api.delete(`/auth/tokens/${tokenId}`);
    setSessions(sessions.filter(s => s.id !== tokenId));
  };

  return (
    <div>
      <h2>Sesiones Activas</h2>
      {sessions.map(session => (
        <div key={session.id}>
          <p>{session.name}</p>
          <p>Última vez usado: {session.last_used_at}</p>
          {session.is_current && <span>(Sesión actual)</span>}
          {!session.is_current && (
            <button onClick={() => handleRevoke(session.id)}>
              Cerrar sesión
            </button>
          )}
        </div>
      ))}
    </div>
  );
}
```

---

### Cerrar Todas las Sesiones

```javascript
async function logoutAllDevices() {
  await api.post('/auth/logout-all');
  localStorage.removeItem('auth_token');
  localStorage.removeItem('user');
  window.location.href = '/login';
}
```

---

## Seguridad

### Rate Limiting

Los endpoints de login y register están protegidos con rate limiting:
- **Máximo 6 intentos por minuto**
- Al exceder el límite, retorna error **429 Too Many Requests**

### CORS

El servidor está configurado para aceptar peticiones desde:
- **Desarrollo**: `http://localhost:3000`
- **Producción**: Configurar `FRONTEND_URL` en `.env`

### Best Practices

1. **Guardar token en localStorage** (o sessionStorage para mayor seguridad)
2. **Nunca** expongas el token en URLs o logs
3. **HTTPS en producción** es obligatorio
4. **Validar token en cada request** (Axios interceptor lo hace automáticamente)
5. **Implementar refresh UI** cuando detectes usuario no autenticado (401)
6. **Ofrecer "Cerrar todas las sesiones"** si usuario sospecha actividad no autorizada

### Expiración de Tokens

Por defecto, los tokens **NO expiran**. Los gestionas manualmente:
- Al hacer logout → Token eliminado
- Puedes configurar expiración en `.env`: `SANCTUM_EXPIRATION=1440` (minutos)

---

## Variables de Entorno Requeridas

Asegúrate de tener estas variables en tu `.env`:

```env
# Frontend URL (cliente Vite)
FRONTEND_URL=http://localhost:3000

# Dominios stateful para Sanctum (si usas cookies)
SANCTUM_STATEFUL_DOMAINS=localhost:3000

# Expiración de tokens (null = nunca expiran)
SANCTUM_EXPIRATION=null
```

---

## Testing Manual

### Con cURL

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"juan@erp.com","password":"password123"}'
```

**Request autenticado:**
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer 1|aBcDeFg..."
```

### Con Postman

1. Crear request POST a `/api/auth/login`
2. En Body → raw → JSON:
   ```json
   {
     "email": "test@erp.com",
     "password": "password123"
   }
   ```
3. Copiar el `token` de la respuesta
4. En otras requests, agregar header:
   - Key: `Authorization`
   - Value: `Bearer {token_copiado}`

---

## Troubleshooting

**Error: "Unauthenticated"**
- Verifica que el token esté en el header `Authorization: Bearer {token}`
- Verifica que el token no haya sido revocado
- Verifica que el usuario aún exista en la BD

**Error: "CORS policy"**
- Asegúrate de tener `FRONTEND_URL=http://localhost:3000` en `.env`
- Verifica que `config/cors.php` esté configurado correctamente

**Error: "Too Many Requests"**
- Has excedido el rate limit (6 intentos/min)
- Espera 1 minuto antes de intentar de nuevo

**Token no se guarda**
- Verifica que la tabla `personal_access_tokens` exista
- Ejecuta `php artisan migrate` si no existe

---

## Soporte

Para más información sobre Laravel Sanctum:
- [Documentación Oficial](https://laravel.com/docs/sanctum)
- [GitHub Repository](https://github.com/laravel/sanctum)
