pipeline {
agent any

```
options {
    timestamps()
}

stages {
    stage('Checkout') {
        steps {
            checkout scm
            echo 'Laravel source code checked out successfully.'
        }
    }

    stage('Check PHP and Composer') {
        steps {
            sh '''
                php -v
                composer --version
            '''
        }
    }

    stage('Install Dependencies') {
        steps {
            sh '''
                composer install \
                    --no-interaction \
                    --prefer-dist \
                    --no-progress
            '''
        }
    }

    stage('Run Laravel Tests') {
        steps {
            sh '''
                export APP_ENV=testing
                export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
                php artisan config:clear
                php artisan test
            '''
        }
    }

    stage('Deploy Laravel Application') {
        steps {
            sh '''
                set -eu

                DEPLOY_DIR="/var/www/laravel-app"

                echo "Preparing deployment directory..."

                mkdir -p "$DEPLOY_DIR"

                if [ ! -f "$DEPLOY_DIR/.env" ]; then
                    cp "$WORKSPACE/.env.example" "$DEPLOY_DIR/.env"
                fi

                echo "Copying application files..."

                rsync -a --delete \
                    --exclude='.git/' \
                    --exclude='.env' \
                    --exclude='database/database.sqlite' \
                    --exclude='storage/' \
                    "$WORKSPACE/" "$DEPLOY_DIR/"

                echo "Preparing Laravel environment..."

                sed -i \
                    -e 's/^APP_ENV=.*/APP_ENV=production/' \
                    -e 's/^APP_DEBUG=.*/APP_DEBUG=false/' \
                    -e 's|^APP_URL=.*|APP_URL=http://localhost|' \
                    -e 's/^SESSION_DRIVER=.*/SESSION_DRIVER=file/' \
                    -e 's/^CACHE_STORE=.*/CACHE_STORE=file/' \
                    -e 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' \
                    "$DEPLOY_DIR/.env"

                mkdir -p "$DEPLOY_DIR/database"

                touch "$DEPLOY_DIR/database/database.sqlite"

                mkdir -p \
                    "$DEPLOY_DIR/storage/framework/cache/data" \
                    "$DEPLOY_DIR/storage/framework/sessions" \
                    "$DEPLOY_DIR/storage/framework/views" \
                    "$DEPLOY_DIR/storage/logs" \
                    "$DEPLOY_DIR/bootstrap/cache"

                echo "Configuring file permissions..."

                chown -R jenkins:www-data "$DEPLOY_DIR"

                find "$DEPLOY_DIR" -type d -exec chmod 2755 {} \\;
                find "$DEPLOY_DIR" -type f -exec chmod 0644 {} \\;

                chmod 0640 "$DEPLOY_DIR/.env"

                chmod -R 2775 \
                    "$DEPLOY_DIR/storage" \
                    "$DEPLOY_DIR/bootstrap/cache"

                chmod 0664 "$DEPLOY_DIR/database/database.sqlite"

                echo "Generating application key if required..."

                if ! grep -q '^APP_KEY=base64:' "$DEPLOY_DIR/.env"; then
                    cd "$DEPLOY_DIR"
                    php artisan key:generate --force
                fi

                cd "$DEPLOY_DIR"

                echo "Clearing Laravel configuration cache..."

                php artisan config:clear
                php artisan route:clear
                php artisan view:clear

                echo "Running database migrations..."

                php artisan migrate --force

                echo "Deployment completed successfully."
            '''
        }
    }
}

post {
    success {
        echo 'SUCCESS: Laravel pipeline and deployment completed.'
    }

    failure {
        echo 'FAILED: Check the Jenkins console output.'
    }
}
```

}
