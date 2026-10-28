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
                sh 'php artisan test'
            }
        }
    }

    post {
        success {
            echo 'SUCCESS: Laravel pipeline completed.'
        }

        failure {
            echo 'FAILED: Check the Jenkins console output.'
        }
    }
}
