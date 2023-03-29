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
    public const REPO_DIR = PathHelper::VAR_PERSISTENT_DIR.'/Data';

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        if ('dev' !== $_SERVER['APP_ENV']) {
            $this->setup();
        }
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
    private function executeRepositoryCommand(string $command): ?array
    {
        exec('cd '.self::REPO_DIR.' && '.$command, $output, $result);
        if ($result > 0) {
            throw new \Exception('repository command execution failed: '.join(', ', $output));
        }

        return $output;
    }

    /**
     * @throws \Exception
     */
    private function setup(): void
    {
        if (!is_dir(self::REPO_DIR)) {
            mkdir(self::REPO_DIR, 0777, true);
            $this->executeCommand('git clone git@github.com:DiniTheorie/Data '.self::REPO_DIR);
            $this->executeRepositoryCommand('git checkout -b automation');
        } else {
            $this->executeRepositoryCommand('git pull');
        }
    }

    /**
     * @throws \Exception
     */
    public function store(): void
    {
        $output = $this->executeRepositoryCommand('git status --porcelain');
        if ($output) {
            $this->executeRepositoryCommand('git add -A');
            $this->executeRepositoryCommand('git commit -m "instructor: Store" --author="Automation <automation@instructor.dinitheorie.ch>"');
            $this->executeRepositoryCommand('git push --set-upstream');
        }
    }

    /**
     * @throws \Exception
     */
    public function resetHard(): void
    {
        $this->executeRepositoryCommand('git add -A');
        $this->executeRepositoryCommand('git reset HEAD --hard');
    }
}
