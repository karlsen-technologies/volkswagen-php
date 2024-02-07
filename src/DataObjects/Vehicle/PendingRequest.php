<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle;

class PendingRequest
{
    /*
     * "id" => "db4c4f45-bf38-487f-a5ab-de6f62101f57",
        "userId" => "14828885-0484-42fc-b4cc-310e4661c280",
        "vin" => "WVWZZZAUZJ8901429",
        "httpMethod" => "POST",
        "path" => "/vehicles/WVWZZZAUZJ8901429/windowheating/start",
        "capability" => "climatisation",
        "operation" => "start",
        "operationGroup" => "windowHeating",
        "body" => "{"callback":"https://emea.bff.cariad.digital/vehicle/v1/vehicles/callbacks/WVWZZZAUZJ8901429/windowHeating/start/POST"}",
        "vcfRequestId" => "134999494",
        "status" => "in_progress",
        "queuedOperation" => null,
        "timestamp" => "2024-02-07T21:20:12.082852132Z",

     * */
    public function __construct(
        public string $id,
        public string $userId,
        public string $vin,
        public string $httpMethod,
        public string $path,
        public string $capability,
        public string $operation,
        public string $operationGroup,
        public string $body,
        public string $vcfRequestId,
        public string $status,
        public ?PendingRequest $queuedOperation,
        public string $timestamp,
    ) {}

    public static function fromApi(array $data): PendingRequest
    {
        $queuedOperation = null;

        if ($data['queuedOperation'] !== null) {
            $queuedOperation = PendingRequest::fromApi($data['queuedOperation']);
        }

        return new PendingRequest(
            $data['id'],
            $data['userId'],
            $data['vin'],
            $data['httpMethod'],
            $data['path'],
            $data['capability'],
            $data['operation'],
            $data['operationGroup'],
            $data['body'],
            $data['vcfRequestId'],
            $data['status'],
            $queuedOperation,
            $data['timestamp'],
        );
    }

    public static function fromArray(array $data): PendingRequest
    {
        $queuedOperation = null;

        if (isset($data['queuedOperation'])) {
            $queuedOperation = PendingRequest::fromArray($data['queuedOperation']);
        }

        return new PendingRequest(
            $data['id'],
            $data['user_id'],
            $data['vin'],
            $data['http_method'],
            $data['path'],
            $data['capability'],
            $data['operation'],
            $data['operation_group'],
            $data['body'],
            $data['vcf_request_id'],
            $data['status'],
            $queuedOperation,
            $data['timestamp'],
        );
    }

    public function toArray(): array
    {
        $queuedOperation = $this->queuedOperation?->toArray();

        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'vin' => $this->vin,
            'http_method' => $this->httpMethod,
            'path' => $this->path,
            'capability' => $this->capability,
            'operation' => $this->operation,
            'operation_group' => $this->operationGroup,
            'body' => $this->body,
            'vcf_request_id' => $this->vcfRequestId,
            'status' => $this->status,
            'queued_operation' => $queuedOperation,
            'timestamp' => $this->timestamp,
        ];
    }
}
