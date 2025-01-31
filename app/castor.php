<?php

use function Castor\fs;
use function Castor\io;
use function Castor\run;

use Castor\Attribute\AsTask;

/*
 * Composer
 */

/**
 * Install composer dependencies.
 *
 * This task runs the 'composer install' command to install
 * all dependencies defined in the composer.json file.
 */
#[AsTask(description: 'Install composer dependencies', aliases: ['comp:install'], namespace: 'composer')]
function composerInstall(): void
{
    io()->title('Installing composer dependencies');
    run('composer install');
    io()->newLine();
    io()->success('Composer dependencies installed');
}

/*
 * Docker
 */

/**
 * Start Docker Stack
 *
 * This task runs the 'docker compose up -d' command to start
 * all services defined in the compose.yml file in
 * detached mode.
 */
#[AsTask(description: 'Start Docker Stack', aliases: ['docker:start'], namespace: 'docker')]
function dockerStart(): void
{
    io()->title('Starting Docker Stack');
    run('docker compose up -d');
    io()->newLine();
    io()->success('Docker Stack started');
}

/**
 * Stop all services defined in the compose.yml file.
 *
 * This task is useful to stop all services when you are done with
 * development or testing.
 */
#[AsTask(description: 'Stop Docker Stack', aliases: ['docker:stop'], namespace: 'docker')]
function dockerStop(): void
{
    io()->title('Stopping Docker Stack');
    run('docker compose stop');
    io()->newLine();
    io()->success('Docker Stack stopped');
}

/**
 * Restart all services defined in the compose.yml file.
 *
 * This task is useful to restart all services when you have made
 * changes to the compose.yml file.
 */
#[AsTask(description: 'Restart Docker Stack', aliases: ['docker:restart'], namespace: 'docker')]
function dockerRestart(): void
{
    io()->title('Restarting Docker Stack');
    run('docker compose restart');
    io()->newLine();
    io()->success('Docker Stack restarted');
}

/**
 * Remove all services defined in the compose.yml file.
 *
 * This task is useful to remove all services when you are done with
 * development or testing.
 */
#[AsTask(description: 'Remove Docker Stack', aliases: ['docker:remove'], namespace: 'docker')]
function dockerRemove(): void
{
    io()->title('Removing Docker Stack');
    io()->info('This will remove all services defined in the compose.yml file.');
    $confirm = io()->confirm('Are you sure you want to remove the Docker Stack?', false);
    if ($confirm) {
        run('docker compose down');
        io()->newLine();
        io()->success('Docker Stack removed');
    } else {
        io()->warning('Docker Stack not removed');
    }
}

/**
 * Remove all unused Docker images, containers and networks.
 *
 * This task is useful when you want to clean up the Docker environment
 * after you are done with development or testing.
 *
 * @see https://docs.docker.com/engine/reference/commandline/system_prune/
 */
#[AsTask(description: 'Clean Docker Environment', aliases: ['docker:clean'], namespace: 'docker')]
function dockerClean(): void
{
    io()->title('Cleaning Docker Environment');
    io()->info('This will remove all unused Docker images, containers and networks.');
    $confirm = io()->confirm('Are you sure you want to clean the Docker Environment?', false);
    $volumes = io()->confirm('Do you want to remove unused Docker volumes too?', false);
    if ($confirm) {
        run('docker system prune -a -f ' . ($volumes ? '--volumes' : ''));
        io()->newLine();
        io()->success('Docker Environment cleaned');
    } else {
        io()->warning('Docker Environment not cleaned');
    }
}

/*
 * Symfony
 */

/**
 * Create a new Symfony project.
 *
 * This task prompts for the project name and whether to create a web application.
 * It runs the appropriate `symfony new` command to create a new Symfony project
 * with or without the webapp option.
 * It also provides an option to remove the .git folder if the project is inside
 * an existing git repository.
 *
 * @see https://symfony.com/doc/current/setup.html
 */
