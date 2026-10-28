
pipeline {
    agent any

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
                    set -eu
                    php -v
                    composer --version
                    rsync --version
                '''
            }
        }

        stage('Install Dependencies') {
            steps {
                sh '''
                    set -eu
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
                    set -eu

                    export APP_ENV=testing
                    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
                    export DB_CONNECTION=sqlite
                    export DB_DATABASE=:memory:

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

                    sudo mkdir -p "$DEPLOY_DIR"

                    if [ ! -f "$DEPLOY_DIR/.env" ]; then
                        sudo cp "$WORKSPACE/.env.example" "$DEPLOY_DIR/.env"
                    fi

                    echo "Copying application files..."

                    sudo rsync -a --delete \
                        --exclude='.git/' \
                        --exclude='.env' \
                        --exclude='vendor/' \
                        --exclude='storage/' \
                        --exclude='bootstrap/cache/' \
                        --exclude='database/database.sqlite' \
                        "$WORKSPACE/" "$DEPLOY_DIR/"

                    echo "Installing production dependencies..."

                    sudo composer install \
                        --working-dir="$DEPLOY_DIR" \
                        --no-dev \
                        --no-interaction \
                        --prefer-dist \
                        --optimize-autoloader

                    echo "Preparing Laravel environment..."

                    sudo sed -i \
                        -e 's/^APP_ENV=.*/APP_ENV=production/' \
                        -e 's/^APP_DEBUG=.*/APP_DEBUG=false/' \
                        -e 's|^APP_URL=.*|APP_URL=http://localhost|' \
                        -e 's/^SESSION_DRIVER=.*/SESSION_DRIVER=file/' \
                        -e 's/^CACHE_STORE=.*/CACHE_STORE=file/' \
                        -e 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' \
                        "$DEPLOY_DIR/.env"

                    sudo mkdir -p \
                        "$DEPLOY_DIR/storage/framework/cache/data" \
                        "$DEPLOY_DIR/storage/framework/sessions" \
                        "$DEPLOY_DIR/storage/framework/views" \
                        "$DEPLOY_DIR/storage/logs" \
                        "$DEPLOY_DIR/bootstrap/cache" \
                        "$DEPLOY_DIR/database"

                    sudo touch "$DEPLOY_DIR/database/database.sqlite"

                    echo "Configuring permissions..."

                    sudo chown -R jenkins:www-data "$DEPLOY_DIR"

                    sudo find "$DEPLOY_DIR" -type d -exec chmod 2755 {} \\;
                    sudo find "$DEPLOY_DIR" -type f -exec chmod 0644 {} \\;

                    sudo chmod 0640 "$DEPLOY_DIR/.env"

                    sudo chmod -R 2775 \
                        "$DEPLOY_DIR/storage" \
                        "$DEPLOY_DIR/bootstrap/cache"

                    sudo chmod 0664 "$DEPLOY_DIR/database/database.sqlite"

                    echo "Generating application key if missing..."

                    if ! sudo grep -q '^APP_KEY=base64:' "$DEPLOY_DIR/.env"; then
                        cd "$DEPLOY_DIR"
                        sudo php artisan key:generate --force
                    fi

                    cd "$DEPLOY_DIR"

                    echo "Clearing Laravel caches..."

                    sudo php artisan config:clear
                    sudo php artisan route:clear
                    sudo php artisan view:clear

                    echo "Running database migrations..."

                    sudo php artisan migrate --force

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
}
