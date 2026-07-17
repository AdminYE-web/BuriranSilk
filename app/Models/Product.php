<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_code',
        'translation_key',
        'category_id',
        'material_id',
        'product_type',
        'product_name',
        'language',
        'description',
        'is_antivirus_included',
        'is_active',
        'product_recomend',
        'product_premium',
        'can_upload_artwork',
        'artwork_required',
        'allow_no_artwork',
        'allow_text_print',
        'allow_font_select',
        'allow_template_select',
        'product_recomend_menu',
    ];

    public function priceTiers()
    {
        return $this->hasMany(ProductPriceTier::class, 'product_id', 'product_id')
            ->where('is_active', 1)
            ->orderBy('min_qty');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'product_id')
            ->where('image_type', 'main')
            ->orderBy('is_main', 'desc')
            ->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function detailImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id')
            ->where('image_type', 'detail')
            ->orderBy('sort_order');
    }

    public function detail()
    {
        return $this->hasOne(ProductDetail::class, 'product_id', 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id')
            ->where('image_type', 'gallery')
            ->orderBy('sort_order');
    }

    public function optionAssignments()
    {
        return $this->hasMany(ProductOptionAssignment::class, 'product_id', 'product_id');
    }

    public function assignedOptions()
    {
        return $this->belongsToMany(
            ProductOption::class,
            'product_option_assignments',
            'product_id',
            'option_id'
        )
            ->withPivot([
                'sort_order',
                'is_default',
                'is_active',
                'qty_rule_type',
                'min_qty',
                'max_qty',
                'exact_qty',
            ])
            ->withTimestamps();
    }

    public function priceRules()
    {
        return $this->hasMany(ProductPriceRule::class, 'product_id', 'product_id')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('rule_id');
    }

    public function artworkTemplates()
    {
        return $this->hasMany(ProductArtworkTemplate::class, 'product_id', 'product_id')
            ->where('is_active', 1)
            ->orderBy('sort_order');
    }

    public function options()
    {
        return $this->belongsToMany(
            ProductOption::class,
            'product_option_assignments',
            'product_id',
            'option_id',
            'product_id',
            'option_id'
        )
            ->withPivot([
                'assignment_id',
                'sort_order',
                'is_default',
                'is_active',
            ])
            ->withTimestamps();
    }

    public function templates()
    {
        return $this->hasMany(ProductTemplate::class, 'product_id', 'product_id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'product_id', 'product_id');
    }

    public function displayPriceTier()
    {
        return $this->hasOneThrough(
            ProductPriceRuleTier::class,
            ProductPriceRule::class,
            'product_id',
            'rule_id',
            'product_id',
            'rule_id'
        )
            ->where('product_price_rule_tiers.is_display', 1)
            ->where('product_price_rule_tiers.is_active', 1);
    }

    public function optionPriceRules()
    {
        return $this->hasMany(
            ProductOptionPriceRule::class,
            'product_id',
            'product_id'
        )
            ->where('is_active', 1)
            ->orderBy('option_price_rule_id');
    }
}
