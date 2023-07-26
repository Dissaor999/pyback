
# CENTRALIZED SYSTEM

Crear el archivo .env
```sh
cp .env.example .env
```

Actualizar variables de entorno desde archivo .env
```dosini
APP_NAME=Hernes
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=mysql-hermes
DB_PORT=3306
DB_DATABASE=hermes_db
DB_USERNAME=root
DB_PASSWORD=root
```

Cargar contenedores de proyectos
```sh
docker-compose build --no-cache
docker-compose up -d
```

Acceder al contenedor
```sh
docker-compose exec app-hermes bash
```

Instalar dependencias del proyecto && generar clave de proyecto Laravel && Base de datos
```sh
echo '{}' > composer.lock
composer install
php artisan key:generate
php artisan migrate
```


Accede al proyecto
[http://localhost:8989](http://localhost:8989)