#[AsTask(description: 'Create new Symfony project', aliases: ['sf:create'], namespace: 'symfony')]
function symfonyCreate(): void
{
    io()->title('Creating new Symfony project');
    $projectName = io()->ask('What is the name of the project?', 'app');
    $webapp = io()->confirm('Do you want to create a webapp?', false);
    if ($webapp) {
        run('symfony new ' . $projectName . ' --webapp');
    } else {
        run('symfony new ' . $projectName);
    }

    fs()->copy(__DIR__ . '/castor.php', $projectName . '/castor.php');
    fs()->rename(__DIR__ . '/castor.php', __DIR__ . '/castor.php.old');

    io()->newLine();
    io()->info('The following command will remove the .git folder in the project');
    $git = io()->confirm('Are you already in a git repository? ', true);
    if ($git) {
        fs()->remove($projectName . '/.git');
    }

    io()->newLine();
    io()->info([
        "'Successfully created project ' . $projectName",
        'Run `cd ' . $projectName . '` to enter the project directory'
    ]);
}

/**
 * Start the Symfony server.
 *
 * This command starts the Symfony server in daemon mode and listen on localhost.
 *
 * @see https://symfony.com/doc/current/setup/symfony_server.html
 */
#[AsTask(description: 'Start Symfony server', aliases: ['sf:server:start'], namespace: 'symfony')]
function serverStart(): void
{
    io()->title('Opening Symfony server');
    run('symfony serve -d --listen-ip=localhost');
}

/**
 * Stop the Symfony server.
 *
 * This command stops the Symfony server started with the
 * `server` command.
 *
 * @see https://symfony.com/doc/current/setup/symfony_server.html
 */
#[AsTask(description: 'Stop Symfony server', aliases: ['sf:server:stop'], namespace: 'symfony')]
function serverStop(): void
{
    io()->title('Stopping Symfony server');
    run('symfony server:stop');
}

/**
 * Clear Symfony cache.
 *
 * This task runs the `symfony console cache:clear` command to clear the
 * Symfony cache.
 */
#[AsTask(description: 'Clear Cache', aliases: ['sf:cache:clear'], namespace: 'symfony')]
function clearCache(): void
{
    io()->title('Clearing Cache');
    run('symfony console cache:clear');
}

/*
 * Maker Bundle
 */

/**
 * Installs the Maker Bundle.
 *
 * This task runs the `composer require --dev symfony/maker-bundle` command to install
 * the Maker Bundle.
 *
 * @see https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html
 */
#[AsTask(description: 'Install Maker Bundle', aliases: ['make:install'], namespace: 'maker')]
function installMakerBundle(): void
{
    io()->title('Installing Maker Bundle');
    run('composer require --dev symfony/maker-bundle');
    io()->newLine();
    io()->success('Maker Bundle installed');
}

/*
 * DB
 */

/**
 * Create a new database.
 *
 * This task runs the `symfony console doctrine:database:create` command to
 * create a new database.
 *
 * @see https://symfony.com/doc/current/doctrine.html#creating-the-database
 */
#[AsTask(description: 'Create new Database', aliases: ['db:create'], namespace: 'database')]
function createDatabase(): void
{
    io()->title('Creating new Database');
    run('symfony console doctrine:database:create --if-not-exists');
}

/**
 * Drop the current database.
 *
 * This task runs the `symfony console doctrine:database:drop --force` command to
 * drop the current database.
 *
 * @see https://symfony.com/doc/current/doctrine.html#drop-the-database
 */
#[AsTask(description: 'Drop Database', aliases: ['db:drop'], namespace: 'database')]
function dropDatabase(): void
{
    io()->title('Dropping Database');
    run('symfony console doctrine:database:drop --force');
}

/**
 * Create a new Doctrine Migration.
 *
 * This task runs the `symfony console make:migration` command to create
 * a new Doctrine migration.
 *
 * @see https://symfony.com/doc/current/doctrine.html#migrations-creating-the-database-tables-schema
 */
#[AsTask(description: 'Create new Migration', aliases: ['db:migration'], namespace: 'database')]
function createMigration(): void
{
    io()->title('Creating new Migration');
    run('symfony console make:migration ');
}

/**
 * Run all available Doctrine migrations to update the database to the latest version.
 *
 * This task runs the `symfony console doctrine:migrations:migrate` command to execute
 * all available Doctrine migrations to update the database to the latest version.
 *
 * @see https://symfony.com/doc/current/doctrine.html#migrations-creating-the-database-tables-schema
 */
#[AsTask(description: 'Run Migrations', aliases: ['db:migrate'], namespace: 'database')]
function runMigrations(): void
{
    io()->title('Running Migrations');
    run('symfony console doctrine:migrations:migrate');
}

