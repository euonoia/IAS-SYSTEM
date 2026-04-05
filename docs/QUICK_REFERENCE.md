# Performance Optimization - Quick Reference Guide

## For Developers

### Quick Start: Adding to a New Controller

#### Step 1: Import the Trait
```php
<?php

namespace App\Http\Controllers;

use App\Models\YourModel;
use App\Traits\CacheableIndex;  // ← Add this
use Illuminate\Http\Request;

class YourController extends Controller
{
    use CacheableIndex;  // ← Add this
    
    // ... rest of controller
}
```

#### Step 2: Use Eager Loading in Index
```php
public function index(Request $request)
{
    $search = $request->get('search');
    
    // Use with() for relationships
    $items = YourModel::with('relationship1', 'relationship2')  // ← Eager load
        ->when($search, function ($query) use ($search) {
            $query->where('column', 'ILIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(15);
    
    return view('items.index', compact('items', 'search'));
}
```

#### Step 3: Invalidate Cache on Mutations
```php
public function store(Request $request)
{
    $validated = $request->validate([/* rules */]);
    
    YourModel::create($validated);
    
    // Invalidate cache
    $this->forgetAllIndexCache(YourModel::class);  // ← Add this
    
    return redirect()->route('your.index')
                     ->with('success', 'Created!');
}

public function update(Request $request, $id)
{
    $model = YourModel::findOrFail($id);
    $model->update($validated);
    
    // Invalidate cache
    $this->forgetAllIndexCache(YourModel::class);  // ← Add this
    
    return redirect()->route('your.index')
                     ->with('success', 'Updated!');
}

public function destroy($id)
{
    $model = YourModel::findOrFail($id);
    $model->delete();
    
    // Invalidate cache
    $this->forgetAllIndexCache(YourModel::class);  // ← Add this
    
    return back()->with('success', 'Deleted!');
}
```

---

## Common Patterns

### Pattern: Basic Index with Search
```php
public function index(Request $request)
{
    $search = $request->get('search');
    
    $items = Item::with('relationship')
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'ILIKE', "%{$search}%")
              ->orWhere('code', 'ILIKE', "%{$search}%");
        })
        ->latest()
        ->paginate(15);
    
    return view('items.index', compact('items', 'search'));
}
```

### Pattern: Multiple Eager Loads
```php
$items = Item::with([
    'user',
    'category',
    'tags' => function ($query) {
        $query->orderBy('name');
    }
])->paginate();
```

### Pattern: AJAX Response
```php
public function index(Request $request)
{
    $search = $request->get('search');
    
    $items = Item::when($search, ...)
        ->latest()
        ->paginate(15);
    
    // Handle AJAX
    if ($request->ajax()) {
        return response()->json([
            'html' => view('items.partials.table', 
                compact('items'))->render(),
            'pagination' => $items->appends($request->query())
                ->links()->toHtml(),
        ]);
    }
    
    return view('items.index', compact('items', 'search'));
}
```

### Pattern: With Relationships and Filters
```php
$items = Item::with('category')
    ->when($category_id, fn($q) => $q->where('category_id', $category_id))
    ->when($search, fn($q) => $q->where('name', 'ILIKE', "%{$search}%"))
    ->latest()
    ->paginate(20);
```

---

## Cache Invalidation Checklist

Before committing, ensure all mutation methods have cache invalidation:

```php
✅ public function store() {
    // ... create ...
    $this->forgetAllIndexCache(Model::class);
}

✅ public function update() {
    // ... update ...
    $this->forgetAllIndexCache(Model::class);
}

✅ public function destroy() {
    // ... delete ...
    $this->forgetAllIndexCache(Model::class);
}

✅ public function approve() {
    // ... status change ...
    $this->forgetAllIndexCache(Model::class);
}
```

---

## Debugging Tips

### Check Query Count
```bash
php artisan tinker
>>> DB::enableQueryLog()
>>> Model::with('relation')->paginate()
>>> dd(DB::getQueryLog())
```

