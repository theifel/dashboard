<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Progress;

use App\Model\Work\Entity\Projects\Task\Task;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public int $id;
    /**
     * @Assert\NotBlank()
     */
    public int $progress;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function fromTask(Task $task): self
    {
        $command = new self($task->getId()->getValue());

        $command->progress = $task->getProgress();

        return $command;
    }
}
