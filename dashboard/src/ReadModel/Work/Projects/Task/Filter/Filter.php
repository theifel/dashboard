<?php

declare(strict_types=1);

namespace App\ReadModel\Work\Projects\Task\Filter;

class Filter
{
    public ?string $project;
    public ?string $member = null;
    public ?string $author = null;
    public ?string $name = null;
    public ?string $type = null;
    public ?string $status = null;
    public ?int $priority = null;
    public ?string $executor = null;

    private function __construct(?string $project)
    {
        $this->project = $project;
    }

    public static function forProject(string $project): self
    {
        return new self($project);
    }

    public static function all(): self
    {
        return new self(null);
    }

    public function forMember(string $member): self
    {
        $clone = clone $this;

        $clone->member = $member;

        return $clone;
    }
}
