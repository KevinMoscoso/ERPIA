<?php

declare(strict_types=1);

namespace Erpia\Model;

class Producto
{
    public int $id = 0;
    public string $nombre = '';
    public string $descripcion = '';
    public float $precio = 0.0;
    public int $stock = 0;
    public string $created_at = '';

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->loadFromArray($data);
        }
    }

    public function loadFromArray(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }

        if (isset($data['nombre'])) {
            $this->nombre = (string) $data['nombre'];
        }

        if (isset($data['descripcion'])) {
            $this->descripcion = (string) $data['descripcion'];
        }

        if (isset($data['precio'])) {
            $this->precio = (float) $data['precio'];
        }

        if (isset($data['stock'])) {
            $this->stock = (int) $data['stock'];
        }

        if (isset($data['created_at'])) {
            $this->created_at = (string) $data['created_at'];
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'created_at' => $this->created_at,
        ];
    }
}
