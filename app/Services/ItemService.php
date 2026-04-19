<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ItemService
{
    protected string $filePath = 'data/items.json';

    
    public function getAll(): array
    {
        if (!Storage::exists($this->filePath)) {
            Storage::put($this->filePath, json_encode([]));
        }

        $content = Storage::get($this->filePath);
        return json_decode($content, true) ?? [];
    }

   
    private function saveAll(array $items): void
    {
        Storage::put($this->filePath, json_encode(array_values($items), JSON_PRETTY_PRINT));
    }

    public function findById(int $id): ?array
    {
        $items = $this->getAll();
        foreach ($items as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }

  
    public function create(array $data): array
    {
        $items = $this->getAll();

        $newId = count($items) > 0
            ? max(array_column($items, 'id')) + 1
            : 1;

        $newItem = [
            'id'         => $newId,
            'nama_barang'=> $data['nama_barang'],
            'harga'      => (int) $data['harga'],
            'stok'       => isset($data['stok']) ? (int) $data['stok'] : 0,
            'deskripsi'  => $data['deskripsi'] ?? null,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];

        $items[] = $newItem;
        $this->saveAll($items);

        return $newItem;
    }

   
    public function update(int $id, array $data): ?array
    {
        $items = $this->getAll();

        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item['nama_barang'] = $data['nama_barang'];
                $item['harga']       = (int) $data['harga'];
                $item['stok']        = (int) $data['stok'];
                $item['deskripsi']   = $data['deskripsi'] ?? null;
                $item['updated_at']  = now()->toDateTimeString();
                $this->saveAll($items);
                return $item;
            }
        }

        return null;
    }

    
    public function patch(int $id, array $data): ?array
    {
        $items = $this->getAll();

        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                if (isset($data['nama_barang'])) $item['nama_barang'] = $data['nama_barang'];
                if (isset($data['harga']))       $item['harga']       = (int) $data['harga'];
                if (isset($data['stok']))        $item['stok']        = (int) $data['stok'];
                if (array_key_exists('deskripsi', $data)) $item['deskripsi'] = $data['deskripsi'];
                $item['updated_at'] = now()->toDateTimeString();
                $this->saveAll($items);
                return $item;
            }
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $items = $this->getAll();
        $filtered = array_filter($items, fn($item) => $item['id'] !== $id);

        if (count($filtered) === count($items)) {
            return false; 
        }

        $this->saveAll($filtered);
        return true;
    }
}