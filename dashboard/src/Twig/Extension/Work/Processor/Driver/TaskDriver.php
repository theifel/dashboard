<?php

declare(strict_types=1);

namespace App\Twig\Extension\Work\Processor\Driver;

use App\ReadModel\Work\Projects\Task\TaskFetcher;
use Twig\Environment;

class TaskDriver implements Driver
{
    private const PATTERN = '/#\d+/';

    private TaskFetcher $tasks;
    private Environment $twig;

    public function __construct(TaskFetcher $tasks, Environment $twig)
    {
        $this->tasks = $tasks;
        $this->twig = $twig;
    }

    public function process(string $text): string
    {
        return preg_replace_callback(self::PATTERN, function (array $matches) {
            $id = \ltrim($matches[0], '#');

            if (null === $task = $this->tasks->find($id)) {
                return $matches[0];
            }

            return $this->twig->render('processor/work/task.html.twig', compact('task'));
        }, $text);
    }
}
