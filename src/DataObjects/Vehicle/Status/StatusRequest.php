<?php

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle\Status;

class StatusRequest
{
    public function __construct(
        public string $status,
        public string $operation,
        public array $body,
        public string $requestId,
        public string $vcfRequestId,
    ) {
    }

    static function fromApi(array $data): StatusRequest
    {
        $body = $data['body'] ?? [];

        return new StatusRequest(
            $data['status'],
            $data['operation'],
            $body,
            $data['requestId'],
            $data['vcfRequestId'],
        );
    }

    static function fromArray(array $data): StatusRequest
    {
        return new StatusRequest(
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
