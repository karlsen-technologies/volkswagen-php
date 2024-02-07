<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status;

class Request
{
    public function __construct(
        public string $status,
        public string $operation,
        public array $body,
        public string $requestId,
        public ?string $vcfRequestId,
    ) {}

    public static function fromApi(array $data): Request
    {
        $body = $data['body'] ?? [];

        return new Request(
            $data['status'],
            $data['operation'],
            $body,
            $data['requestId'],
            $data['vcfRequestId'],
        );
    }

    public static function fromArray(array $data): Request
    {
        return new Request(
            $data['status'],
            $data['operation'],
            $data['body'],
            $data['request_id'],
            $data['vcf_request_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'operation' => $this->operation,
            'body' => $this->body,
            'request_id' => $this->requestId,
            'vcf_request_id' => $this->vcfRequestId,
        ];
    }
}
