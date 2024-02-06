<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status;

class Error
{
    public function __construct(
        public ?string $message,
        public ?string $errorTimestamp,
        public ?string $info,
        public ?int $code,
        public ?int $group,
        public ?bool $retry,
    ) {}

    public static function fromApi(array $data): Error
    {
        $values = $data['error'] ?? [];

        $message = $values['message'] ?? null;
        $errorTimestamp = $values['errorTimeStamp'] ?? null;
        $info = $values['info'] ?? null;
        $code = $values['code'] ?? null;
        $group = $values['group'] ?? null;
        $retry = $values['retry'] ?? null;

        return new Error(
            $message,
            $errorTimestamp,
            $info,
            $code,
            $group,
            $retry,
        );
    }

    public static function fromArray(array $data): Error
    {
        return new Error(
            $data['message'],
            $data['error_timestamp'],
            $data['info'],
            $data['code'],
            $data['group'],
            $data['retry'],
        );
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'error_timestamp' => $this->errorTimestamp,
            'info' => $this->info,
            'code' => $this->code,
            'group' => $this->group,
            'retry' => $this->retry,
        ];
    }
}
