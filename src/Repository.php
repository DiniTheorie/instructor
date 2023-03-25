<?php

/*
 * This file is part of the DiniTheorie project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DiniTheorie\Instructor;

class Repository
{
    public const REPO_PATH = __DIR__.'/../var/persistent/Data';

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setup();
    }

    /**
     * @throws \Exception
     */
    private function executeCommand(string $command): void
    {
        exec($command, $output, $result);
        if ($result > 0) {
            throw new \Exception('command execution failed: '.join(', ', $output));
        }
    }

    /**
     * @throws \Exception
     */
    private function executeRepositoryCommand(string $command): void
    {
        exec('cd '.self::REPO_PATH.' && '.$command, $output, $result);
        if ($result > 0) {
            throw new \Exception('repository command execution failed: '.join(', ', $output));
        }
    }

    /**
     * @throws \Exception
     */
    private function setup(): void
    {
        if (!is_dir(self::REPO_PATH)) {
            mkdir(self::REPO_PATH, 0777, true);
            $this->executeCommand('git clone git@github.com:DiniTheorie/Data '.self::REPO_PATH);
        } else {
            $this->executeRepositoryCommand('git pull');
        }
    }

    /**
     * @throws \Exception
     */
    public function store(): void
    {
        $this->executeRepositoryCommand('git add -A');
        $this->executeRepositoryCommand('git commit -m "instructor: Store"');
        $this->executeRepositoryCommand('git push');
    }
}
