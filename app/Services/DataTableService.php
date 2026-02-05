<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * DataTableService - Handles all table logic with simple arrays
 * 
 * Usage:
 * $data = app(DataTableService::class)->make(
 *     User::where('role', 'user'),
 *     [
 *         'columns' => [
 *             ['key' => 'name', 'label' => 'Nama', 'sortable' => true, 'type' => 'text'],
 *             ['key' => 'email', 'label' => 'Email', 'sortable' => true],
 *             ['key' => 'avatar', 'label' => 'Foto', 'type' => 'image'],
 *             ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
 *         ],
 *         'searchable' => ['name', 'email', 'phone'],
 *         'sortable' => ['name', 'email', 'created_at'],
 *         'actions' => ['edit', 'delete'],
 *         'route' => 'admin.users',
 *         'title' => 'User Management',
 *         'entity' => 'user',
 *         'filter' => [  // Optional custom filter
 *             'key' => 'status',      // URL param name
 *             'column' => 'status',   // DB column name  
 *             'options' => [
 *                 '' => 'Semua Status',
 *                 'pending' => 'Menunggu',
 *                 'active' => 'Aktif',
 *             ]
 *         ],
 *         'showCreate' => true,  // Show/hide create button
 *     ],
 *     $request
 * );
 */
class DataTableService
{
    /**
     * Build table data from query and config
     */
    public function make($query, array $config, Request $request): array
    {
        // Pagination settings
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        // Sorting
        $sortKey = $request->input('sort', $config['defaultSort'] ?? 'created_at');
        $allowedSorts = $config['sortable'] ?? ['created_at'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = $config['defaultSort'] ?? 'created_at';
        }
        $sortDir = strtolower($request->input('dir', $config['defaultDir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';

        // Search
        $searchTerm = $request->input('search', '');
        $searchableFields = $config['searchable'] ?? [];
        
        if (!empty($searchTerm) && !empty($searchableFields)) {
            $query->where(function ($q) use ($searchTerm, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $q->orWhere($field, 'like', "%{$searchTerm}%");
                }
            });
        }

        // Custom filter (status, category, type, etc.)
        $filterValue = '';
        $filterKey = '';
        if (isset($config['filter'])) {
            $filterKey = $config['filter']['key'] ?? 'status';
            $filterValue = $request->input($filterKey, '');
            $filterColumn = $config['filter']['column'] ?? $filterKey;
            
            if (!empty($filterValue)) {
                // Check if it's an is_active style filter
                if ($filterColumn === 'is_active') {
                    $query->where($filterColumn, $filterValue === 'active' ? 1 : 0);
                } else {
                    $query->where($filterColumn, $filterValue);
                }
            }
        } elseif ($request->filled('status')) {
            // Default is_active filter for backward compatibility
            $filterValue = $request->input('status');
            $filterKey = 'status';
            $query->where('is_active', $filterValue === 'active' ? 1 : 0);
        }

        // Get sort column (handle dot notation for joined tables)
        $sortColumn = $config['sortColumns'][$sortKey] ?? $sortKey;

        // Execute query with pagination
        $paginator = $query->orderBy($sortColumn, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        // Apply transformer if provided
        if (isset($config['transformer']) && is_callable($config['transformer'])) {
            $paginator->getCollection()->transform($config['transformer']);
        }

        return [
            // Data
            'items' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'perPage' => $paginator->perPage(),
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'from' => $paginator->firstItem() ?? 0,
                'to' => $paginator->lastItem() ?? 0,
            ],
            
            // Config for view
            'columns' => $config['columns'] ?? [],
            'actions' => $config['actions'] ?? ['edit', 'delete'],
            'route' => $config['route'] ?? '',
            'routeParam' => $config['routeParam'] ?? null, // e.g., 'id', 'program', 'article' - auto-detected if not specified
            'title' => $config['title'] ?? 'Data Management',
            'description' => $config['description'] ?? null, // Optional description text below title
            'entity' => $config['entity'] ?? 'item',
            'createLabel' => $config['createLabel'] ?? 'Tambah ' . ucfirst($config['entity'] ?? 'Item'),
            'showCreate' => $config['showCreate'] ?? true,
            'headerAction' => $config['headerAction'] ?? null, // Custom header action button
            'bulkActions' => $config['bulkActions'] ?? null, // Bulk action configuration
            
            // Filter config
            'filter' => $config['filter'] ?? [
                'key' => 'status',
                'column' => 'is_active',
                'options' => [
                    '' => 'Semua Status',
                    'active' => 'Aktif',
                    'inactive' => 'Tidak Aktif',
                ]
            ],
            'showFilter' => $config['showFilter'] ?? true,
            
            // Badge classes for custom styling
            'badgeClasses' => $config['badgeClasses'] ?? [],
            
            // Current state
            'search' => $searchTerm,
            'filterValue' => $filterValue,
            'filterKey' => $filterKey ?: 'status',
            'sortKey' => $sortKey,
            'sortDir' => $sortDir,
            
            // Search placeholder
            'searchPlaceholder' => $config['searchPlaceholder'] ?? 'Cari...',
        ];
    }

    /**
     * Return JSON response for AJAX requests
     */
    public function json($query, array $config, Request $request): array
    {
        $data = $this->make($query, $config, $request);
        
        return [
            'data' => $data['items'],
            'total' => $data['pagination']['total'],
            'per_page' => $data['pagination']['perPage'],
            'current_page' => $data['pagination']['currentPage'],
            'last_page' => $data['pagination']['lastPage'],
            'from' => $data['pagination']['from'],
            'to' => $data['pagination']['to'],
        ];
    }
}