### Check Relationships
```bash
php artisan tinker
>>> Model::with('relation')->first()->toArray()
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

---

## What NOT to Do

❌ **Don't cache Eloquent objects**
```php
// ❌ BAD - Will cause serialization errors
$items = Cache::remember($key, $ttl, function () {
    return Model::with('relation')->get(); // Collections can't cache reliably
});
```

❌ **Don't use N+1 pattern**
```php
// ❌ BAD - 1 + N queries
$items = Model::all();
foreach ($items as $item) {
    echo $item->relation->name;
}
```

❌ **Don't forget cache invalidation**
```php
// ❌ BAD - Data changes but cache isn't cleared
public function store(Request $request) {
    Model::create($validated);
    return redirect(); // Where's forgetAllIndexCache()?
}
```

❌ **Don't over-complicate**
```php
// ❌ BAD - Too complex, hard to maintain
if ($shouldCache) {
    $data = rememberIndex()...
} else if ($search) {
    // ...different logic...
}
```

---

## Trait Reference

### Available Methods

```php
// In your controller, use CacheableIndex trait
use App\Traits\CacheableIndex;

class YourController extends Controller {
    use CacheableIndex;
    
    // Method 1: Invalidate all index cache for a model
    protected function forgetAllIndexCache(string $modelClass): void
    
    // Method 2: Get cache tag for a model
    protected function getCacheTag(string $modelClass): string
}
```

### Usage Example
```php
// In store/update/destroy methods
$this->forgetAllIndexCache(YourModel::class);

// Internally calls:
Cache::tags(['index_yourmodel'])->flush();
```

---

## File Locations

```
Project Root
├── app/
│   ├── Traits/
│   │   └── CacheableIndex.php          ← Import this
│   └── Http/Controllers/
│       ├── StudentMedicalRecordClinicController.php  ← Example
│       ├── MedicineController.php                    ← Example
│       └── YourController.php                        ← Add here
├── docs/
│   ├── CACHE_OPTIMIZATION.md           ← Detailed guide
│   ├── IMPLEMENTATION_SUMMARY.md       ← Technical summary
│   └── QUICK_REFERENCE.md              ← This file
└── .env                                ← Check CACHE_STORE
```

---

## Configuration Check

Verify these settings in `.env`:
```env
CACHE_STORE=file          ✅ Correct
SESSION_DRIVER=file       ✅ Correct
CACHE_DRIVER=redis        ❌ Needs change to "file"
```

---

## Common Errors & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| "Unknown column in where clause" | Typo in search query | Check column names match database |
| "Call to undefined method" | Missing relationship | Define in Model |
| "Stale data shown" | Cache not invalidated | Add forgetAllIndexCache() |
| "N+1 queries" | Missing with() | Add eager loading |
| "Serialization error" | Caching objects | Use direct queries |

---

## Performance Checklist

Before deploying new controller:

- [ ] Index method uses `with()` for all relationships
- [ ] Search uses `.when()` helper
- [ ] Store method calls `forgetAllIndexCache()`
- [ ] Update method calls `forgetAllIndexCache()`
- [ ] Destroy method calls `forgetAllIndexCache()`
- [ ] Relationships defined in Model
- [ ] Search columns indexed in database
- [ ] No query logging left in code
- [ ] AJAX responses formatted correctly
- [ ] Error messages user-friendly

---

## Example Complete Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Traits\CacheableIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    use CacheableIndex;

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $medicines = Medicine::query()
            ->when($search, fn($q) => $q->where('name', 'ILIKE', "%{$search}%")
                                       ->orWhere('batch_number', 'ILIKE', "%{$search}%"))
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('clinic.medicines.partials.table', 
                    compact('medicines'))->render(),
                'pagination' => $medicines->appends($request->query())
                    ->links()->toHtml(),
            ]);
        }

        return view('clinic.medicines.index', compact('medicines', 'search'));
    }

    public function create()
    {
        return view('clinic.medicines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $validated['batch_number'] = 'MED-' . time();
        Medicine::create($validated);
        
        $this->forgetAllIndexCache(Medicine::class);

        return redirect()->route('clinic.medicines.index')
                         ->with('success', 'Medicine added successfully!');
    }

    public function edit(Medicine $medicine)
    {
        return view('clinic.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $medicine->update($validated);
        
        $this->forgetAllIndexCache(Medicine::class);

        return redirect()->route('clinic.medicines.index')
                         ->with('success', 'Medicine updated successfully!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        
        $this->forgetAllIndexCache(Medicine::class);

        return back()->with('success', 'Medicine removed from inventory.');
    }
}
```

---

## Questions?

Refer to:
- Technical details: `docs/CACHE_OPTIMIZATION.md`
- Implementation approach: `docs/IMPLEMENTATION_SUMMARY.md`
- Code examples: `app/Http/Controllers/MedicineController.php`
- Trait source: `app/Traits/CacheableIndex.php`

---

**Last Updated:** April 6, 2026  
**Version:** 1.0  
**Status:** Ready to Use
