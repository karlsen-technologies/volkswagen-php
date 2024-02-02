<?php

declare(strict_types=1);

namespace KarlsenTechnologies\Volkswagen\DataObjects\Vehicle;

class Vehicle
{
    public function __construct(
        public string $vin,
        public string $role,
        public string $userRoleStatus,
        public string $enrollmentStatus,
        public string $brandCode,
        public string $model,
        public string $nickname,
        public array $capabilities,
        public array $images,
        public array $coUsers,
        public string $devicePlatform,
        public array $tags,
    ) {}

    public static function fromArray(array $data): Vehicle
    {
        $capabilities = [];

        foreach ($data['capabilities'] as $capability) {
            $capabilities[] = VehicleCapability::fromArray($capability);
        }
        return new Vehicle(
            $data['vin'],
            $data['role'],
            $data['userRoleStatus'],
            $data['enrollmentStatus'],
            $data['brandCode'],
            $data['model'],
            $data['nickname'],
            $capabilities,
            $data['images'],
            $data['coUsers'],
            $data['devicePlatform'],
            $data['tags'],
        );
    }

    public function toArray(): array
    {
        return [
            'vin' => $this->vin,
            'role' => $this->role,
            'user_role_status' => $this->userRoleStatus,
            'enrollment_status' => $this->enrollmentStatus,
            'brand_code' => $this->brandCode,
            'model' => $this->model,
            'nickname' => $this->nickname,
            'capabilities' => $this->capabilities,
            'images' => $this->images,
            'co_users' => $this->coUsers,
            'device_platform' => $this->devicePlatform,
            'tags' => $this->tags,
        ];
    }
}
