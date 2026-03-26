<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RobuxDiscountRule;
use Illuminate\Http\Request;

class RobuxDiscountRuleController extends Controller
{
    public function index(Request $request)
    {
        $purchaseMethod = $request->get('method', 'gamepass');
        
        $rules = RobuxDiscountRule::where('purchase_method', $purchaseMethod)
            ->orderBy('sort_order')
            ->orderBy('min_amount', 'desc')
            ->get();
        
        // Detect conflicts: rules with same priority that overlap
        $conflicts = [];
        foreach ($rules as $rule) {
            if (!$rule->is_active) continue;
            
            $samePriority = $rules->where('sort_order', $rule->sort_order)
                ->where('id', '!=', $rule->id)
                ->where('is_active', true);
            
            foreach ($samePriority as $otherRule) {
                if (self::rulesOverlap($rule, $otherRule)) {
                    // Check if this conflict pair is already added
                    $exists = false;
                    foreach ($conflicts as $conflict) {
                        if (($conflict['rule1'] == $rule->id && $conflict['rule2'] == $otherRule->id) ||
                            ($conflict['rule1'] == $otherRule->id && $conflict['rule2'] == $rule->id)) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) {
                        $conflicts[] = [
                            'rule1' => $rule->id,
                            'rule2' => $otherRule->id,
                            'priority' => $rule->sort_order,
                        ];
                    }
                }
            }
        }
            
        return view('admin.robux-discount-rules', compact('rules', 'purchaseMethod', 'conflicts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_method' => 'required|in:gamepass,group',
            'min_amount' => 'nullable|integer|min:0',
            'max_amount' => 'nullable|integer|min:0|gte:min_amount',
            'discount_method' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $minAmount = $request->min_amount;
        $maxAmount = $request->max_amount;
        $sortOrder = $request->sort_order ?? 0;
        $purchaseMethod = $request->purchase_method;

        // Validate exact amount or range
        if ($minAmount !== null && $maxAmount !== null) {
            if ($minAmount === $maxAmount) {
                // Exact amount - check for duplicates
                $exists = RobuxDiscountRule::where('purchase_method', $purchaseMethod)
                    ->where('min_amount', $minAmount)
                    ->where('max_amount', $maxAmount)
                    ->exists();
                    
                if ($exists) {
                    return back()->withErrors(['min_amount' => 'Rule untuk jumlah ini sudah ada.'])->withInput();
                }
            }
        }

        // Check for potential conflicts with same priority
        $conflictingRules = RobuxDiscountRule::where('purchase_method', $purchaseMethod)
            ->where('sort_order', $sortOrder)
            ->where('is_active', true)
            ->get()
            ->filter(function($rule) use ($minAmount, $maxAmount) {
                // Check if ranges overlap
                if ($minAmount === null) return false;
                
                $ruleMin = $rule->min_amount;
                $ruleMax = $rule->max_amount;
                
                // If new rule is exact amount
                if ($minAmount === $maxAmount) {
                    // Check if exact amount falls in existing rule's range
                    if ($ruleMin !== null && $ruleMax !== null && $ruleMin !== $ruleMax) {
                        // Range: check if exact amount is in range
                        return $minAmount >= $ruleMin && $minAmount <= $ruleMax;
                    } elseif ($ruleMin !== null && $ruleMax === null) {
                        // Minimum only: check if exact amount >= min
                        return $minAmount >= $ruleMin;
                    } elseif ($ruleMin === $ruleMax && $ruleMin === $minAmount) {
                        // Same exact amount
                        return true;
                    }
                } 
                // If new rule is range
                elseif ($maxAmount !== null) {
                    // Check overlap with existing range
                    if ($ruleMin !== null && $ruleMax !== null && $ruleMin !== $ruleMax) {
                        // Both are ranges: check overlap
                        return !($maxAmount < $ruleMin || $minAmount > $ruleMax);
                    } elseif ($ruleMin !== null && $ruleMax === null) {
                        // Existing is minimum only: new range overlaps if minAmount <= ruleMin or maxAmount >= ruleMin
                        return $maxAmount >= $ruleMin;
                    } elseif ($ruleMin === $ruleMax && $ruleMin !== null) {
                        // Existing is exact: check if exact is in new range
                        return $ruleMin >= $minAmount && $ruleMin <= $maxAmount;
                    }
                }
                // If new rule is minimum only (maxAmount is null)
                else {
                    if ($ruleMin !== null && $ruleMax !== null && $ruleMin !== $ruleMax) {
                        // Existing is range: new minimum overlaps if minAmount <= ruleMax
                        return $minAmount <= $ruleMax;
                    } elseif ($ruleMin !== null && $ruleMax === null) {
                        // Both are minimum only: always overlap if both active
                        return true;
                    } elseif ($ruleMin === $ruleMax && $ruleMin !== null) {
                        // Existing is exact: new minimum overlaps if minAmount <= ruleMin
                        return $minAmount <= $ruleMin;
                    }
                }
                
                return false;
            });

        if ($conflictingRules->isNotEmpty()) {
            $conflictDetails = $conflictingRules->map(function($rule) {
                return $rule->description . " (Diskon: " . ($rule->discount_method === 'percentage' ? $rule->discount_value . '%' : 'Rp ' . number_format((float)$rule->discount_value, 0, ',', '.')) . ")";
            })->implode(', ');

            return back()
                ->withErrors(['sort_order' => "⚠️ PERINGATAN: Priority {$sortOrder} sudah digunakan oleh rule lain yang OVERLAP: {$conflictDetails}. Jika ada overlap, sistem akan memilih rule dengan min_amount lebih besar. Disarankan gunakan priority berbeda."])
                ->withInput();
        }

        RobuxDiscountRule::create([
            'purchase_method' => $purchaseMethod,
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
            'discount_method' => $request->discount_method,
            'discount_value' => $request->discount_value,
            'sort_order' => $sortOrder,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.robux-discount-rules', ['method' => $purchaseMethod])
            ->with('success', 'Discount rule berhasil dibuat.');
    }

    public function update(Request $request, RobuxDiscountRule $robuxDiscountRule)
    {
        $request->validate([
            'purchase_method' => 'required|in:gamepass,group',
            'min_amount' => 'nullable|integer|min:0',
            'max_amount' => 'nullable|integer|min:0|gte:min_amount',
            'discount_method' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $minAmount = $request->min_amount;
        $maxAmount = $request->max_amount;
        $sortOrder = $request->sort_order ?? 0;
        $purchaseMethod = $request->purchase_method;

        // Validate exact amount or range (check duplicates excluding current)
        if ($minAmount !== null && $maxAmount !== null) {
            if ($minAmount === $maxAmount) {
                $exists = RobuxDiscountRule::where('purchase_method', $purchaseMethod)
                    ->where('min_amount', $minAmount)
                    ->where('max_amount', $maxAmount)
                    ->where('id', '!=', $robuxDiscountRule->id)
                    ->exists();
                    
                if ($exists) {
                    return back()->withErrors(['min_amount' => 'Rule untuk jumlah ini sudah ada.'])->withInput();
                }
            }
        }

        // Check for potential conflicts with same priority (excluding current rule)
        $conflictingRules = RobuxDiscountRule::where('purchase_method', $purchaseMethod)
            ->where('sort_order', $sortOrder)
            ->where('id', '!=', $robuxDiscountRule->id)
            ->where('is_active', true)
            ->get()
            ->filter(function($rule) use ($minAmount, $maxAmount) {
                // Same logic as store method
                if ($minAmount === null) return false;
                
                $ruleMin = $rule->min_amount;
                $ruleMax = $rule->max_amount;
                
                if ($minAmount === $maxAmount) {
                    if ($ruleMin !== null && $ruleMax !== null && $ruleMin !== $ruleMax) {
                        return $minAmount >= $ruleMin && $minAmount <= $ruleMax;
                    } elseif ($ruleMin !== null && $ruleMax === null) {
                        return $minAmount >= $ruleMin;
                    } elseif ($ruleMin === $ruleMax && $ruleMin === $minAmount) {
                        return true;
                    }
                } elseif ($maxAmount !== null) {
                    if ($ruleMin !== null && $ruleMax !== null && $ruleMin !== $ruleMax) {
                        return !($maxAmount < $ruleMin || $minAmount > $ruleMax);
                    } elseif ($ruleMin !== null && $ruleMax === null) {
                        return $maxAmount >= $ruleMin;
                    } elseif ($ruleMin === $ruleMax && $ruleMin !== null) {
                        return $ruleMin >= $minAmount && $ruleMin <= $maxAmount;
                    }
                } else {
                    if ($ruleMin !== null && $ruleMax !== null && $ruleMin !== $ruleMax) {
                        return $minAmount <= $ruleMax;
                    } elseif ($ruleMin !== null && $ruleMax === null) {
                        return true;
                    } elseif ($ruleMin === $ruleMax && $ruleMin !== null) {
                        return $minAmount <= $ruleMin;
                    }
                }
                
                return false;
            });

        if ($conflictingRules->isNotEmpty()) {
            $conflictDetails = $conflictingRules->map(function($rule) {
                return $rule->description . " (Diskon: " . ($rule->discount_method === 'percentage' ? $rule->discount_value . '%' : 'Rp ' . number_format((float)$rule->discount_value, 0, ',', '.')) . ")";
            })->implode(', ');

            return back()
                ->withErrors(['sort_order' => "⚠️ PERINGATAN: Priority {$sortOrder} sudah digunakan oleh rule lain yang OVERLAP: {$conflictDetails}. Jika ada overlap, sistem akan memilih rule dengan min_amount lebih besar. Disarankan gunakan priority berbeda."])
                ->withInput();
        }

        $robuxDiscountRule->update([
            'purchase_method' => $purchaseMethod,
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
            'discount_method' => $request->discount_method,
            'discount_value' => $request->discount_value,
            'sort_order' => $sortOrder,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.robux-discount-rules', ['method' => $purchaseMethod])
            ->with('success', 'Discount rule berhasil diperbarui.');
    }

    public function destroy(RobuxDiscountRule $robuxDiscountRule)
    {
        $method = $robuxDiscountRule->purchase_method;
        $robuxDiscountRule->delete();
        
        return redirect()->route('admin.robux-discount-rules', ['method' => $method])
            ->with('success', 'Discount rule berhasil dihapus.');
    }

    public function toggleStatus(RobuxDiscountRule $robuxDiscountRule)
    {
        $robuxDiscountRule->update(['is_active' => !$robuxDiscountRule->is_active]);
        $status = $robuxDiscountRule->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.robux-discount-rules', ['method' => $robuxDiscountRule->purchase_method])
            ->with('success', "Rule {$status}.");
    }

    /**
     * Check if two rules overlap
     */
    private static function rulesOverlap($rule1, $rule2): bool
    {
        $min1 = $rule1->min_amount;
        $max1 = $rule1->max_amount;
        $min2 = $rule2->min_amount;
        $max2 = $rule2->max_amount;

        // Rule 1: Exact amount
        if ($min1 === $max1 && $min1 !== null) {
            if ($min2 === $max2 && $min2 !== null) {
                return $min1 === $min2; // Same exact amount
            } elseif ($max2 === null && $min2 !== null) {
                return $min1 >= $min2; // Exact in minimum
            } elseif ($min2 !== null && $max2 !== null && $min2 !== $max2) {
                return $min1 >= $min2 && $min1 <= $max2; // Exact in range
            }
        }
        // Rule 1: Range
        elseif ($max1 !== null && $min1 !== null && $min1 !== $max1) {
            if ($min2 === $max2 && $min2 !== null) {
                return $min2 >= $min1 && $min2 <= $max1; // Exact in range
            } elseif ($max2 === null && $min2 !== null) {
                return $max1 >= $min2; // Range overlaps minimum
            } elseif ($min2 !== null && $max2 !== null && $min2 !== $max2) {
                return !($max1 < $min2 || $min1 > $max2); // Ranges overlap
            }
        }
        // Rule 1: Minimum only
        elseif ($max1 === null && $min1 !== null) {
            if ($min2 === $max2 && $min2 !== null) {
                return $min2 >= $min1; // Exact >= minimum
            } elseif ($max2 === null && $min2 !== null) {
                return true; // Both minimum, always overlap if both active
            } elseif ($min2 !== null && $max2 !== null && $min2 !== $max2) {
                return $min1 <= $max2; // Minimum overlaps range
            }
        }

        return false;
    }
}