/**
 * Initialize the database by creating it if it does not exist,
 * generating a new migration, and applying all migrations.
 *
 * This task performs the following commands:
 * 1. `symfony console doctrine:database:create --if-not-exists` to create the database if it doesn't exist.
 * 2. `symfony console make:migration` to create a new Doctrine migration.
 * 3. `symfony console doctrine:migrations:migrate` to apply all available migrations.
 *
 * @see https://symfony.com/doc/current/doctrine.html
 */
#[AsTask(description: 'Initialize Database', aliases: ['db:init'], namespace: 'database')]
function initializeDatabase(): void
{
    io()->title('Initializing Database');
    run('symfony console doctrine:database:create --if-not-exists');
    run('symfony console make:migration');
    run('symfony console doctrine:migrations:migrate');
    $fixtures = io()->ask('Would you like to load fixtures?', 'y');
    if ($fixtures === 'y') {
        loadFixtures();
    }
    io()->newLine();
    io()->success('Database initialized');
}

/**
 * Reset the current database.
 *
 * This task runs the following commands to reset the current database:
 *
 * 1. `symfony console doctrine:database:drop --force` to drop the current database.
 * 2. `symfony console doctrine:database:create` to create a new database.
 * 3. `symfony console doctrine:migrations:migrate` to apply all migrations.
 *
 * @see https://symfony.com/doc/current/doctrine.html#resetting-the-database
 */
#[AsTask(description: 'Reset Database', aliases: ['db:reset'], namespace: 'database')]
function resetDatabase(): void
{
    io()->title('Resetting Database');
    run('symfony console doctrine:database:drop --force');
    run('symfony console doctrine:database:create');
    run('symfony console doctrine:migrations:migrate');
    $fixtures = io()->ask('Would you like to load fixtures?', 'y');
    if ($fixtures === 'y') {
        loadFixtures();
    }
    io()->newLine();
    io()->success('Database reset');
}

/*
 * Fixtures
 */

/**
 * Installs the Doctrine Fixtures Bundle and asks if you want to install FakerPHP.
 *
 * If you choose to install FakerPHP, it will ask you where you want to create your fixtures.
 * If the file does not exist, it will create it with a sample content.
 * If the file already exists, it will inform you that you can edit it to add your fixtures.
 *
 * @see https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
 * @see https://fakerphp.org/
 */
#[AsTask(description: 'Install Fixtures Bundle', aliases: ['fixt:install'], namespace: 'fixtures')]
function installFixtures(): void
{
    io()->title('Installing Fixtures Bundle');
    run('composer require --dev doctrine/doctrine-fixtures-bundle');

    io()->newLine();
    $useFaker = io()->ask('Would you use FakerPHP?', 'y');

    if ($useFaker === 'y') {
        io()->section('Installing FakerPHP');
        run('composer require --dev fakerphp/faker');

        io()->newLine();
        $path = io()->ask('Where do you want to create your fixtures?', 'src/DataFixtures');

        if (!fs()->exists($path . '/AppFixtures.php')) {
            fs()->mkdir($path);
            fs()->touch($path . '/AppFixtures.php');

            $fixturesFileContent = <<<'EOF'
            <?php

            namespace App\DataFixtures;

            use Faker\Factory as Factory;
            use Doctrine\Persistence\ObjectManager;
            use Doctrine\Bundle\FixturesBundle\Fixture;

            class AppFixtures extends Fixture
            {
                public function load(ObjectManager $manager): void
                {
                    $faker = Factory::create('fr_FR');
                    // ...
                }
            }
            EOF;

            fs()->appendToFile($path . '/AppFixtures.php', $fixturesFileContent);
            io()->newLine();
            io()->info([
                '`' . $path . '/AppFixtures.php` created.',
                'Edit this file to add your fixtures.'
            ]);
        } else {
            io()->newLine();
            io()->info([
                '`' . $path . '/AppFixtures.php` already exists.',
                'Edit this file to add your fixtures.'
            ]);
        }
        io()->success('FakerPHP installed');
    }
}

/**
 * Load fixtures from the App\DataFixtures namespace to the database.
 *
 * @see https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html
 */
#[AsTask(description: 'Load Fixtures', aliases: ['fixt:load'], namespace: 'fixtures')]
function loadFixtures(): void
{
    io()->title('Loading Fixtures');
    run('symfony console doctrine:fixtures:load');
}
