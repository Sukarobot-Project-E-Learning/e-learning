<?php

namespace App\DTOs\Instructor;

use JsonSerializable;

class QuizDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $program,
        public readonly int $total_questions,
        public readonly int $total_responses,
        public readonly string $status,
        public readonly string $created_at,
    ) {}

    public static function fromDatabase(object $row): self
    {
        return new self(
            id: $row->id,
            title: $row->title ?? '',
            program: $row->program ?? null,
            total_questions: $row->total_questions ?? 0,
            total_responses: $row->total_responses ?? 0,
            status: $row->status ?? 'draft',
            created_at: $row->created_at,
        );
    }

    public static function collection(iterable $rows): array
    {
        $items = [];
        foreach ($rows as $row) {
            $items[] = self::fromDatabase($row);
        }
        return $items;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'program' => $this->program,
            'total_questions' => $this->total_questions,
            'total_responses' => $this->total_responses,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
