<?php

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status;

class StatusError
{
    public function __construct(
        public ?string $message,
        public ?string $errorTimestamp,
        public ?string $info,
        public ?int $code,
        public ?int $group,
        public ?bool $retry,
    ) {
    }

    static function fromApi(array $data): StatusError
    {
        $values = $data['error'] ?? [];

        $message = $values['message'] ?? null;
        $errorTimestamp = $values['errorTimeStamp'] ?? null;
        $info = $values['info'] ?? null;
        $code = $values['code'] ?? null;
        $group = $values['group'] ?? null;
        $retry = $values['retry'] ?? null;

        return new StatusError(
            $message,
            $errorTimestamp,
            $info,
            $code,
            $group,
            $retry,
        );
    }

    static function fromArray(array $data): StatusError
    {
        return new StatusError(
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
