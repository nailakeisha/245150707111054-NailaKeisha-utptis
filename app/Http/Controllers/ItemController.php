<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Items', description: 'Endpoint untuk manajemen barang')]
class ItemController extends Controller
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    #[OA\Get(
        path: '/api/items',
        summary: 'Tampilkan semua barang',
        tags: ['Items'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil mengambil semua data barang',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Berhasil mengambil semua data barang'),
                        new OA\Property(property: 'total', type: 'integer', example: 2),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'nama_barang', type: 'string', example: 'Laptop Asus'),
                                new OA\Property(property: 'harga', type: 'integer', example: 8500000),
                                new OA\Property(property: 'stok', type: 'integer', example: 10),
                                new OA\Property(property: 'deskripsi', type: 'string', example: 'Laptop gaming'),
                                new OA\Property(property: 'created_at', type: 'string', example: '2026-04-19 10:00:00'),
                                new OA\Property(property: 'updated_at', type: 'string', example: '2026-04-19 10:00:00'),
                            ]
                        )),
                    ]
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $items = $this->itemService->getAll();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil semua data barang',
            'data'    => $items,
            'total'   => count($items),
        ], 200);
    }

    #[OA\Get(
        path: '/api/items/{id}',
        summary: 'Tampilkan barang berdasarkan ID',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil mengambil data barang',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Berhasil mengambil data barang'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Item tidak ditemukan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Item dengan ID 99 tidak Ditemukan'),
                    ]
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $item = $this->itemService->findById($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data barang',
            'data'    => $item,
        ], 200);
    }

    #[OA\Post(
        path: '/api/items',
        summary: 'Tambah barang baru',
        tags: ['Items'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nama_barang', 'harga'],
                properties: [
                    new OA\Property(property: 'nama_barang', type: 'string', example: 'Laptop Asus'),
                    new OA\Property(property: 'harga', type: 'integer', example: 8500000),
                    new OA\Property(property: 'stok', type: 'integer', example: 10),
                    new OA\Property(property: 'deskripsi', type: 'string', example: 'Laptop gaming terjangkau'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Barang berhasil ditambahkan'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|min:2|max:100',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'sometimes|integer|min:0',
            'deskripsi'   => 'sometimes|nullable|string|max:500',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'nama_barang.min'      => 'Nama barang minimal 2 karakter',
            'nama_barang.max'      => 'Nama barang maksimal 100 karakter',
            'harga.required'       => 'Harga wajib diisi',
            'harga.integer'        => 'Harga harus berupa angka',
            'harga.min'            => 'Harga tidak boleh negatif',
            'stok.integer'         => 'Stok harus berupa angka',
            'stok.min'             => 'Stok tidak boleh negatif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item = $this->itemService->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data'    => $item,
        ], 201);
    }

    #[OA\Put(
        path: '/api/items/{id}',
        summary: 'Update seluruh data barang',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nama_barang', 'harga', 'stok'],
                properties: [
                    new OA\Property(property: 'nama_barang', type: 'string', example: 'Laptop Asus Pro'),
                    new OA\Property(property: 'harga', type: 'integer', example: 9000000),
                    new OA\Property(property: 'stok', type: 'integer', example: 5),
                    new OA\Property(property: 'deskripsi', type: 'string', example: 'Versi terbaru'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Barang berhasil diupdate sepenuhnya'),
            new OA\Response(response: 404, description: 'Item tidak ditemukan'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->itemService->findById($id)) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|min:2|max:100',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string|max:500',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'harga.required'       => 'Harga wajib diisi',
            'stok.required'        => 'Stok wajib diisi untuk update penuh (PUT)',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item = $this->itemService->update($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate sepenuhnya',
            'data'    => $item,
        ], 200);
    }

    #[OA\Patch(
        path: '/api/items/{id}',
        summary: 'Update sebagian data barang',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nama_barang', type: 'string', example: 'Laptop Baru'),
                    new OA\Property(property: 'harga', type: 'integer', example: 7500000),
                    new OA\Property(property: 'stok', type: 'integer', example: 3),
                    new OA\Property(property: 'deskripsi', type: 'string', example: 'Update deskripsi'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Barang berhasil diupdate sebagian'),
            new OA\Response(response: 404, description: 'Item tidak ditemukan'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function patch(Request $request, int $id): JsonResponse
    {
        if (!$this->itemService->findById($id)) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        if (!$request->hasAny(['nama_barang', 'harga', 'stok', 'deskripsi'])) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal satu field harus diisi untuk update parsial',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'sometimes|string|min:2|max:100',
            'harga'       => 'sometimes|integer|min:0',
            'stok'        => 'sometimes|integer|min:0',
            'deskripsi'   => 'sometimes|nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item = $this->itemService->patch($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate sebagian',
            'data'    => $item,
        ], 200);
    }

    #[OA\Delete(
        path: '/api/items/{id}',
        summary: 'Hapus barang',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Barang berhasil dihapus'),
            new OA\Response(response: 404, description: 'Item tidak ditemukan'),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->itemService->delete($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID {$id} tidak Ditemukan",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Barang dengan ID {$id} berhasil dihapus",
        ], 200);
    }
}