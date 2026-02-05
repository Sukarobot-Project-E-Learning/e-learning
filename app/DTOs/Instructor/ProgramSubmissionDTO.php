<?php

namespace App\DTOs\Instructor;

use JsonSerializable;

class ProgramSubmissionDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $category,
        public readonly string $type,
        public readonly string $status,
        public readonly ?string $rejection_reason,
        public readonly string $created_at,
    ) {}

    public static function fromDatabase(object $row): self
    {
        return new self(
            id: $row->id,
            title: $row->title ?? '',
            category: $row->category,
            type: $row->type ?? 'online',
            status: $row->status ?? 'pending',
            rejection_reason: $row->rejection_reason,
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
            'category' => $this->category,
            'type' => $this->type,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at,
        ];
    }
}
