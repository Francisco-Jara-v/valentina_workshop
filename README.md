# 🧵 ValentinaWorkshop

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![Filament](https://img.shields.io/badge/Filament-v4-orange)
![MySQL](https://img.shields.io/badge/MySQL-Database-blue)
![Status](https://img.shields.io/badge/Status-Production-green)

Sistema web de gestión de pedidos, insumos y control de ganancias para emprendimientos.

---

## 🚀 Sobre el proyecto

**ValentinaWorkshop** es una aplicación desarrollada para digitalizar y optimizar la gestión de un emprendimiento real, eliminando el uso de registros manuales y permitiendo un control preciso del negocio.

El sistema fue construido para resolver problemas concretos de organización, inventario y cálculo de ganancias, entregando información clara y en tiempo real.

---

## 🎯 Problema

En la gestión tradicional del emprendimiento:

* Los pedidos se registraban en cuadernos
* No existía control de stock
* No se conocía la ganancia real por producto
* La información estaba dispersa

---

## 💡 Solución

El sistema permite:

* Centralizar toda la información del negocio
* Calcular automáticamente costos y ganancias
* Controlar insumos y su uso en pedidos
* Mantener información técnica organizada (notas)

---

## 🧠 Valor del proyecto

Este proyecto destaca porque:

* ✔ Está basado en un caso real (no es solo académico)
* ✔ Automatiza cálculos clave del negocio
* ✔ Aplica buenas prácticas con Laravel (MVC, migraciones, relaciones)
* ✔ Usa herramientas modernas como FilamentPHP
* ✔ Mejora directamente la toma de decisiones

---

## ⚙️ Funcionalidades

### 📦 Gestión de insumos

* CRUD completo
* Registro de costos
* Control de inventario

### 📋 Gestión de pedidos

* CRUD de pedidos
* Asociación de insumos
* Cálculo automático de costos
* Cálculo de ganancia

### 🧮 Cálculo inteligente

* Suma automática de insumos utilizados
* Cálculo de ganancia:

  **ganancia = precio de venta - costo total**

### 📝 Notas

* Registro de información libre
* Organización por categorías
* Uso para parámetros técnicos (ej: sublimación)

### 🔍 Búsqueda y filtros

* Filtrado de datos en múltiples módulos

### 📊 Dashboard

* Total invertido
* Ganancia total
* Inversión por pedido
* Pedidos pendientes de entrega

---

## 🧱 Tecnologías

* **Backend:** PHP
* **Framework:** Laravel 12
* **Admin Panel:** FilamentPHP v4
* **Base de datos:** MySQL
* **Entorno:** Laragon

---

## 🏗️ Arquitectura

El sistema está desarrollado bajo el patrón:

**MVC (Modelo - Vista - Controlador)**

Incluye:

* Migraciones
* Relaciones entre tablas
* Lógica de negocio encapsulada
* Componentes de Filament para UI

---

## 🗄️ Base de datos

Tablas principales:

* `insumos`
* `pedidos`
* `detalle_pedido`
* `notas`
* `categorias`

Relaciones clave:

* Un pedido tiene múltiples insumos
* Los insumos se relacionan mediante `detalle_pedido`
* Las notas pertenecen a categorías

---

## 🖥️ Interfaz

* Panel administrativo moderno con Filament
* Menú lateral
* Formularios dinámicos
* Diseño enfocado en productividad

---

## 🚀 Instalación

```bash id="install1"
git clone (https://github.com/Francisco-Jara-v/valentina_workshop)
cd valentinaworkshop
```

```bash id="install2"
composer install
```

```bash id="install3"
cp .env.example .env
```

Configurar base de datos en `.env`

```bash id="install4"
php artisan key:generate
php artisan migrate
```

```bash id="install5"
php artisan serve
```

---


## 🔐 Acceso

Sistema con soporte para múltiples usuarios.

---

## 📈 Estado del proyecto

✔ En uso real
✔ Funcional
🔄 Escalable

---

## 🔮 Roadmap (mejoras futuras)

* Autenticación avanzada (roles/permisos)
* Reportes exportables (PDF / Excel)
* Alertas de stock bajo
* Backup automático
* Versión móvil

---

## 👨‍💻 Autor

Desarrollado como solución real para la gestión de un emprendimiento.

---

## ⭐ Relevancia

Este proyecto demuestra habilidades en:

* Desarrollo backend con Laravel
* Modelado de base de datos
* Automatización de lógica de negocio
* Desarrollo de herramientas útiles en contextos reales
