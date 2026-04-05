# Query Optimization & Cache Management Guide

## Overview

This document outlines the performance optimization strategy implemented across all controllers in the IAS System. The system uses **eager loading and database optimization** combined with **strategic cache invalidation** to improve performance and reduce unnecessary database load.

## Architecture

### CacheableIndex Trait

Located at: `app/Traits/CacheableIndex.php`

The trait provides a simple, reliable interface for cache invalidation on controllers with create/update/delete operations:

```php
use App\Traits\CacheableIndex;

class YourController extends Controller {
    use CacheableIndex;
}
```

### Key Features

1. **Direct Query Performance**
   - Queries use eager loading with `.with()` to prevent N+1 problems
   - Eliminates serial database calls when fetching related data
   - Built-in Laravel pagination is optimized and reliable

2. **Automatic Cache Invalidation**
   - Cache is automatically cleared on `create`, `update`, and `delete` operations
   - Ensures data consistency without manual intervention
   - Uses file-based cache driver (no external dependencies)

3. **Search-Optimized**
   - Search queries are never cached (always fresh results)
   - Non-search queries benefit from HTTP caching headers
   - ILIKE operations are optimized with database indexes

## Implementation Pattern

### For Index Methods (Read-Only)

```php
public function index(Request $request)
{
    $search = $request->get('search');
    
    // Eager load relationships to prevent N+1 queries
    $records = Model::with('relationship')
        ->when($search, function ($query) use ($search) {
            // Search filters applied conditionally
            $query->where('column', 'ILIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(15);
    
    // Handle AJAX requests
    if ($request->ajax()) {
        return response()->json([
            'html' => view('partials.table', compact('records'))->render(),
            'pagination' => $records->appends($request->query())->links()->toHtml(),
            'total' => $records->total(),
            'search' => $search
        ]);
    }
    
    return view('records.index', compact('records', 'search'));
}
```

### For Mutating Methods (Create, Update, Delete)

```php
public function store(Request $request)
{
    $validated = $request->validate([/* rules */]);
    
    Model::create($validated);
    
    // Invalidate cache after data changes
    $this->forgetAllIndexCache(Model::class);
    
    return redirect()->route('model.index')
                     ->with('success', 'Created successfully!');
}

public function update(Request $request, $id)
{
    $model = Model::findOrFail($id);
    $model->update($validated);
    
    // Invalidate cache after data changes
    $this->forgetAllIndexCache(Model::class);
    
    return redirect()->route('model.index')
                     ->with('success', 'Updated successfully!');
}

public function destroy($id)
{
    $model = Model::findOrFail($id);
    $model->delete();
    
    // Invalidate cache after deletion
    $this->forgetAllIndexCache(Model::class);
    
    return back()->with('success', 'Deleted successfully!');
}
```

## Optimized Controllers

1. ✅ **StudentMedicalRecordClinicController** - Medical records with eager loading
2. ✅ **MedicineController** - Inventory management with search optimization
3. ✅ **ConsultationController** - Consultations with relationship loading
4. ✅ **MedicalClearanceController** - Clearances with status tracking
5. ✅ **HealthIncidentController** - Incidents with relationship optimization
6. ✅ **DashboardController** - Dashboard stats with direct queries
7. ✅ **AuthController** - Authentication with proper logging

## Performance Optimization Techniques

### 1. Eager Loading with `.with()`
```php
// ❌ Bad - N+1 query problem
$records = Model::all();
foreach ($records as $record) {
    echo $record->relationship->name; // 1 + N queries
}

// ✅ Good - Eager loading
$records = Model::with('relationship')->get(); // 2 queries total
```

### 2. Conditional Queries with `.when()`
```php
$records = Model::when($search, function ($query) use ($search) {
    $query->where('column', 'ILIKE', "%{$search}%");
})->paginate();
```

### 3. Eager Load with Constraints
```php
$records = Model::with([
    'relationship' => function ($query) {
        $query->orderBy('created_at', 'desc');
    }
])->paginate();
```

### 4. Database Indexes for ILIKE
Create indexes on frequently searched columns:
```sql
CREATE INDEX idx_model_name ON models (name);
CREATE INDEX idx_model_code ON models (student_id);
```

## Cache Management

### File-Based Cache Driver

The application uses Laravel's file-based cache driver (no Redis required):

```env
CACHE_STORE=file
SESSION_DRIVER=file
```

### Manual Cache Operations

```bash
# Clear all application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear specific cache tags
php artisan tinker
>>> Cache::tags(['index_StudentMedicalRecordClinic'])->flush()
```

### Cache Invalidation Strategy

Cache is invalidated automatically using tags:

```php
// In controller after data changes
$this->forgetAllIndexCache(Model::class);

// Internally uses:
Cache::tags(['index_' . strtolower('ClassName')])->flush();
```

## Performance Benefits

| Scenario | Benefit |
|----------|---------|
| First page load | Normal database query |
| Repeated page load (no search) | Benefits from HTTP caching headers |
| Search query | Fresh data (bypasses application cache) |
| List pagination | Native Laravel pagination optimized |
| Related data access | No N+1 queries with eager loading |

## Troubleshooting

### Seeing Stale Data?
1. Check that `forgetAllIndexCache()` is called after mutations
2. Verify cache is cleared: `php artisan cache:clear`
3. Ensure HTTP cache headers aren't too aggressive

### Database Queries Too Slow?
1. Add indexes to frequently searched columns
2. Use `with()` to prevent N+1 queries
3. Check database query logs: `DB::enableQueryLog()`
4. Use Laravel Debugbar for profiling

### Cache Not Working?
1. Verify cache driver is `file` in `.env`
2. Check `/storage/framework/cache/` has write permissions
3. Ensure `php artisan cache:clear` runs with no errors

## Best Practices

✅ **DO:**
- Use eager loading with `.with()` for all relationships
- Apply `forgetAllIndexCache()` in store, update, destroy
- Keep search queries fresh (no caching)
- Index frequently searched columns
- Use conditional queries with `.when()`

❌ **DON'T:**
- Cache Eloquent Collections or Paginator objects
- Use N+1 query patterns
- Cache search/filter results
- Forget to invalidate cache on data changes
- Over-complicate cache logic

## Related Files

- **Trait:** `app/Traits/CacheableIndex.php`
- **Config:** `config/cache.php`
- **Cache Driver Config:** `.env` (`CACHE_STORE` setting)
- **Controllers:** All in `app/Http/Controllers/`

## Future Improvements

1. **Query Logging** - Add detailed query logging for monitoring
2. **Database Profiling** - Use Laravel Horizon for queue monitoring
3. **Redis Caching** - Upgrade to Redis for distributed caching
4. **Query Optimization Tool** - Implement automatic slow query detection
5. **Cache Warming** - Pre-load frequently accessed data

## Example: Complete Controller Implementation

```php
<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Traits\CacheableIndex;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    use CacheableIndex;

    // Read-only - benefits from HTTP caching
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $medicines = Medicine::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('batch_number', 'ILIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('clinic.medicines.partials.table', 
                    compact('medicines'))->render(),
                'pagination' => $medicines->appends($request->query())->links()->toHtml(),
            ]);
        }

        return view('clinic.medicines.index', compact('medicines', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        Medicine::create($validated);
        $this->forgetAllIndexCache(Medicine::class); // ← Invalidate cache

        return redirect()->route('clinic.medicines.index')
                         ->with('success', 'Medicine added!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        $this->forgetAllIndexCache(Medicine::class); // ← Invalidate cache

        return back()->with('success', 'Medicine deleted!');
    }
}
```

## Summary

The IAS System uses a **proven, reliable approach** to performance optimization:
- **Eager loading** prevents N+1 database queries
- **Strategic invalidation** keeps data fresh
- **File-based caching** requires no external dependencies
- **Simple, maintainable code** that's easy to debug
- **HTTP caching** handles repeated requests efficiently

This approach balances performance with reliability, avoiding complex caching mechanisms that create bugs and maintenance headaches.

---

**Last Updated:** April 6, 2026  
**Status:** ✅ Production Ready  
**Optimization Strategy:** Database-First with Selective Invalidation
